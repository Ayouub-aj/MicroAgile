# 📋 MicroAgile — DevTrack Laravel SCRUM Board
**Sprint:** 04/05/2026 → 08/05/2026 | **Deadline:** Vendredi 08/05 – 16:00 | **Mode:** Binôme

---

## 🐳 Docker Setup — Empty GitHub Repo (Start Here)

> Follow these steps **before anything else** on an empty cloned repo.

### Step 1 — Clone your repo and enter it

```bash
git clone https://github.com/<your-org>/microagile.git
cd microagile
```

### Step 2 — Create Laravel project in a temp folder, then move files

```bash
# Create Laravel app in a temp folder (outside the repo)
composer create-project laravel/laravel microagile-temp

# Copy everything into your cloned repo
cp -r microagile-temp/. .

# Remove the temp folder
rm -rf microagile-temp
```

### Step 3 — Install Laravel Sail

```bash
composer require laravel/sail --dev
php artisan sail:install
# When prompted, select: mysql
```

### Step 4 — Files to create / configure

**`.env`** — Edit the generated `.env`:
```env
APP_NAME=MicroAgile
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=microagile
DB_USERNAME=sail
DB_PASSWORD=password
```

**`.gitignore`** — Verify these lines exist (add if missing):
```
/vendor/
/node_modules/
.env
.env.backup
/storage/*.key
/public/hot
/public/storage
```

**`compose.yaml`** — Created automatically by Sail. Verify it contains these services:
```yaml
services:
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.3
            dockerfile: Dockerfile
        ports:
            - '${APP_PORT:-80}:80'
        environment:
            - DB_HOST=mysql
        depends_on:
            - mysql
    mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3306}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
        volumes:
            - 'sail-mysql:/var/lib/mysql'
    phpmyadmin:
        image: phpmyadmin/phpmyadmin
        ports:
            - '8081:80'
        environment:
            PMA_HOST: mysql
        depends_on:
            - mysql
volumes:
    sail-mysql:
        driver: local
```

> ⚠️ **phpMyAdmin is not added by Sail automatically** — add it manually to `compose.yaml` as shown above.

### Step 5 — Start Docker and verify

```bash
./vendor/bin/sail up -d

# Add alias for convenience (add to ~/.bashrc or ~/.zshrc)
alias sail='./vendor/bin/sail'

# Verify the app is running
# → http://localhost should show the Laravel welcome page
```

### Step 6 — Generate app key

```bash
sail artisan key:generate
```

### Step 7 — Initial commit

```bash
git add .
git commit -m "Initial Laravel + Sail setup with MySQL and phpMyAdmin"
git push origin main
```

### Step 8 — Create feature branches

```bash
git checkout -b feature/auth
git push origin feature/auth

git checkout main
git checkout -b feature/projects
git push origin feature/projects

git checkout main
git checkout -b feature/tasks
git push origin feature/tasks

git checkout main
git checkout -b feature/api
git push origin feature/api
```

---

## 📋 Legend

| Label | Meaning |
|-------|---------|
| `ARCH` | Architecture / Setup |
| `DOCKER` | Docker / Infrastructure |
| `AUTH` | Authentication |
| `PROJ` | Project Management |
| `TASK` | Task Management |
| `POLICY` | Policies & Authorization |
| `API` | API Endpoint |
| `QA` | Code Quality / Security |
| `DEBUG` | Debugging Tools |
| `DOC` | Documentation / Livrables |
| `BONUS` | Bonus Feature |

---

## 🏃 Sprint 1 — Infrastructure & Setup
**Objectif:** Docker up, Laravel initialized, migrations ready, debugging tools installed, Tailwind working
**Durée:** Jour 1 — Lundi 04/05

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-01 | Initialize GitHub repo + branches | `ARCH` | High | 0.3h | **Action:**<br>- Create branches: `feature/auth`, `feature/projects`, `feature/tasks`, `feature/api`<br>- `.gitignore`: ignore `vendor/`, `.env`, `node_modules/`, `storage/*.key`<br>- `README.md`: push initial skeleton<br>- Invite teammate as collaborator on GitHub |
| [ ] | T-02 | Install Laravel via Sail | `DOCKER` | High | 1h | **Action:**<br>- Follow the Docker Setup section above<br>- `composer create-project laravel/laravel microagile-temp`<br>- Copy files into cloned repo<br>- `composer require laravel/sail --dev`<br>- `php artisan sail:install` → choose **mysql**<br>- Add phpMyAdmin service manually to `compose.yaml` |
| [ ] | T-03 | Start Docker + verify environment | `DOCKER` | High | 0.5h | **Action:**<br>- `./vendor/bin/sail up -d`<br>- Verify `http://localhost` → Laravel welcome page<br>- Verify `http://localhost:8081` → phpMyAdmin login<br>- Add alias: `alias sail='./vendor/bin/sail'` |
| [ ] | T-04 | Configure `.env` | `ARCH` | High | 0.3h | **Files to Edit:**<br>- `.env`: `APP_NAME=MicroAgile`, `DB_HOST=mysql`, `DB_DATABASE=microagile`, `DB_USERNAME=sail`, `DB_PASSWORD=password`<br>- `APP_URL=http://localhost`<br>- Run `sail artisan key:generate` if not done |
| [ ] | T-05 | Install Tailwind CSS (v4) | `ARCH` | High | 0.5h | **Action:**<br>- `sail npm install`<br>- `sail npm install -D @tailwindcss/vite`<br>- Edit `vite.config.js` → add `@tailwindcss/vite` plugin<br>- `resources/css/app.css` → add `@import 'tailwindcss';`<br>- `sail npm run dev` → verify styles load on welcome page |
| [ ] | T-06 | Migration — Table `users` (default) | `ARCH` | High | 0.2h | **Action:**<br>- Default Laravel `users` migration is already present<br>- Verify columns: `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `timestamps`<br>- No changes needed — Breeze will handle it |
| [ ] | T-07 | Migration — Table `projects` | `ARCH` | High | 1h | **Files to Create:**<br>- `sail artisan make:migration create_projects_table`<br>- **Columns:** `id` (PK), `title` (string, 255), `description` (text, nullable), `deadline` (date, nullable), `deleted_at` (timestamp, nullable — for SoftDeletes), `timestamps`<br>- Use `$table->softDeletes();` — do NOT add `deleted_at` manually |
| [ ] | T-08 | Migration — Pivot table `project_user` | `ARCH` | High | 1h | **Files to Create:**<br>- `sail artisan make:migration create_project_user_table`<br>- **Columns:** `project_id` (unsignedBigInteger, FK → projects.id, onDelete cascade), `user_id` (unsignedBigInteger, FK → users.id, onDelete cascade), `role` (enum: `['lead', 'developer']`)<br>- **Primary key:** composite `['project_id', 'user_id']` — use `$table->primary(['project_id', 'user_id']);`<br>- NO `id()`, NO `timestamps()` |
| [ ] | T-09 | Migration — Table `tasks` | `ARCH` | High | 1h | **Files to Create:**<br>- `sail artisan make:migration create_tasks_table`<br>- **Columns:** `id` (PK), `title` (string, 255), `description` (text, nullable), `status` (enum: `['todo', 'in_progress', 'done']`, default: `todo`), `priority` (enum: `['low', 'medium', 'high']`, default: `medium`), `deadline` (date, nullable), `project_id` (FK → projects.id, onDelete cascade), `user_id` (FK → users.id, onDelete set null, nullable), `timestamps` |
| [ ] | T-10 | Model `Project` + SoftDeletes + relationships | `ARCH` | High | 1h | **Files to Create:**<br>- `sail artisan make:model Project`<br>- Add `use SoftDeletes;` trait (import `Illuminate\Database\Eloquent\SoftDeletes`)<br>- `$fillable = ['title', 'description', 'deadline']`<br>- Relationships:<br>&nbsp;&nbsp;- `members(): BelongsToMany` → `User::class` with `->withPivot('role')->withTimestamps()`<br>&nbsp;&nbsp;- `tasks(): HasMany` → `Task::class`<br>- **Mutator:** `setTitleAttribute(string $value)` → `$this->attributes['title'] = ucfirst($value);`<br>- **Bonus scope:** `scopeUrgent(Builder $query)` → tasks with deadline ≤ 48h and status != done |
| [ ] | T-11 | Model `Task` + accessors + scope | `ARCH` | High | 1h | **Files to Create:**<br>- `sail artisan make:model Task`<br>- `$fillable = ['title', 'description', 'status', 'priority', 'deadline', 'project_id', 'user_id']`<br>- Relationships:<br>&nbsp;&nbsp;- `project(): BelongsTo` → `Project::class`<br>&nbsp;&nbsp;- `assignedUser(): BelongsTo` → `User::class`<br>- **Accessor `getStatusLabelAttribute()`:**<br>&nbsp;&nbsp;`return match($this->status) { 'todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé' };`<br>- **Accessor `getDeadlineStatusAttribute()`:**<br>&nbsp;&nbsp;Returns `'overdue'`, `'urgent'` (< 48h), or `'ok'` based on deadline vs now<br>- **Scope `scopeUrgent(Builder $query)`:**<br>&nbsp;&nbsp;`->where('status', '!=', 'done')->where('deadline', '<=', now()->addHours(48))` |
| [ ] | T-12 | Model `User` + relationships | `ARCH` | High | 0.5h | **Files to Edit:**<br>- `app/Models/User.php`<br>- Add: `projects(): BelongsToMany` → `Project::class` with `->withPivot('role')->withTimestamps()`<br>- Add: `assignedTasks(): HasMany` → `Task::class`<br>- Verify `$fillable` includes `name`, `email`, `password` |
| [ ] | T-13 | Seeders | `ARCH` | High | 1.5h | **Files to Create:**<br>- `sail artisan make:seeder UserSeeder` → 3 users: 1 lead + 2 developers, all password: `password`<br>- `sail artisan make:seeder ProjectSeeder` → 2 projects, each with a lead attached via pivot<br>- `sail artisan make:seeder TaskSeeder` → 6 tasks distributed across both projects with mixed statuses and priorities<br>- `DatabaseSeeder.php`: call all three seeders in order<br>- `sail artisan migrate:fresh --seed` must pass ✅ |
| [ ] | T-14 | Install Laravel Debugbar | `DEBUG` | High | 0.5h | **Action:**<br>- `sail composer require barryvdh/laravel-debugbar --dev`<br>- Set `DEBUGBAR_ENABLED=true` in `.env` (only in dev)<br>- Open `http://localhost` → Debugbar panel must appear at bottom<br>- Confirm the **SQL** tab is visible |
| [ ] | T-15 | Install Laravel Telescope | `DEBUG` | High | 0.5h | **Action:**<br>- `sail composer require laravel/telescope --dev`<br>- `sail artisan telescope:install`<br>- `sail artisan migrate`<br>- Verify `http://localhost/telescope` is accessible<br>- Verify **Requests** tab shows incoming requests |

**Sprint 1 — Definition of Done:**
- [ ] `sail up -d` starts all services without error
- [ ] `http://localhost` shows Laravel + Tailwind working
- [ ] `http://localhost:8081` shows phpMyAdmin
- [ ] `sail artisan migrate:fresh --seed` runs cleanly with no errors
- [ ] Debugbar visible on every page
- [ ] `/telescope` accessible and logging requests
- [ ] All 3 models have `$fillable`, relationships and accessors defined

---

## 🏃 Sprint 2 — Authentication (US1)
**Objectif:** Registration, Login and Logout fully functional with main layout
**Durée:** Jour 2 matin — Mardi 05/05
**Branch:** `feature/auth`

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-16 | Install Laravel Breeze (Blade) | `AUTH` | High | 0.5h | **Action:**<br>- `sail composer require laravel/breeze --dev`<br>- `sail artisan breeze:install blade`<br>- `sail npm run dev`<br>- `sail artisan migrate`<br>- Test `http://localhost/register` → register form must appear |
| [ ] | T-17 | `US1` — Register / Login / Logout | `AUTH` | High | 1.5h | **Files to Verify/Edit:**<br>- `resources/views/auth/register.blade.php`: fields `name`, `email`, `password`, `password_confirmation`, `@csrf`, `@error` messages<br>- `resources/views/auth/login.blade.php`: fields `email`, `password`, `@csrf`<br>- `RegisteredUserController@store`: validate → `User::create()` → redirect `/dashboard`<br>- `AuthenticatedSessionController@destroy`: `Auth::logout()` → redirect `/`<br>- **Routes:** `GET/POST /register`, `GET/POST /login`, `POST /logout` |
| [ ] | T-18 | Main layout `layouts/app.blade.php` | `AUTH` | High | 2h | **Files to Create/Edit:**<br>- `resources/views/layouts/app.blade.php`:<br>&nbsp;&nbsp;- `@vite(['resources/css/app.css', 'resources/js/app.js'])` in `<head>`<br>&nbsp;&nbsp;- Navbar with `@auth` → Dashboard + Logout button `\| @guest` → Login + Register<br>&nbsp;&nbsp;- `@can('create', App\Models\Project::class)` → Show "New Project" in nav (lead only)<br>&nbsp;&nbsp;- Flash messages: `@if(session('success'))` and `@if(session('error'))`<br>&nbsp;&nbsp;- `@yield('content')` in main body |
| [ ] | T-19 | Protect all routes under `auth` middleware | `AUTH` | High | 0.5h | **Files to Edit:**<br>- `routes/web.php`: wrap all project + task routes in `Route::middleware('auth')->group(function () { ... })`<br>- Redirect to `/login` is default behavior with `auth` middleware<br>- Test: direct access to `/dashboard` without login → must redirect to `/login` |

**Sprint 2 — Definition of Done:**
- [ ] Registration creates a new user and redirects to `/dashboard`
- [ ] Login with seeded credentials works
- [ ] Logout redirects to `/` or `/login`
- [ ] Direct access to `/dashboard` without login → redirect `/login`
- [ ] `@auth`/`@guest` in layout shows correct nav links
- [ ] Flash messages display correctly

---

## 🏃 Sprint 3 — Projects CRUD + Members (US2, US3, US4, US5, US6, US7)
**Objectif:** Full project management — dashboard, create, edit, archive, restore, add/remove members
**Durée:** Jour 2 après-midi + Jour 3 matin — Mardi 05/05 → Mercredi 06/05
**Branch:** `feature/projects`

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-20 | `ProjectPolicy` — Authorization rules | `POLICY` | High | 1.5h | **Files to Create:**<br>- `sail artisan make:policy ProjectPolicy --model=Project`<br>- Register in `AuthServiceProvider` (or auto-discovered in Laravel 11+)<br>- **Methods to define:**<br>&nbsp;&nbsp;- `viewAny(User $user)` → always true (any auth user can see their projects)<br>&nbsp;&nbsp;- `view(User $user, Project $project)` → user is a member (lead or dev)<br>&nbsp;&nbsp;- `create(User $user)` → always true (any auth user can create a project)<br>&nbsp;&nbsp;- `update(User $user, Project $project)` → user is lead on this project<br>&nbsp;&nbsp;- `delete(User $user, Project $project)` → user is lead on this project<br>&nbsp;&nbsp;- `restore(User $user, Project $project)` → user is lead on this project<br>&nbsp;&nbsp;- `forceDelete(User $user, Project $project)` → user is lead on this project<br>- **Helper check:** `$project->members()->where('user_id', $user->id)->wherePivot('role', 'lead')->exists()` |
| [ ] | T-21 | `ProjectController` — Scaffold | `PROJ` | High | 0.5h | **Files to Create:**<br>- `sail artisan make:controller ProjectController --resource`<br>- Add `$this->authorize()` calls in every relevant method<br>- Use `with()` on every query — zero N+1 |
| [ ] | T-22 | `US2` — Dashboard (project list) | `PROJ` | High | 2h | **Files to Create/Edit:**<br>- `ProjectController@index`:<br>&nbsp;&nbsp;- Get projects where the auth user is a member: `auth()->user()->projects()->withCount(['tasks', 'tasks as done_tasks_count' => fn($q) => $q->where('status', 'done')])->with('members')->get()`<br>&nbsp;&nbsp;- Pass to view: `['projects' => $projects]`<br>- `resources/views/projects/index.blade.php`:<br>&nbsp;&nbsp;- Card per project: title, description, deadline, task progress (done / total)<br>&nbsp;&nbsp;- Badge: "Lead" or "Developer" based on pivot role<br>&nbsp;&nbsp;- `@can('update', $project)` → show Edit + Archive buttons<br>- **Route:** `GET /dashboard` → `projects.index` |
| [ ] | T-23 | `US3` — Create a project | `PROJ` | High | 1.5h | **Files to Create/Edit:**<br>- `ProjectController@create`: `$this->authorize('create', Project::class)`<br>- `ProjectController@store`:<br>&nbsp;&nbsp;- `$this->authorize('create', Project::class)`<br>&nbsp;&nbsp;- Validate via `StoreProjectRequest`<br>&nbsp;&nbsp;- `Project::create([...])` → then attach lead: `$project->members()->attach(auth()->id(), ['role' => 'lead'])`<br>- `resources/views/projects/create.blade.php`: fields `title`, `description`, `deadline`, `@csrf`<br>- **Routes:** `GET /projects/create` → `projects.create` · `POST /projects` → `projects.store` |
| [ ] | T-24 | `StoreProjectRequest` + `UpdateProjectRequest` | `ARCH` | High | 0.5h | **Files to Create:**<br>- `sail artisan make:request StoreProjectRequest`<br>&nbsp;&nbsp;- `authorize()` → `return true;` (Policy handles it in controller)<br>&nbsp;&nbsp;- `rules()`: `title\|required\|string\|max:255`, `description\|nullable\|string`, `deadline\|nullable\|date\|after:today`<br>- `sail artisan make:request UpdateProjectRequest`<br>&nbsp;&nbsp;- Same rules as Store |
| [ ] | T-25 | `US4` — Edit a project | `PROJ` | High | 1.5h | **Files to Create/Edit:**<br>- `ProjectController@edit`:<br>&nbsp;&nbsp;- `$this->authorize('update', $project)`<br>&nbsp;&nbsp;- Pass project to view<br>- `ProjectController@update`:<br>&nbsp;&nbsp;- `$this->authorize('update', $project)`<br>&nbsp;&nbsp;- Validate via `UpdateProjectRequest`<br>&nbsp;&nbsp;- `$project->update($validated)`<br>- `resources/views/projects/edit.blade.php`: pre-filled form with `@method('PUT')`, `@csrf`<br>- **Routes:** `GET /projects/{project}/edit` → `projects.edit` · `PUT /projects/{project}` → `projects.update` |
| [ ] | T-26 | `US5` — Archive a project (SoftDelete) | `PROJ` | High | 1h | **Files to Edit:**<br>- `ProjectController@archive`:<br>&nbsp;&nbsp;- `$this->authorize('delete', $project)`<br>&nbsp;&nbsp;- `$project->delete()` (SoftDeletes — sets `deleted_at`)<br>&nbsp;&nbsp;- `return redirect()->route('projects.index')->with('success', 'Projet archivé.')`<br>- Add archive button in `projects/index.blade.php` with `@method('PATCH')` form, `@csrf`, `@can('update', $project)`<br>- **Route:** `PATCH /projects/{project}/archive` → `projects.archive` |
| [ ] | T-27 | `US6` — Archives page + Restore | `PROJ` | High | 1.5h | **Files to Create/Edit:**<br>- `ProjectController@archives`:<br>&nbsp;&nbsp;- `$this->authorize('viewAny', Project::class)`<br>&nbsp;&nbsp;- `Project::onlyTrashed()->whereHas('members', fn($q) => $q->where('user_id', auth()->id())->where('role', 'lead'))->get()`<br>- `ProjectController@restore`:<br>&nbsp;&nbsp;- `$project = Project::onlyTrashed()->findOrFail($id)`<br>&nbsp;&nbsp;- `$this->authorize('restore', $project)`<br>&nbsp;&nbsp;- `$project->restore()`<br>- `resources/views/projects/archives.blade.php`: list archived projects, Restore button, optional Force Delete button<br>- **Routes:** `GET /projects/archives` → `projects.archives` · `PATCH /projects/{project}/restore` → `projects.restore` |
| [ ] | T-28 | `US7` — Add / Remove a member | `PROJ` | High | 2h | **Files to Edit:**<br>- `ProjectController@addMember`:<br>&nbsp;&nbsp;- `$this->authorize('update', $project)`<br>&nbsp;&nbsp;- Validate: `email\|required\|email\|exists:users,email`<br>&nbsp;&nbsp;- Find user by email → check not already member → `$project->members()->attach($user->id, ['role' => 'developer'])`<br>- `ProjectController@removeMember`:<br>&nbsp;&nbsp;- `$this->authorize('update', $project)`<br>&nbsp;&nbsp;- Prevent removing self (lead can't remove themselves)<br>&nbsp;&nbsp;- `$project->members()->detach($user->id)`<br>- `resources/views/projects/show.blade.php`: member list table, add member form (email input + submit), remove button per member with `@method('DELETE')`, `@csrf`<br>&nbsp;&nbsp;- `@can('update', $project)` wrapping all member management UI<br>- **Routes:** `POST /projects/{project}/members` → `projects.members.add` · `DELETE /projects/{project}/members/{user}` → `projects.members.remove` |
| [ ] | T-29 | `projects/show.blade.php` — Project detail page | `PROJ` | High | 1.5h | **Files to Create:**<br>- Load project with all relations: `Project::with(['members', 'tasks.assignedUser'])->findOrFail($id)`<br>- Display: title, description, deadline, member list with roles<br>- Task list section: title, status badge, priority badge, assigned developer, urgency indicator<br>- `@can('create', App\Models\Task::class)` → show "Add Task" button (leads only)<br>- **Route:** `GET /projects/{project}` → `projects.show` |

**Sprint 3 — Definition of Done:**
- [ ] Dashboard shows only projects the user is member of (as lead or developer)
- [ ] Create project → lead is auto-attached with role `lead` in pivot
- [ ] Edit project (lead only) — 403 for developers
- [ ] Archive removes project from dashboard, restore brings it back
- [ ] Add member by email works — developer gets `role = developer` in pivot
- [ ] Remove member works — cannot remove self
- [ ] `@can` blocks control all action buttons in views
- [ ] Zero `abort(403)` calls — all authorization through `$this->authorize()`

---

## 🏃 Sprint 4 — Tasks CRUD + Status (US8, US9, US10, US11, US12)
**Objectif:** Full task management — list, create, edit, delete, status change, urgency indicator
**Durée:** Jour 3 après-midi + Jour 4 matin — Mercredi 06/05 → Jeudi 07/05
**Branch:** `feature/tasks`

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-30 | `TaskPolicy` — Authorization rules | `POLICY` | High | 1.5h | **Files to Create:**<br>- `sail artisan make:policy TaskPolicy --model=Task`<br>- **Methods to define:**<br>&nbsp;&nbsp;- `view(User $user, Task $task)` → user is a member of the task's project<br>&nbsp;&nbsp;- `create(User $user, Project $project)` → user is lead on the project<br>&nbsp;&nbsp;- `update(User $user, Task $task)` → user is lead on the task's project<br>&nbsp;&nbsp;- `delete(User $user, Task $task)` → user is lead on the task's project<br>&nbsp;&nbsp;- `updateStatus(User $user, Task $task)` → user is the assigned developer OR is lead<br>- **Note:** all checks use `$task->project->members()->where(...)->exists()` — always eager-load `project` first |
| [ ] | T-31 | `TaskController` — Scaffold | `TASK` | High | 0.5h | **Files to Create:**<br>- `sail artisan make:controller TaskController --resource`<br>- All methods must call `$this->authorize()` before any action<br>- All queries must use `with()` — zero N+1 |
| [ ] | T-32 | `US8` — Task list for a project | `TASK` | High | 2h | **Files to Create/Edit:**<br>- `TaskController@index`:<br>&nbsp;&nbsp;- `$this->authorize('view', $project)` (using ProjectPolicy)<br>&nbsp;&nbsp;- `$tasks = $project->tasks()->with('assignedUser')->get()`<br>&nbsp;&nbsp;- Use `$task->deadline_status` accessor for urgency: shows alert badge if `urgent` or `overdue`<br>- `resources/views/tasks/index.blade.php`:<br>&nbsp;&nbsp;- Table: title, status badge (using `$task->status_label`), priority badge, assigned developer, deadline with urgency indicator (⚠️ if urgent, 🔴 if overdue)<br>&nbsp;&nbsp;- `@can('update', $task)` → Edit + Delete buttons (lead only)<br>&nbsp;&nbsp;- Status change form always visible for the assigned developer<br>- **Route:** `GET /projects/{project}/tasks` → `tasks.index` |
| [ ] | T-33 | `US9` — Create a task | `TASK` | High | 2h | **Files to Create/Edit:**<br>- `TaskController@create`:<br>&nbsp;&nbsp;- `$this->authorize('create', [Task::class, $project])`<br>&nbsp;&nbsp;- Pass `$project` and `$members = $project->members()->where('role', 'developer')->get()` to view<br>- `TaskController@store`:<br>&nbsp;&nbsp;- `$this->authorize('create', [Task::class, $project])`<br>&nbsp;&nbsp;- Validate via `StoreTaskRequest`<br>&nbsp;&nbsp;- `Task::create([..., 'project_id' => $project->id])`<br>- `resources/views/tasks/create.blade.php`:<br>&nbsp;&nbsp;- Fields: `title`, `description`, `deadline`, `priority` (select: low/medium/high), `user_id` (select: developers of the project), `@csrf`<br>- **Routes:** `GET /projects/{project}/tasks/create` → `tasks.create` · `POST /projects/{project}/tasks` → `tasks.store` |
| [ ] | T-34 | `StoreTaskRequest` + `UpdateTaskRequest` | `ARCH` | High | 0.5h | **Files to Create:**<br>- `sail artisan make:request StoreTaskRequest`<br>&nbsp;&nbsp;- `rules()`: `title\|required\|string\|max:255`, `description\|nullable\|string`, `deadline\|nullable\|date`, `priority\|required\|in:low,medium,high`, `user_id\|nullable\|exists:users,id`<br>- `sail artisan make:request UpdateTaskRequest`<br>&nbsp;&nbsp;- Same rules as Store<br>- Both: `authorize()` → `return true;` (Policy handles it in controller) |
| [ ] | T-35 | `US10` — Edit a task | `TASK` | High | 1.5h | **Files to Create/Edit:**<br>- `TaskController@edit`:<br>&nbsp;&nbsp;- `$this->authorize('update', $task)`<br>&nbsp;&nbsp;- Pass `$task`, `$project`, developers list<br>- `TaskController@update`:<br>&nbsp;&nbsp;- `$this->authorize('update', $task)`<br>&nbsp;&nbsp;- Validate via `UpdateTaskRequest`<br>&nbsp;&nbsp;- `$task->update($validated)`<br>- `resources/views/tasks/edit.blade.php`: pre-filled form, `@method('PUT')`, `@csrf`<br>- **Routes:** `GET /tasks/{task}/edit` → `tasks.edit` · `PUT /tasks/{task}` → `tasks.update` |
| [ ] | T-36 | `US11` — Change task status (developer) | `TASK` | High | 1.5h | **Files to Edit:**<br>- `TaskController@updateStatus`:<br>&nbsp;&nbsp;- `$this->authorize('updateStatus', $task)` (custom TaskPolicy method)<br>&nbsp;&nbsp;- Cycle: `todo → in_progress → done` (and optionally `done → todo`)<br>&nbsp;&nbsp;- `$task->update(['status' => $nextStatus])`<br>&nbsp;&nbsp;- Redirect back with flash<br>- `tasks/index.blade.php`: small PATCH form button per task, `@method('PATCH')`, `@csrf`<br>&nbsp;&nbsp;- Show to assigned developer AND lead, but block others via Policy<br>- **Route:** `PATCH /tasks/{task}/status` → `tasks.status` |
| [ ] | T-37 | `US12` — Delete a task | `TASK` | High | 1h | **Files to Edit:**<br>- `TaskController@destroy`:<br>&nbsp;&nbsp;- `$this->authorize('delete', $task)`<br>&nbsp;&nbsp;- `$task->delete()` + redirect with flash message<br>- `tasks/index.blade.php`: delete form with `@method('DELETE')`, `@csrf`, JS confirm dialog<br>&nbsp;&nbsp;- `@can('delete', $task)` wrapping the button<br>- **Route:** `DELETE /tasks/{task}` → `tasks.destroy` |

**Sprint 4 — Definition of Done:**
- [ ] Task list shows all tasks for a project (lead and developer members)
- [ ] Status badges use `$task->status_label` accessor — no raw enum values in views
- [ ] Deadline urgency indicator displays correctly (⚠️ urgent, 🔴 overdue)
- [ ] Create task (lead only) — `user_id` assign to a project developer
- [ ] Edit task (lead only) — 403 for developers
- [ ] Status change works for the assigned developer and lead
- [ ] Delete task (lead only) — 403 for developers
- [ ] Zero `abort(403)` — all through `$this->authorize()`
- [ ] Zero N+1 — all queries use `with()`

---

## 🏃 Sprint 5 — API Endpoint (US13)
**Objectif:** REST API endpoint returning project tasks as JSON via TaskResource
**Durée:** Jour 4 après-midi — Jeudi 07/05
**Branch:** `feature/api`

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-38 | `TaskResource` — JSON formatting | `API` | High | 1h | **Files to Create:**<br>- `sail artisan make:resource TaskResource`<br>- `app/Http/Resources/TaskResource.php` — `toArray()` must return:<br>```php<br>return [<br>    'id'              => $this->id,<br>    'title'           => $this->title,<br>    'description'     => $this->description,<br>    'status'          => $this->status,<br>    'status_label'    => $this->status_label,       // accessor<br>    'priority'        => $this->priority,<br>    'deadline'        => $this->deadline,<br>    'deadline_status' => $this->deadline_status,    // accessor<br>    'assigned_to'     => $this->assignedUser?->name,<br>];```<br>- **Key requirement:** `status_label` and `deadline_status` come from accessors in the model, not from logic in the Resource |
| [ ] | T-39 | `US13` — API route + controller method | `API` | High | 1h | **Files to Edit:**<br>- `routes/api.php`:<br>&nbsp;&nbsp;- `Route::get('/projects/{project}/tasks', [TaskController::class, 'apiIndex']);`<br>&nbsp;&nbsp;- **No auth middleware on API** — as per brief (public endpoint)<br>- `TaskController@apiIndex`:<br>&nbsp;&nbsp;- `$tasks = $project->tasks()->with('assignedUser')->get()`<br>&nbsp;&nbsp;- `return TaskResource::collection($tasks);`<br>- Test in browser: `http://localhost/api/projects/1/tasks` → must return clean JSON |
| [ ] | T-40 | Verify API JSON output | `API` | High | 0.3h | **Action:**<br>- Open `http://localhost/api/projects/1/tasks` in browser or Postman<br>- Confirm response format:<br>```json<br>{<br>  "data": [<br>    {<br>      "id": 1,<br>      "title": "Setup auth",<br>      "status": "in_progress",<br>      "status_label": "En cours",<br>      "priority": "high",<br>      "deadline": "2026-05-08",<br>      "deadline_status": "urgent",<br>      "assigned_to": "Ali Benali"<br>    }<br>  ]<br>}<br>```<br>- `status_label` must show French label (from accessor), NOT raw value<br>- `deadline_status` must show urgency level (from accessor) |

**Sprint 5 — Definition of Done:**
- [ ] `GET /api/projects/{project}/tasks` returns HTTP 200 with `Content-Type: application/json`
- [ ] Response includes `status_label` computed from accessor
- [ ] Response includes `deadline_status` computed from accessor
- [ ] `assigned_to` shows the developer's name (eager-loaded, no N+1)
- [ ] Works for a project with 0 tasks → returns `{"data": []}`

---

## 🏃 Sprint 6 — Bonus Features
**Objectif:** Extra features for additional credit
**Durée:** Jour 4 fin — Jeudi 07/05 (si temps disponible)

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-41 | Bonus — Force delete from Archives | `BONUS` | Low | 0.5h | **Files to Edit:**<br>- `ProjectController@forceDelete`:<br>&nbsp;&nbsp;- `$project = Project::onlyTrashed()->findOrFail($id)`<br>&nbsp;&nbsp;- `$this->authorize('forceDelete', $project)`<br>&nbsp;&nbsp;- `$project->forceDelete()`<br>- `projects/archives.blade.php`: "Supprimer définitivement" button with double confirm<br>- **Route:** `DELETE /projects/{project}/force` → `projects.force-delete` |
| [ ] | T-42 | Bonus — Mutator `ucfirst` on title | `BONUS` | Low | 0.3h | **Files to Edit:**<br>- `app/Models/Project.php`<br>- Add mutator: `public function setTitleAttribute(string $value): void { $this->attributes['title'] = ucfirst($value); }`<br>- Test: create project with title `"test project"` → stored as `"Test project"` |
| [ ] | T-43 | Bonus — `scopeUrgent()` on Task | `BONUS` | Low | 0.5h | **Files to Edit:**<br>- `app/Models/Task.php`<br>- Add: `public function scopeUrgent(Builder $query): Builder { return $query->where('status', '!=', 'done')->where('deadline', '<=', now()->addHours(48)); }`<br>- Use in dashboard: `$urgentTasks = $project->tasks()->urgent()->count()` and display a warning badge on the project card |

---

## 🏃 Sprint 7 — QA, Debugging & Livrables
**Objectif:** Security audit, N+1 fix, Telescope prep, README, commits check
**Durée:** Jour 5 — Vendredi 08/05

| Done | # | Task | Label | Priority | Time | Detailed Implementation & Files |
| :---: | :--- | :--- | :---: | :---: | :---: | :--- |
| [ ] | T-44 | Audit — All Form Requests in place | `QA` | High | 0.5h | **Files to Audit:**<br>- `ProjectController@store` → uses `StoreProjectRequest` (not `$request->validate()`) ✅<br>- `ProjectController@update` → uses `UpdateProjectRequest` ✅<br>- `TaskController@store` → uses `StoreTaskRequest` ✅<br>- `TaskController@update` → uses `UpdateTaskRequest` ✅<br>- Auth forms → handled by Breeze ✅<br>- `addMember` → inline `$request->validate(['email' => 'required|email|exists:users,email'])` is acceptable here |
| [ ] | T-45 | Audit — `@csrf` on all forms | `QA` | High | 0.3h | **Forms to Audit:**<br>- `projects/create.blade.php` → `@csrf` ✅<br>- `projects/edit.blade.php` → `@csrf` + `@method('PUT')` ✅<br>- `projects/show.blade.php` (add member form) → `@csrf` ✅<br>- `projects/show.blade.php` (remove member form) → `@csrf` + `@method('DELETE')` ✅<br>- `projects/index.blade.php` (archive form) → `@csrf` + `@method('PATCH')` ✅<br>- `projects/archives.blade.php` (restore form) → `@csrf` + `@method('PATCH')` ✅<br>- `tasks/create.blade.php` → `@csrf` ✅<br>- `tasks/edit.blade.php` → `@csrf` + `@method('PUT')` ✅<br>- `tasks/index.blade.php` (status form) → `@csrf` + `@method('PATCH')` ✅<br>- `tasks/index.blade.php` (delete form) → `@csrf` + `@method('DELETE')` ✅ |
| [ ] | T-46 | Audit — `$fillable` on all models | `QA` | High | 0.2h | **Files to Check:**<br>- `Project::$fillable` = `['title', 'description', 'deadline']`<br>- `Task::$fillable` = `['title', 'description', 'status', 'priority', 'deadline', 'project_id', 'user_id']`<br>- `User::$fillable` = `['name', 'email', 'password']` (set by Breeze) |
| [ ] | T-47 | Audit — Zero `abort(403)` | `POLICY` | High | 0.3h | **Action:**<br>- `grep -r "abort(403)" app/Http/Controllers/` → must return **0 results**<br>- Every authorization must go through `$this->authorize()`<br>- Every view action button must use `@can` |
| [ ] | T-48 | Debugbar — Detect and fix N+1 | `DEBUG` | High | 1h | **Action:**<br>- Open `/dashboard` → check SQL tab in Debugbar<br>- If N+1 detected (repeated queries for tasks or members): fix with `->with(['tasks', 'members'])`<br>- Open `/projects/{id}` → check tasks queries<br>- If N+1 detected for assignedUser: fix with `->with('assignedUser')`<br>- Confirm total query count is stable regardless of number of projects/tasks |
| [ ] | T-49 | Telescope — Trace key HTTP requests | `DEBUG` | High | 1h | **Action — trace these 4 requests:**<br>- `POST /projects` → create project: payload received, insert query, pivot attach query<br>- `POST /projects/{id}/tasks` → create task: payload, validation, insert query<br>- `PATCH /tasks/{id}/status` → status change: which user, which task, update query<br>- `GET /api/projects/{id}/tasks` → API: no auth, query, JSON response<br>- **For each:** read payload, SQL queries, response status, any exceptions<br>- Be able to explain the full path: request → route → middleware → policy → controller → DB → response |
| [ ] | T-50 | Full flow test — both roles | `QA` | High | 1h | **Scenarios to verify:**<br>- Access `/dashboard` without login → redirect `/login` ✅<br>- Register new user → dashboard shows empty projects ✅<br>- Login as lead → create project → add developer member ✅<br>- Lead creates task → assigns to developer ✅<br>- Login as developer → sees project in dashboard ✅<br>- Developer changes task status → works ✅<br>- Developer tries `GET /projects/{id}/edit` → 403 via ProjectPolicy ✅<br>- Developer tries `PUT /tasks/{id}` → 403 via TaskPolicy ✅<br>- Lead archives project → disappears from dashboard ✅<br>- Lead restores from archives → reappears in dashboard ✅<br>- `GET /api/projects/{id}/tasks` → clean JSON with accessors ✅ |
| [ ] | T-51 | MCD & MLD | `DOC` | High | 1h | **Deadline: Lundi 04/05 avant 14:00**<br>- **MCD:** Entités (User, Project, Task), attributs sans types ni FK, relations avec cardinalités<br>- **MLD:** Tables avec types, PK (souligné), FK (avec flèches), table pivot `project_user(project_id, user_id, role)`<br>- Doit correspondre exactement aux migrations<br>- Format: draw.io, Looping, ou papier scanné |
| [ ] | T-52 | `README.md` complet | `DOC` | High | 0.5h | **Vérifier que README contient:**<br>- Description du projet<br>- Stack: Laravel 11, PHP 8.3, MySQL 8.0, Docker Sail, Tailwind CSS v4<br>- Instructions d'installation complètes (clone → env → sail up → migrate:fresh --seed)<br>- Credentials de test (email + password)<br>- Table des routes (web + API)<br>- MCD & MLD (diagrams Mermaid ou liens)<br>- Section concepts clés (Policies, SoftDeletes, Accessors) |
| [ ] | T-53 | Jira board + Backlog | `DOC` | High | 0.3h | **Action:**<br>- Vérifier que le board Jira est partagé avec `abderahmane.merradou@gmail.com`<br>- Sprint backlog complet avec toutes les US (US1 → US13)<br>- Chaque US a un ticket avec critères d'acceptation<br>- Historique visible (pas de tickets créés à la dernière minute) |
| [ ] | T-54 | Git audit — commits & branches | `DOC` | High | 0.3h | **Action:**<br>- `git log --oneline` → vérifier ≥ 20 commits répartis entre les 2 membres<br>- Vérifier branches: `feature/auth`, `feature/projects`, `feature/tasks`, `feature/api`<br>- Vérifier PR avec reviews avant merge sur main<br>- Zéro commit direct sur `main`<br>- Exemples de messages corrects: `Add ProjectPolicy with lead/developer checks`, `Implement SoftDeletes on Project model`, `Add status_label accessor to Task model`, `Fix N+1 on project dashboard with eager loading` |

**Sprint 7 — Definition of Done:**
- [ ] All forms have `@csrf` and use Form Request classes
- [ ] All models have `$fillable` defined
- [ ] Zero `abort(403)` in controllers — all through `$this->authorize()`
- [ ] N+1 confirmed fixed via Debugbar
- [ ] 4 key requests traced end-to-end in Telescope
- [ ] Both role flows tested and working
- [ ] README complete with install instructions + credentials
- [ ] ≥ 20 commits across both members
- [ ] All PRs reviewed before merge on main

---

## 📦 Final Deliverables Checklist

| Livrable | Critère | Statut |
|----------|---------|--------|
| GitHub Repo | ≥ 20 commits répartis entre les 2 membres | ⬜ |
| GitHub Repo | Feature branches + PR avec reviews avant merge | ⬜ |
| GitHub Repo | Zéro commit direct sur `main` | ⬜ |
| GitHub Repo | Au moins 1 review visible par PR | ⬜ |
| Jira | Board partagé avec `abderahmane.merradou@gmail.com` avant lundi 12h | ⬜ |
| Jira | Sprint backlog complet dès lundi après-midi | ⬜ |
| MCD | Entités, attributs, relations avec cardinalités (sans types ni FK) | ⬜ |
| MLD | Tables, types, PK, FK — table pivot incluse | ⬜ |
| MCD/MLD | Soumis avant lundi 04/05 – 14:00 | ⬜ |
| Présentation | Structure 10 slides respectée | ⬜ |
| Présentation | MCD et MLD slides obligatoires | ⬜ |
| Présentation | Démo live fonctionnelle | ⬜ |
| Présentation | Règles: max 30 mots/slide · min 1 visuel · police min 24px | ⬜ |
| README.md | Installation complète avec Sail/Docker + credentials de test | ⬜ |
| Migrations | Toutes les tables via migrations (zéro SQL manuel) | ⬜ |
| Seeders | Users (lead + devs), projects, tasks avec statuts variés | ⬜ |
| Debugbar | N+1 identifié et corrigé | ⬜ |
| Telescope | 4 requêtes tracées de bout en bout | ⬜ |

---

## 🏆 Performance Criteria

### Architecture Laravel (35%)
| Critère | Statut |
|---------|--------|
| `ProjectPolicy` + `TaskPolicy` — `$this->authorize()` dans tous les controllers | ⬜ |
| `@can` dans toutes les vues pour masquer les boutons selon le rôle | ⬜ |
| Zéro `abort(403)` manuel dans le code | ⬜ |
| `StoreProjectRequest`, `UpdateProjectRequest`, `StoreTaskRequest`, `UpdateTaskRequest` utilisées | ⬜ |
| Many-to-many `users ↔ projects` avec colonne `role` dans la pivot | ⬜ |
| `SoftDeletes` sur `Project` avec archive et restauration fonctionnels | ⬜ |
| Accessors `status_label` et `deadline_status` utilisés dans vues ET dans `TaskResource` | ⬜ |
| Zéro N+1 vérifié et confirmé avec Debugbar | ⬜ |

### Fonctionnalités (25%)
| Critère | Statut |
|---------|--------|
| CRUD projets complet avec gestion des membres (lead vs developer) | ⬜ |
| CRUD tâches avec assignation et changement de statut selon rôle | ⬜ |
| Archive / restauration de projets fonctionnelle | ⬜ |
| `GET /api/projects/{project}/tasks` retourne JSON propre avec `TaskResource` | ⬜ |

### Présentation (20%)
| Critère | Statut |
|---------|--------|
| Structure 10 slides respectée avec MCD et MLD | ⬜ |
| Explication claire des concepts nouveaux (Policies, SoftDeletes, Accessors) | ⬜ |
| Démo live fonctionnelle | ⬜ |
| Règles slides respectées (mots, visuels, numérotation, police) | ⬜ |

### Collaboration & Process (20%)
| Critère | Statut |
|---------|--------|
| ≥ 20 commits répartis entre les 2 membres | ⬜ |
| Feature branches + PR avec reviews avant merge | ⬜ |
| Jira board à jour avec historique visible | ⬜ |
| Zéro commit direct sur `main` | ⬜ |

---

## 🎤 Debugging Session Prep (30 min par binôme)

### Phase 1 — Telescope (10 min)
| Request à tracer | Préparé |
|-----------------|---------|
| `POST /projects` — Créer un projet: payload reçu, insert query, pivot attach | ⬜ |
| `POST /projects/{id}/tasks` — Créer une tâche: validation, insert query | ⬜ |
| `PATCH /tasks/{id}/status` — Changement de statut: quel user, quelle tâche, update query | ⬜ |

### Phase 2 — Bug Hunt (10 min)
| Compétence | Préparé |
|-----------|---------|
| Utiliser Debugbar pour compter les queries sur une page et détecter un N+1 | ⬜ |
| Utiliser Telescope pour trouver une erreur sans lire le code directement | ⬜ |
| Corriger le bug identifié par les outils, pas par lecture de code | ⬜ |

### Phase 3 — Questions individuelles (10 min)
| Membre | Question | Préparé |
|--------|----------|---------|
| Membre 1 | "Un developer tente `GET /projects/{id}/edit`. Montre dans Telescope ce qui se passe et explique comment la `ProjectPolicy` bloque cette action." | ⬜ |
| Membre 2 | "Ouvre `/api/projects/{id}/tasks`. Explique comment l'accessor `status_label` transforme la valeur brute avant qu'elle apparaisse dans la `TaskResource`. Montre le code dans le model." | ⬜ |

---

*Dernière mise à jour : 04/05/2026*