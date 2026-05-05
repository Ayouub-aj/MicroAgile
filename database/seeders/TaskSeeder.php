<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $nova  = User::where('email', 'nova@microagile.io')->first();
        $zara  = User::where('email', 'zara@microagile.io')->first();
        $orion = User::where('email', 'orion@microagile.io')->first();
        $luna  = User::where('email', 'luna@microagile.io')->first();
        $axel  = User::where('email', 'axel@microagile.io')->first();
        $sage  = User::where('email', 'sage@microagile.io')->first();
        $finn  = User::where('email', 'finn@microagile.io')->first();
        $ivy   = User::where('email', 'ivy@microagile.io')->first();
        $coda  = User::where('email', 'coda@microagile.io')->first();

        $p1  = Project::where('title', 'E-Commerce Platform Redesign')->first();
        $p2  = Project::where('title', 'Analytics Dashboard API')->first();
        $p3  = Project::where('title', 'Mobile App Backend')->first();
        $p4  = Project::where('title', 'Internal HR Portal')->first();
        $p5  = Project::where('title', 'DevOps Pipeline Automation')->first();
        $p6  = Project::where('title', 'Customer Support Ticketing System')->first();
        $p7  = Project::where('title', 'SEO & Performance Audit Tool')->first();
        $p8  = Project::where('title', 'Inventory Management System')->first();
        $p9  = Project::where('title', 'Real-Time Notifications Service')->first();
        $p10 = Project::where('title', 'Multi-Tenant SaaS Billing')->first();

        $tasks = [

            // ── P1: E-Commerce ────────────────────────────────────
            [
                'title'       => 'Audit existing UI components',
                'description' => 'Review all Blade components and flag which need redesigning for the new system.',
                'status'      => 'done',
                'priority'    => 'high',
                'deadline'    => '2026-05-04',
                'project_id'  => $p1->id,
                'user_id'     => $nova->id,
            ],
            [
                'title'       => 'Integrate Stripe checkout',
                'description' => 'Set up Stripe SDK, handle payment webhooks, and write feature tests for the full checkout flow.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'deadline'    => '2026-05-06', // overdue
                'project_id'  => $p1->id,
                'user_id'     => $zara->id,
            ],
            [
                'title'       => 'Mobile responsive storefront',
                'description' => 'Apply Tailwind responsive prefixes across all pages. Test on iOS Safari and Android Chrome.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'deadline'    => '2026-05-15',
                'project_id'  => $p1->id,
                'user_id'     => $nova->id,
            ],

            // ── P2: Analytics Dashboard ───────────────────────────
            [
                'title'       => 'Design metrics database schema',
                'description' => 'Create migrations for events, sessions, and conversion tables with proper indexes.',
                'status'      => 'done',
                'priority'    => 'high',
                'deadline'    => '2026-05-03',
                'project_id'  => $p2->id,
                'user_id'     => $zara->id,
            ],
            [
                'title'       => 'Build sales aggregation endpoint',
                'description' => 'Implement GET /api/analytics/sales with filters for date range, category, and region.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'deadline'    => '2026-05-07', // urgent
                'project_id'  => $p2->id,
                'user_id'     => $orion->id,
            ],
            [
                'title'       => 'Write OpenAPI documentation',
                'description' => 'Document all endpoints using OpenAPI 3.0 spec with request examples and error codes.',
                'status'      => 'todo',
                'priority'    => 'low',
                'deadline'    => '2026-05-28',
                'project_id'  => $p2->id,
                'user_id'     => $zara->id,
            ],

            // ── P3: Mobile App Backend ────────────────────────────
            [
                'title'       => 'Set up Pusher real-time chat',
                'description' => 'Configure Laravel Echo with Pusher, implement chat channels, and handle presence events.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'deadline'    => '2026-05-07', // urgent
                'project_id'  => $p3->id,
                'user_id'     => $luna->id,
            ],
            [
                'title'       => 'Push notification service',
                'description' => 'Integrate Firebase Cloud Messaging for iOS and Android push notifications via Laravel queues.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'deadline'    => '2026-05-18',
                'project_id'  => $p3->id,
                'user_id'     => $axel->id,
            ],

            // ── P4: HR Portal ─────────────────────────────────────
            [
                'title'       => 'Leave request workflow',
                'description' => 'Build the leave application form, manager approval flow, and email notifications on status change.',
                'status'      => 'todo',
                'priority'    => 'high',
                'deadline'    => '2026-05-20',
                'project_id'  => $p4->id,
                'user_id'     => $sage->id,
            ],
            [
                'title'       => 'SSO integration with Active Directory',
                'description' => 'Configure LDAP authentication and map AD groups to application roles.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'deadline'    => '2026-05-10',
                'project_id'  => $p4->id,
                'user_id'     => $finn->id,
            ],

            // ── P5: DevOps Pipeline ───────────────────────────────
            [
                'title'       => 'GitHub Actions CI workflow',
                'description' => 'Set up automated test runs on every PR using GitHub Actions with Laravel test suite and code coverage.',
                'status'      => 'done',
                'priority'    => 'high',
                'deadline'    => '2026-05-04',
                'project_id'  => $p5->id,
                'user_id'     => $ivy->id,
            ],
            [
                'title'       => 'Docker image build and push',
                'description' => 'Write production Dockerfile, build image in CI, and push to AWS ECR on merge to main.',
                'status'      => 'in_progress',
                'priority'    => 'medium',
                'deadline'    => '2026-05-08',
                'project_id'  => $p5->id,
                'user_id'     => $coda->id,
            ],

            // ── P7: SEO Audit Tool ────────────────────────────────
            [
                'title'       => 'Core Web Vitals crawler',
                'description' => 'Build a headless Chrome crawler using Browsershot to collect LCP, FID, and CLS scores per page.',
                'status'      => 'todo',
                'priority'    => 'medium',
                'deadline'    => '2026-05-22',
                'project_id'  => $p7->id,
                'user_id'     => $orion->id,
            ],

            // ── P9: Notifications Service ─────────────────────────
            [
                'title'       => 'SMS fallback via Twilio',
                'description' => 'Implement Twilio SMS channel as fallback when in-app and email notifications go unread after 30 minutes.',
                'status'      => 'todo',
                'priority'    => 'low',
                'deadline'    => '2026-05-20',
                'project_id'  => $p9->id,
                'user_id'     => $finn->id,
            ],

            // ── P10: SaaS Billing ─────────────────────────────────
            [
                'title'       => 'Dunning system for failed payments',
                'description' => 'Implement retry logic and email sequences for failed Stripe charges using Laravel queued jobs.',
                'status'      => 'todo',
                'priority'    => 'high',
                'deadline'    => '2026-05-25',
                'project_id'  => $p10->id,
                'user_id'     => $coda->id,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}