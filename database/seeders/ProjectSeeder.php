<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $atlas = User::where('email', 'atlas@microagile.io')->first();
        $nova  = User::where('email', 'nova@microagile.io')->first();
        $zara  = User::where('email', 'zara@microagile.io')->first();
        $orion = User::where('email', 'orion@microagile.io')->first();
        $luna  = User::where('email', 'luna@microagile.io')->first();
        $axel  = User::where('email', 'axel@microagile.io')->first();
        $sage  = User::where('email', 'sage@microagile.io')->first();
        $finn  = User::where('email', 'finn@microagile.io')->first();
        $ivy   = User::where('email', 'ivy@microagile.io')->first();
        $coda  = User::where('email', 'coda@microagile.io')->first();

        // ── Project 1 — Atlas leads ───────────────────────────────
        $p1 = Project::create([
            'title'       => 'E-Commerce Platform Redesign',
            'description' => 'Full redesign of the storefront UI, checkout flow, and Stripe payment gateway integration. Includes mobile responsiveness and performance audit.',
            'deadline'    => '2026-05-20',
        ]);
        $p1->members()->attach($atlas->id, ['role' => 'lead']);
        $p1->members()->attach($nova->id,  ['role' => 'developer']);
        $p1->members()->attach($zara->id,  ['role' => 'developer']);

        // ── Project 2 — Nova leads ────────────────────────────────
        $p2 = Project::create([
            'title'       => 'Analytics Dashboard API',
            'description' => 'REST API aggregating sales data, user activity, and conversion metrics. Endpoints consumed by the React frontend team.',
            'deadline'    => '2026-05-30',
        ]);
        $p2->members()->attach($nova->id,  ['role' => 'lead']);
        $p2->members()->attach($zara->id,  ['role' => 'developer']);
        $p2->members()->attach($orion->id, ['role' => 'developer']);

        // ── Project 3 — Orion leads ───────────────────────────────
        $p3 = Project::create([
            'title'       => 'Mobile App Backend',
            'description' => 'Laravel API backend powering the iOS and Android apps. Handles auth, push notifications, and real-time chat via Pusher.',
            'deadline'    => '2026-06-10',
        ]);
        $p3->members()->attach($orion->id, ['role' => 'lead']);
        $p3->members()->attach($luna->id,  ['role' => 'developer']);
        $p3->members()->attach($axel->id,  ['role' => 'developer']);

        // ── Project 4 — Luna leads ────────────────────────────────
        $p4 = Project::create([
            'title'       => 'Internal HR Portal',
            'description' => 'Employee management system with leave requests, payroll summaries, and org chart. Secured behind SSO with Active Directory.',
            'deadline'    => '2026-06-01',
        ]);
        $p4->members()->attach($luna->id, ['role' => 'lead']);
        $p4->members()->attach($sage->id, ['role' => 'developer']);
        $p4->members()->attach($finn->id, ['role' => 'developer']);

        // ── Project 5 — Axel leads ────────────────────────────────
        $p5 = Project::create([
            'title'       => 'DevOps Pipeline Automation',
            'description' => 'CI/CD pipeline setup with GitHub Actions, Docker image builds, automated testing, and deployment to AWS ECS.',
            'deadline'    => '2026-05-25',
        ]);
        $p5->members()->attach($axel->id, ['role' => 'lead']);
        $p5->members()->attach($ivy->id,  ['role' => 'developer']);
        $p5->members()->attach($coda->id, ['role' => 'developer']);

        // ── Project 6 — Sage leads ────────────────────────────────
        $p6 = Project::create([
            'title'       => 'Customer Support Ticketing System',
            'description' => 'Internal helpdesk tool with ticket creation, priority queues, SLA tracking, and agent assignment. Integrates with Slack for notifications.',
            'deadline'    => '2026-06-15',
        ]);
        $p6->members()->attach($sage->id,  ['role' => 'lead']);
        $p6->members()->attach($atlas->id, ['role' => 'developer']);
        $p6->members()->attach($nova->id,  ['role' => 'developer']);

        // ── Project 7 — Finn leads ────────────────────────────────
        $p7 = Project::create([
            'title'       => 'SEO & Performance Audit Tool',
            'description' => 'Crawls client websites and generates reports on Core Web Vitals, broken links, meta tags, and page speed scores.',
            'deadline'    => '2026-05-28',
        ]);
        $p7->members()->attach($finn->id,  ['role' => 'lead']);
        $p7->members()->attach($orion->id, ['role' => 'developer']);
        $p7->members()->attach($luna->id,  ['role' => 'developer']);

        // ── Project 8 — Ivy leads ─────────────────────────────────
        $p8 = Project::create([
            'title'       => 'Inventory Management System',
            'description' => 'Warehouse stock tracking with barcode scanning, low-stock alerts, supplier management, and purchase order generation.',
            'deadline'    => '2026-06-20',
        ]);
        $p8->members()->attach($ivy->id,  ['role' => 'lead']);
        $p8->members()->attach($axel->id, ['role' => 'developer']);
        $p8->members()->attach($sage->id, ['role' => 'developer']);

        // ── Project 9 — Coda leads ────────────────────────────────
        $p9 = Project::create([
            'title'       => 'Real-Time Notifications Service',
            'description' => 'WebSocket-based notification service using Laravel Echo and Pusher. Supports in-app alerts, email digests, and SMS fallback via Twilio.',
            'deadline'    => '2026-05-22',
        ]);
        $p9->members()->attach($coda->id, ['role' => 'lead']);
        $p9->members()->attach($finn->id, ['role' => 'developer']);
        $p9->members()->attach($ivy->id,  ['role' => 'developer']);

        // ── Project 10 — Atlas leads ──────────────────────────────
        $p10 = Project::create([
            'title'       => 'Multi-Tenant SaaS Billing',
            'description' => 'Subscription billing system with Stripe, plan management, usage-based pricing, invoice generation, and dunning for failed payments.',
            'deadline'    => '2026-06-05',
        ]);
        $p10->members()->attach($atlas->id, ['role' => 'lead']);
        $p10->members()->attach($coda->id,  ['role' => 'developer']);
        $p10->members()->attach($zara->id,  ['role' => 'developer']);
    }
}