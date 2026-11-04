<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\BlogPost;
use App\Models\Branch;
use App\Models\Client;
use App\Models\ClinicalNote;
use App\Models\ClinicalNoteAccessLog;
use App\Models\ClinicalNoteVersion;
use App\Models\ConsentRecord;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Faq;
use App\Models\FormTemplate;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LeaveRequest;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Practitioner;
use App\Models\PractitionerAvailability;
use App\Models\Review;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SupportTicket;
use App\Models\Tenant;
use App\Models\TherapySession;
use App\Models\TreatmentGoal;
use App\Models\TreatmentPlan;
use App\Models\User;
use App\Services\ModuleService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $moduleService = app(ModuleService::class);

        // 1. Sync all 26 SaaS Business Modules
        $moduleService->syncRegistry();

        // 2. Subscription Plans
        $plansData = [
            [
                'name' => 'Free / Starter Trial',
                'slug' => 'free',
                'description' => 'For solo practitioners starting out.',
                'price_monthly' => 0.00,
                'price_annual' => 0.00,
                'max_practitioners' => 1,
                'max_branches' => 1,
                'max_clients' => 25,
                'is_active' => true,
                'sort_order' => 1,
                'features' => ['Online Booking', 'Basic Calendar', 'Client Portal', 'Email Notifications'],
            ],
            [
                'name' => 'Solo Practitioner Pro',
                'slug' => 'starter',
                'description' => 'For independent therapists needing clinical notes and telehealth.',
                'price_monthly' => 49.00,
                'price_annual' => 470.00,
                'max_practitioners' => 2,
                'max_branches' => 1,
                'max_clients' => 150,
                'is_active' => true,
                'sort_order' => 2,
                'features' => ['All Starter Features', 'SOAP / DAP Clinical Notes', 'WebRTC Telehealth Rooms', 'Invoicing & PDF Receipts', 'Informed Consents'],
            ],
            [
                'name' => 'Group Practice / Clinic',
                'slug' => 'professional',
                'description' => 'The complete solution for multi-clinician centers.',
                'price_monthly' => 99.00,
                'price_annual' => 950.00,
                'max_practitioners' => 10,
                'max_branches' => 3,
                'max_clients' => 800,
                'is_active' => true,
                'sort_order' => 3,
                'features' => ['All Pro Features', 'Biometric HR & Attendance', 'Multi-Branch Management', 'Advanced Analytics & Reports', 'Secure Messaging Thread', 'Custom Intake Forms'],
            ],
            [
                'name' => 'Behavioral Health Network',
                'slug' => 'business',
                'description' => 'For established multi-location psychiatric & psychological clinics.',
                'price_monthly' => 199.00,
                'price_annual' => 1900.00,
                'max_practitioners' => 25,
                'max_branches' => 10,
                'max_clients' => 2500,
                'is_active' => true,
                'sort_order' => 4,
                'features' => ['All Clinic Features', 'Supervisor Approval Workflows', 'Custom Subdomains', 'API Access', 'Dedicated Account Manager'],
            ],
            [
                'name' => 'Enterprise Healthcare',
                'slug' => 'enterprise',
                'description' => 'Hospital networks, university counseling centers, and telehealth chains.',
                'price_monthly' => 399.00,
                'price_annual' => 3800.00,
                'max_practitioners' => 100,
                'max_branches' => 50,
                'max_clients' => 20000,
                'is_active' => true,
                'sort_order' => 5,
                'features' => ['Unlimited Clinicians & Clients', 'HIPAA BAA Agreement', 'Custom Domain SSL', 'Biometric ADMS Bridge', '24/7 SLA Hotline'],
            ],
        ];

        $seededPlans = [];
        foreach ($plansData as $pData) {
            $seededPlans[$pData['slug']] = SubscriptionPlan::create($pData);
        }

        // 3. Demo Tenant: Serenity Mental Wellness Center
        $tenant = Tenant::create([
            'name' => 'Serenity Mental Wellness Center',
            'slug' => 'serenity-wellness',
            'subdomain' => 'serenity',
            'plan_id' => $seededPlans['professional']->id,
            'status' => 'active',
            'timezone' => 'America/Los_Angeles',
            'currency' => 'USD',
            'locale' => 'en',
            'crisis_hotline' => '988',
            'crisis_info' => 'If you are experiencing suicidal thoughts or emotional crisis, please dial 988 immediately.',
            'settings' => [
                'cancellation_lead_hours' => 24,
                'buffer_minutes' => 15,
                'telehealth_provider' => 'jitsi',
                'require_intake_before_booking' => true,
            ],
        ]);

        // Subscription record
        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $seededPlans['professional']->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => Carbon::now()->subMonths(2),
            'amount' => 99.00,
        ]);

        // Initialize all 26 modules for Serenity Tenant
        $moduleService->initializeTenantModules($tenant);

        // 4. Roles & Permissions
        $roleNames = [
            'Super Administrator',
            'Platform Support Agent',
            'Platform Finance Manager',
            'Organization Owner',
            'Organization Administrator',
            'Clinic Manager',
            'Clinical Director',
            'Therapist / Counselor',
            'Receptionist',
            'Billing Manager',
            'Compliance Auditor',
            'Client',
            'Parent / Guardian',
        ];

        $roles = [];
        foreach ($roleNames as $rName) {
            $roles[$rName] = Role::firstOrCreate(
                ['slug' => Str::slug($rName)],
                [
                    'name' => $rName,
                    'scope' => in_array($rName, ['Super Administrator', 'Platform Support Agent', 'Platform Finance Manager']) ? 'platform' : 'tenant',
                    'description' => "Role for {$rName}",
                    'is_system' => true,
                ]
            );
        }

        // 5. Branches
        $branch1 = Branch::create([
            'tenant_id' => $tenant->id,
            'name' => 'Main Counseling Center',
            'code' => 'BR-01',
            'phone' => '+1 (555) 349-2849',
            'email' => 'main@serenitywellness.test',
            'address' => '1204 Harmony Way, Suite 400',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94103',
            'country' => 'USA',
            'is_active' => true,
        ]);

        $branch2 = Branch::create([
            'tenant_id' => $tenant->id,
            'name' => 'Uptown Psychological Clinic',
            'code' => 'BR-02',
            'phone' => '+1 (555) 892-1200',
            'email' => 'uptown@serenitywellness.test',
            'address' => '850 Wellness Boulevard, Floor 2',
            'city' => 'Berkeley',
            'state' => 'CA',
            'postal_code' => '94704',
            'country' => 'USA',
            'is_active' => true,
        ]);

        // 6. Mandatory 10 Synthetic Demo Accounts (Password: Demo@12345)
        $demoPassword = Hash::make('Demo@12345');

        // 1. Super Administrator
        $superAdmin = User::create([
            'tenant_id' => null,
            'role' => 'super_admin',
            'name' => 'Imran Ahmed (Super Admin)',
            'email' => 'superadmin@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0001',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $superAdmin->roles()->attach($roles['Super Administrator']->id);

        // 2. Organization Owner
        $owner = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'organization_owner',
            'name' => 'Dr. Eleanor Vance (Owner)',
            'email' => 'owner@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0002',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $owner->roles()->attach($roles['Organization Owner']->id);

        // 3. Organization Administrator
        $admin = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'organization_admin',
            'name' => 'Robert Hastings (Clinic Admin)',
            'email' => 'admin@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0003',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->roles()->attach($roles['Organization Administrator']->id);

        // 4. Clinic Manager
        $manager = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'clinic_manager',
            'name' => 'Melissa Torres (Operations Manager)',
            'email' => 'manager@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0004',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $manager->roles()->attach($roles['Clinic Manager']->id);

        // 5. Therapist / Counselor
        $therapistUser = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'practitioner',
            'name' => 'Dr. Sarah Mitchell, PsyD',
            'email' => 'practitioner@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0005',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $therapistUser->roles()->attach($roles['Therapist / Counselor']->id);

        // 6. Receptionist
        $receptionist = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'receptionist',
            'name' => 'Jessica Alba-Miller (Front Desk)',
            'email' => 'reception@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0006',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $receptionist->roles()->attach($roles['Receptionist']->id);

        // 7. Billing Manager
        $billing = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'billing_manager',
            'name' => 'David Sterling (Billing Dept)',
            'email' => 'billing@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0007',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $billing->roles()->attach($roles['Billing Manager']->id);

        // 8. Client / Patient
        $clientUser = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'client',
            'name' => 'Alexander Cole (Patient)',
            'email' => 'client@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0008',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $clientUser->roles()->attach($roles['Client']->id);

        // 9. Parent / Guardian
        $guardianUser = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'parent_guardian',
            'name' => 'Patricia Cole (Guardian)',
            'email' => 'guardian@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0009',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $guardianUser->roles()->attach($roles['Parent / Guardian']->id);

        // 10. Compliance Auditor
        $auditor = User::create([
            'tenant_id' => $tenant->id,
            'role' => 'compliance_auditor',
            'name' => 'Marcus Chen, CISSP (Auditor)',
            'email' => 'auditor@demo.mhcb.test',
            'password' => $demoPassword,
            'phone' => '+1 (555) 000-0010',
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
        $auditor->roles()->attach($roles['Compliance Auditor']->id);

        // 7. Practitioners (8 diverse clinical specialists)
        $practitionerUsersData = [
            ['name' => 'Dr. Sarah Mitchell, PsyD', 'email' => 'sarah.mitchell@serenitywellness.test', 'title' => 'Dr.', 'specialties' => ['CBT', 'Anxiety & Panic', 'Trauma / PTSD'], 'base_fee' => 180.00, 'license' => 'PSY-CA-28491', 'user' => $therapistUser],
            ['name' => 'Dr. Marcus Vance, MD', 'email' => 'marcus.vance@serenitywellness.test', 'title' => 'Dr.', 'specialties' => ['Psychiatry', 'Medication Management', 'Bipolar'], 'base_fee' => 260.00, 'license' => 'MD-CA-99382', 'user' => null],
            ['name' => 'Elena Rostova, LMFT', 'email' => 'elena.rostova@serenitywellness.test', 'title' => 'LMFT', 'specialties' => ['Couples Counseling', 'Family Dynamics', 'Conflict Resolution'], 'base_fee' => 170.00, 'license' => 'MFT-CA-44821', 'user' => null],
            ['name' => 'David Kim, LCSW', 'email' => 'david.kim@serenitywellness.test', 'title' => 'LCSW', 'specialties' => ['Adolescent Therapy', 'Life Transitions', 'Depression'], 'base_fee' => 150.00, 'license' => 'LCSW-CA-55102', 'user' => null],
            ['name' => 'Priya Sharma, PhD', 'email' => 'priya.sharma@serenitywellness.test', 'title' => 'PhD', 'specialties' => ['Mindfulness', 'ACT', 'Chronic Stress'], 'base_fee' => 165.00, 'license' => 'PSY-CA-77124', 'user' => null],
            ['name' => 'Jordan Miller, LPC', 'email' => 'jordan.miller@serenitywellness.test', 'title' => 'LPC', 'specialties' => ['Addiction Recovery', 'Grief & Loss', 'Relapse Prevention'], 'base_fee' => 155.00, 'license' => 'LPC-CA-33910', 'user' => null],
            ['name' => 'Rachel Adams, PsyD', 'email' => 'rachel.adams@serenitywellness.test', 'title' => 'Dr.', 'specialties' => ['Child Behavioral Health', 'ADHD', 'Play Therapy'], 'base_fee' => 190.00, 'license' => 'PSY-CA-66190', 'user' => null],
            ['name' => 'Carlos Mendez, LMFT', 'email' => 'carlos.mendez@serenitywellness.test', 'title' => 'LMFT', 'specialties' => ['Bilingual Counseling (ES/EN)', 'Cultural Identity', 'EMDR'], 'base_fee' => 160.00, 'license' => 'MFT-CA-88203', 'user' => null],
        ];

        $practitioners = [];
        foreach ($practitionerUsersData as $idx => $pItem) {
            $pUser = $pItem['user'];
            if (!$pUser) {
                $pUser = User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $pItem['name'],
                    'email' => $pItem['email'],
                    'password' => $demoPassword,
                    'phone' => '+1 (555) 200-' . str_pad($idx, 4, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'email_verified_at' => Carbon::now(),
                ]);
                $pUser->roles()->attach($roles['Therapist / Counselor']->id);
            }

            $p = Practitioner::create([
                'tenant_id' => $tenant->id,
                'user_id' => $pUser->id,
                'title' => $pItem['title'],
                'specialties' => $pItem['specialties'],
                'languages' => ($idx === 7) ? ['English', 'Spanish'] : ['English'],
                'qualifications' => 'Doctorate / Master of Clinical Psychology from accredited institution.',
                'license_number' => $pItem['license'],
                'license_verified' => true,
                'license_verified_at' => Carbon::now()->subMonths(6),
                'bio' => "Experienced licensed clinician specializing in " . implode(', ', $pItem['specialties']) . ".",
                'base_fee' => $pItem['base_fee'],
                'session_duration_minutes' => 50,
                'rating' => 4.95,
                'is_featured' => ($idx < 3),
                'is_active' => true,
            ]);
            $practitioners[] = $p;

            // Associate with branches
            $p->branches()->attach([$branch1->id, $branch2->id]);

            // Recurring availability (Mon-Fri 09:00 - 17:00)
            for ($d = 1; $d <= 5; $d++) {
                PractitionerAvailability::create([
                    'tenant_id' => $tenant->id,
                    'practitioner_id' => $p->id,
                    'day_of_week' => $d,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'slot_duration_minutes' => 50,
                    'is_available' => true,
                ]);
            }
        }

        // 8. Service Categories & Services
        $cat1 = ServiceCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Individual Counseling',
            'slug' => 'individual-counseling',
            'description' => 'One-on-one psychotherapy and cognitive behavioral therapy.',
        ]);

        $cat2 = ServiceCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Couples & Family Therapy',
            'slug' => 'couples-family',
            'description' => 'Relationship, marital, and family system interventions.',
        ]);

        $cat3 = ServiceCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Psychiatric Consultations',
            'slug' => 'psychiatric-consultations',
            'description' => 'Medical psychiatric evaluations and medication monitoring.',
        ]);

        $cat4 = ServiceCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Specialized Interventions',
            'slug' => 'specialized-interventions',
            'description' => 'Trauma EMDR, grief recovery, and mindfulness practices.',
        ]);

        $servicesData = [
            [
                'category_id' => $cat1->id,
                'name' => 'Initial Clinical Intake & Assessment',
                'slug' => 'initial-clinical-intake',
                'description' => 'Comprehensive 90-minute biopsychosocial evaluation and preliminary diagnostic assessment.',
                'duration_minutes' => 90,
                'price' => 220.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#0f766e',
                'is_active' => true,
            ],
            [
                'category_id' => $cat1->id,
                'name' => 'Individual Psychotherapy Session',
                'slug' => 'individual-psychotherapy-50min',
                'description' => 'Standard 50-minute evidence-based clinical therapy session tailored to your goals.',
                'duration_minutes' => 50,
                'price' => 160.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#0d9488',
                'is_active' => true,
            ],
            [
                'category_id' => $cat1->id,
                'name' => 'Cognitive Behavioral Therapy (CBT) Session',
                'slug' => 'cbt-therapy-session',
                'description' => 'Targeted cognitive reframing, behavioral experiments, and exposure protocols.',
                'duration_minutes' => 50,
                'price' => 170.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#0284c7',
                'is_active' => true,
            ],
            [
                'category_id' => $cat2->id,
                'name' => 'Couples Counseling & Gottman Method',
                'slug' => 'couples-counseling',
                'description' => '60-minute structured relational therapy focused on de-escalation and intimate bond rebuilding.',
                'duration_minutes' => 60,
                'price' => 190.00,
                'type' => 'couples',
                'delivery_mode' => 'hybrid',
                'color' => '#8b5cf6',
                'is_active' => true,
            ],
            [
                'category_id' => $cat2->id,
                'name' => 'Family Systems Consultation',
                'slug' => 'family-systems-therapy',
                'description' => '75-minute multi-member systemic therapy session to address recurring family dynamics.',
                'duration_minutes' => 75,
                'price' => 210.00,
                'type' => 'family',
                'delivery_mode' => 'in_person',
                'color' => '#ec4899',
                'is_active' => true,
            ],
            [
                'category_id' => $cat3->id,
                'name' => 'Initial Psychiatric Evaluation',
                'slug' => 'initial-psychiatric-evaluation',
                'description' => '60-minute diagnostic session with licensed psychiatrist for medication evaluation.',
                'duration_minutes' => 60,
                'price' => 260.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#f59e0b',
                'is_active' => true,
            ],
            [
                'category_id' => $cat3->id,
                'name' => 'Psychiatric Medication Follow-up',
                'slug' => 'medication-management-followup',
                'description' => '25-minute medication efficacy, side-effect review, and prescription refill check.',
                'duration_minutes' => 25,
                'price' => 130.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#eab308',
                'is_active' => true,
            ],
            [
                'category_id' => $cat4->id,
                'name' => 'Trauma & EMDR Reprocessing',
                'slug' => 'emdr-trauma-session',
                'description' => '60-minute Eye Movement Desensitization and Reprocessing (EMDR) protocol.',
                'duration_minutes' => 60,
                'price' => 200.00,
                'type' => 'individual',
                'delivery_mode' => 'in_person',
                'color' => '#dc2626',
                'is_active' => true,
            ],
            [
                'category_id' => $cat4->id,
                'name' => 'Mindfulness & Somatic Grounding',
                'slug' => 'mindfulness-stress-reduction',
                'description' => '50-minute experiential somatic grounding and stress management session.',
                'duration_minutes' => 50,
                'price' => 150.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#10b981',
                'is_active' => true,
            ],
            [
                'category_id' => $cat4->id,
                'name' => 'Grief & Bereavement Support',
                'slug' => 'grief-counseling',
                'description' => '50-minute compassionate space to navigate profound loss and complex bereavement.',
                'duration_minutes' => 50,
                'price' => 150.00,
                'type' => 'individual',
                'delivery_mode' => 'hybrid',
                'color' => '#6366f1',
                'is_active' => true,
            ],
        ];

        $services = [];
        foreach ($servicesData as $sItem) {
            $services[] = Service::create(array_merge($sItem, ['tenant_id' => $tenant->id]));
        }

        // 9. Clients (30 synthetic clients with emergency contacts)
        $primaryClient = Client::create([
            'tenant_id' => $tenant->id,
            'user_id' => $clientUser->id,
            'guardian_user_id' => $guardianUser->id,
            'client_number' => 'CL-1001',
            'first_name' => 'Alexander',
            'last_name' => 'Cole',
            'email' => 'client@demo.mhcb.test',
            'phone' => '+1 (555) 000-0008',
            'date_of_birth' => '1992-05-14',
            'gender' => 'Male',
            'address' => '420 Montgomery St, Apt 4B',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94104',
            'status' => 'active',
            'emergency_contact_name' => 'Patricia Cole',
            'emergency_contact_phone' => '+1 (555) 000-0009',
            'emergency_contact_relation' => 'Mother / Guardian',
            'assigned_practitioner_id' => $practitioners[0]->id,
            'intake_completed' => true,
            'consent_signed' => true,
            'crm_notes' => 'Client presenting with generalized anxiety symptoms related to occupational transitions.',
        ]);

        $firstNames = ['Sophia', 'Lucas', 'Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Ethan', 'Isabella', 'Mason', 'Mia', 'Oliver', 'Charlotte', 'Aiden', 'Amelia', 'James', 'Harper', 'Benjamin', 'Evelyn', 'Henry', 'Abigail', 'Logan', 'Emily', 'Jackson', 'Ella', 'Sebastian', 'Scarlett', 'Jack', 'Grace'];
        $lastNames = ['Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin', 'Thompson', 'Garcia', 'Martinez', 'Robinson', 'Clark', 'Rodriguez', 'Lewis', 'Lee', 'Walker', 'Hall', 'Allen', 'Young', 'Hernandez', 'King', 'Wright', 'Lopez', 'Hill', 'Scott', 'Green', 'Adams', 'Baker', 'Nelson'];

        $clients = [$primaryClient];
        for ($i = 0; $i < count($firstNames); $i++) {
            $c = Client::create([
                'tenant_id' => $tenant->id,
                'user_id' => null,
                'client_number' => 'CL-' . (1002 + $i),
                'first_name' => $firstNames[$i],
                'last_name' => $lastNames[$i],
                'email' => strtolower($firstNames[$i] . '.' . $lastNames[$i]) . '@example.com',
                'phone' => '+1 (555) ' . rand(200, 899) . '-' . rand(1000, 9999),
                'date_of_birth' => Carbon::now()->subYears(rand(20, 55))->subDays(rand(10, 300))->toDateString(),
                'gender' => ($i % 2 === 0) ? 'Female' : 'Male',
                'address' => rand(100, 999) . ' Pine Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '9410' . rand(2, 9),
                'status' => 'active',
                'emergency_contact_name' => 'Emergency Contact for ' . $firstNames[$i],
                'emergency_contact_phone' => '+1 (555) 999-000' . rand(0, 9),
                'emergency_contact_relation' => ($i % 2 === 0) ? 'Spouse' : 'Parent',
                'assigned_practitioner_id' => $practitioners[$i % count($practitioners)]->id,
                'intake_completed' => true,
                'consent_signed' => true,
            ]);
            $clients[] = $c;
        }

        // 10. Appointments (50 realistic appointments)
        $appointments = [];
        $today = Carbon::today();

        // 25 Past appointments (completed)
        for ($k = 1; $k <= 25; $k++) {
            $client = $clients[$k % count($clients)];
            $practitioner = $practitioners[$k % count($practitioners)];
            $service = $services[$k % count($services)];
            $aptDate = $today->copy()->subDays(28 - $k)->setTime(10, 0);
            if ($aptDate->isWeekend()) {
                $aptDate->subDays(2);
            }

            $apt = Appointment::create([
                'tenant_id' => $tenant->id,
                'appointment_number' => 'APT-' . str_pad($k, 5, '0', STR_PAD_LEFT),
                'branch_id' => ($k % 2 === 0) ? $branch1->id : $branch2->id,
                'practitioner_id' => $practitioner->id,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'scheduled_at' => $aptDate,
                'duration_minutes' => 50,
                'status' => 'completed',
                'delivery_mode' => ($k % 2 === 0) ? 'in_person' : 'online_telehealth',
                'telehealth_meeting_id' => ($k % 2 !== 0) ? 'serenity-' . Str::random(10) : null,
                'telehealth_meeting_url' => ($k % 2 !== 0) ? 'https://meet.jit.si/serenity-' . Str::random(10) : null,
                'fee' => $service->price,
                'payment_status' => 'paid',
            ]);
            $appointments[] = $apt;

            // Generate TherapySession and Signed SOAP / DAP Clinical Note
            $session = TherapySession::create([
                'tenant_id' => $tenant->id,
                'appointment_id' => $apt->id,
                'client_id' => $client->id,
                'practitioner_id' => $practitioner->id,
                'session_number' => 'SES-' . str_pad($k, 5, '0', STR_PAD_LEFT),
                'started_at' => $aptDate,
                'ended_at' => $aptDate->copy()->addMinutes(50),
                'duration_minutes' => 50,
                'session_type' => 'individual',
                'status' => 'completed',
                'documentation_status' => 'signed',
                'billing_status' => 'paid',
            ]);

            $contentData = [
                'subjective' => 'Client reports feeling moderate anxiety regarding workplace performance over the preceding fortnight. Sleep hygiene is maintained at ~6.5 hours per night. Denies suicidal or self-harm ideation.',
                'objective' => 'Affect is congruent, mildly anxious. Speech is fluent with normal rate, tone, and volume. Cognitive processes are logical, goal-directed, and oriented x4.',
                'assessment' => 'Client is making steady measurable progress with CBT cognitive restructuring homework. Demonstrates increased awareness of catastrophic thinking patterns.',
                'plan' => 'Continue bi-weekly individual psychotherapy sessions. Assigned thought record journal exercise (3 entries weekly). Next session scheduled for following week.',
                'diagnosis_codes' => ['F41.1 (Generalized Anxiety Disorder)'],
            ];

            $clinicalNote = ClinicalNote::create([
                'tenant_id' => $tenant->id,
                'therapy_session_id' => $session->id,
                'client_id' => $client->id,
                'practitioner_id' => $practitioner->id,
                'format' => ($k % 2 === 0) ? 'SOAP' : 'DAP',
                'summary' => 'Bi-weekly cognitive behavioral therapy session addressing work-related stress and cognitive reframing.',
                'content' => $contentData,
                'is_signed' => true,
                'signed_at' => $aptDate->copy()->addHours(1),
                'signed_by_id' => $practitioner->user_id,
                'signature_hash' => hash('sha256', "ClinicalNote-{$apt->id}-{$practitioner->id}"),
                'is_locked' => true,
                'version' => 1,
            ]);

            // Version snapshot
            ClinicalNoteVersion::create([
                'clinical_note_id' => $clinicalNote->id,
                'version_number' => 1,
                'content' => $contentData,
                'amendment_reason' => 'Initial signed clinical record entry.',
                'amended_by_id' => $practitioner->user_id,
            ]);

            // Access log
            ClinicalNoteAccessLog::create([
                'tenant_id' => $tenant->id,
                'clinical_note_id' => $clinicalNote->id,
                'user_id' => $practitioner->user_id,
                'action' => 'sign',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Healthcare EMR Client)',
            ]);

            // Paid Invoice
            $inv = Invoice::create([
                'tenant_id' => $tenant->id,
                'client_id' => $client->id,
                'appointment_id' => $apt->id,
                'invoice_number' => 'INV-2026-' . str_pad($k, 5, '0', STR_PAD_LEFT),
                'issue_date' => $aptDate->toDateString(),
                'due_date' => $aptDate->toDateString(),
                'subtotal' => $service->price,
                'tax_rate' => 0.00,
                'tax_amount' => 0.00,
                'discount_amount' => 0.00,
                'total_amount' => $service->price,
                'paid_amount' => $service->price,
                'status' => 'paid',
                'payment_method' => 'stripe',
            ]);

            InvoiceItem::create([
                'invoice_id' => $inv->id,
                'description' => $service->name . ' - ' . $aptDate->format('M d, Y'),
                'quantity' => 1,
                'unit_price' => $service->price,
                'total_price' => $service->price,
            ]);

            Payment::create([
                'tenant_id' => $tenant->id,
                'invoice_id' => $inv->id,
                'client_id' => $client->id,
                'transaction_id' => 'TXN-' . Str::upper(Str::random(10)),
                'amount' => $service->price,
                'payment_method' => 'stripe',
                'status' => 'success',
                'paid_at' => $aptDate->copy()->addMinutes(55),
            ]);
        }

        // Today's appointments (4 active)
        $todayTimes = ['09:00:00', '11:00:00', '14:00:00', '16:00:00'];
        foreach ($todayTimes as $idx => $timeStr) {
            $aptDate = Carbon::parse($today->toDateString() . ' ' . $timeStr);
            $client = ($idx === 0) ? $primaryClient : $clients[$idx + 5];

            $apt = Appointment::create([
                'tenant_id' => $tenant->id,
                'appointment_number' => 'APT-' . str_pad(26 + $idx, 5, '0', STR_PAD_LEFT),
                'branch_id' => $branch1->id,
                'practitioner_id' => $practitioners[$idx]->id,
                'client_id' => $client->id,
                'service_id' => $services[$idx]->id,
                'scheduled_at' => $aptDate,
                'duration_minutes' => 50,
                'status' => 'confirmed',
                'delivery_mode' => ($idx % 2 === 0) ? 'online_telehealth' : 'in_person',
                'telehealth_meeting_id' => 'serenity-room-' . Str::random(8),
                'telehealth_meeting_url' => 'https://meet.jit.si/serenity-room-' . Str::random(8),
                'fee' => $services[$idx]->price,
                'payment_status' => ($idx === 0) ? 'unpaid' : 'paid',
            ]);
            $appointments[] = $apt;

            $inv = Invoice::create([
                'tenant_id' => $tenant->id,
                'client_id' => $apt->client_id,
                'appointment_id' => $apt->id,
                'invoice_number' => 'INV-2026-' . str_pad(26 + $idx, 5, '0', STR_PAD_LEFT),
                'issue_date' => $today->toDateString(),
                'due_date' => $today->copy()->addDays(7)->toDateString(),
                'subtotal' => $apt->fee,
                'tax_rate' => 0.00,
                'tax_amount' => 0.00,
                'discount_amount' => 0.00,
                'total_amount' => $apt->fee,
                'paid_amount' => ($idx === 0) ? 0.00 : $apt->fee,
                'status' => ($idx === 0) ? 'sent' : 'paid',
                'payment_method' => 'stripe',
            ]);

            InvoiceItem::create([
                'invoice_id' => $inv->id,
                'description' => $services[$idx]->name . ' (Today)',
                'quantity' => 1,
                'unit_price' => $apt->fee,
                'total_price' => $apt->fee,
            ]);
        }

        // 20 Future upcoming appointments
        for ($f = 1; $f <= 20; $f++) {
            $client = ($f === 1) ? $primaryClient : $clients[$f % count($clients)];
            $practitioner = $practitioners[$f % count($practitioners)];
            $service = $services[$f % count($services)];
            $aptDate = $today->copy()->addDays(ceil($f / 2))->setTime(($f % 2 === 0) ? 13 : 15, 0);
            if ($aptDate->isWeekend()) {
                $aptDate->addDays(2);
            }

            $apt = Appointment::create([
                'tenant_id' => $tenant->id,
                'appointment_number' => 'APT-' . str_pad(30 + $f, 5, '0', STR_PAD_LEFT),
                'branch_id' => ($f % 2 === 0) ? $branch2->id : $branch1->id,
                'practitioner_id' => $practitioner->id,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'scheduled_at' => $aptDate,
                'duration_minutes' => 50,
                'status' => 'confirmed',
                'delivery_mode' => ($f % 2 === 0) ? 'online_telehealth' : 'in_person',
                'telehealth_meeting_id' => ($f % 2 === 0) ? 'serenity-room-' . Str::random(8) : null,
                'telehealth_meeting_url' => ($f % 2 === 0) ? 'https://meet.jit.si/serenity-room-' . Str::random(8) : null,
                'fee' => $service->price,
                'payment_status' => 'unpaid',
            ]);
            $appointments[] = $apt;
        }

        // 11. Treatment Plans & Goals
        $tPlan = TreatmentPlan::create([
            'tenant_id' => $tenant->id,
            'client_id' => $primaryClient->id,
            'practitioner_id' => $practitioners[0]->id,
            'title' => 'Cognitive Behavioral Anxiety Reduction & Sleep Restoration',
            'diagnosis_notes' => 'F41.1 Generalized Anxiety Disorder, G47.00 Insomnia',
            'start_date' => Carbon::now()->subMonths(1)->toDateString(),
            'target_completion_date' => Carbon::now()->addMonths(3)->toDateString(),
            'review_date' => Carbon::now()->addMonths(1)->toDateString(),
            'status' => 'active',
            'overall_progress_percentage' => 70,
        ]);

        TreatmentGoal::create([
            'treatment_plan_id' => $tPlan->id,
            'description' => 'Decrease subjective GAD-7 anxiety severity score from 14 (Moderate) to below 6 (Mild) over an 8-week timeframe.',
            'goal_type' => 'practitioner_assigned',
            'target_date' => Carbon::now()->addMonths(1)->toDateString(),
            'status' => 'in_progress',
            'progress_percentage' => 65,
            'clinical_notes' => 'Client actively engaging with cognitive thought journal.',
        ]);

        TreatmentGoal::create([
            'treatment_plan_id' => $tPlan->id,
            'description' => 'Establish consistent sleep latency of under 25 minutes using stimulus control protocols 5 nights per week.',
            'goal_type' => 'practitioner_assigned',
            'target_date' => Carbon::now()->addWeeks(3)->toDateString(),
            'status' => 'in_progress',
            'progress_percentage' => 80,
            'clinical_notes' => 'Reported significant improvement in falling asleep without rumination.',
        ]);

        // 12. Informed Consents
        ConsentRecord::create([
            'tenant_id' => $tenant->id,
            'client_id' => $primaryClient->id,
            'consent_type' => 'treatment_consent',
            'version' => '1.0',
            'document_title' => 'Informed Consent for Outpatient Psychotherapy',
            'document_text' => 'Client agrees to engage in psychological psychotherapy under licensed clinical supervision, acknowledging confidentiality parameters.',
            'signer_name' => 'Alexander Cole',
            'signature_data' => 'SIG-SHA256-ACCEPTED',
            'ip_address' => '127.0.0.1',
            'status' => 'active',
            'agreed_at' => Carbon::now()->subMonths(1),
        ]);

        ConsentRecord::create([
            'tenant_id' => $tenant->id,
            'client_id' => $primaryClient->id,
            'consent_type' => 'telehealth_consent',
            'version' => '1.0',
            'document_title' => 'Telehealth Technology & Video Encounter Agreement',
            'document_text' => 'Client verified physical California presence and consented to encrypted browser-based WebRTC telecommunication.',
            'signer_name' => 'Alexander Cole',
            'signature_data' => 'SIG-SHA256-ACCEPTED',
            'ip_address' => '127.0.0.1',
            'status' => 'active',
            'agreed_at' => Carbon::now()->subMonths(1),
        ]);

        // 13. Form Templates
        FormTemplate::create([
            'tenant_id' => $tenant->id,
            'name' => 'Adult Biopsychosocial Clinical Intake Screener',
            'slug' => 'adult-biopsychosocial-intake',
            'type' => 'intake',
            'description' => 'General health, mental health history, and chief complaint screener.',
            'fields' => [
                ['name' => 'chief_complaint', 'type' => 'textarea', 'label' => 'Primary Reason for Visit', 'required' => true],
                ['name' => 'duration', 'type' => 'text', 'label' => 'Symptom Duration', 'required' => true],
                ['name' => 'previous_therapy', 'type' => 'select', 'label' => 'Prior Psychological Treatment?', 'options' => ['Yes', 'No']],
            ],
            'version' => 1,
            'is_active' => true,
            'language' => 'en',
        ]);

        FormTemplate::create([
            'tenant_id' => $tenant->id,
            'name' => 'GAD-7 Anxiety Screener',
            'slug' => 'gad7-screener',
            'type' => 'evaluation',
            'description' => 'Standardized 7-item Generalized Anxiety Disorder assessment.',
            'fields' => [
                ['name' => 'gad_1', 'type' => 'radio', 'label' => 'Feeling nervous, anxious, or on edge', 'options' => ['0', '1', '2', '3']],
                ['name' => 'gad_2', 'type' => 'radio', 'label' => 'Trouble relaxing', 'options' => ['0', '1', '2', '3']],
            ],
            'version' => 1,
            'is_active' => true,
            'language' => 'en',
        ]);

        // 14. Documents for Client
        Document::create([
            'tenant_id' => $tenant->id,
            'client_id' => $primaryClient->id,
            'uploaded_by_id' => $therapistUser->id,
            'title' => 'Initial Clinical Intake & Biopsychosocial Summary',
            'file_path' => '/documents/intake_summary_alex_cole.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 1048576,
            'category' => 'clinical',
            'access_level' => 'client_accessible',
        ]);

        Document::create([
            'tenant_id' => $tenant->id,
            'client_id' => $primaryClient->id,
            'uploaded_by_id' => $therapistUser->id,
            'title' => 'Sleep Hygiene & Stimulus Control Protocol Handout',
            'file_path' => '/documents/sleep_hygiene_guide.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 524288,
            'category' => 'general',
            'access_level' => 'client_accessible',
        ]);

        // 15. HR Employees & Biometric Attendance
        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $therapistUser->id,
            'employee_id' => 'EMP-101',
            'department' => 'Psychology & Psychotherapy',
            'designation' => 'Clinical Psychologist',
            'joining_date' => '2023-01-15',
            'salary' => 95000.00,
            'is_active' => true,
        ]);

        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $receptionist->id,
            'employee_id' => 'EMP-102',
            'department' => 'Operations',
            'designation' => 'Front Desk Receptionist',
            'joining_date' => '2023-04-01',
            'salary' => 48000.00,
            'is_active' => true,
        ]);

        // Attendance records
        for ($a = 0; $a < 5; $a++) {
            $attDate = Carbon::today()->subDays($a);
            if (!$attDate->isWeekend()) {
                AttendanceRecord::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $therapistUser->id,
                    'branch_id' => $branch1->id,
                    'date' => $attDate->toDateString(),
                    'check_in' => '08:52:00',
                    'check_out' => '17:15:00',
                    'status' => 'present',
                    'method' => 'biometric',
                ]);

                AttendanceRecord::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $receptionist->id,
                    'branch_id' => $branch1->id,
                    'date' => $attDate->toDateString(),
                    'check_in' => '07:58:00',
                    'check_out' => '16:30:00',
                    'status' => 'present',
                    'method' => 'adms',
                ]);
            }
        }

        // Leave Request
        LeaveRequest::create([
            'tenant_id' => $tenant->id,
            'user_id' => $therapistUser->id,
            'leave_type' => 'annual',
            'start_date' => Carbon::now()->addDays(20)->toDateString(),
            'end_date' => Carbon::now()->addDays(22)->toDateString(),
            'reason' => 'Annual Psychological Association Continuing Medical Education symposium.',
            'status' => 'approved',
        ]);

        // 16. Secure Messaging Thread
        Message::create([
            'tenant_id' => $tenant->id,
            'sender_id' => $therapistUser->id,
            'recipient_id' => $clientUser->id,
            'subject' => 'Welcome to Serenity Care & Telehealth Setup',
            'body' => 'Hello Alexander, welcome to the practice. Before our next session on Tuesday, please ensure you review the Sleep Hygiene document uploaded to your portal documents tab.',
            'is_clinical' => false,
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(4),
        ]);

        Message::create([
            'tenant_id' => $tenant->id,
            'sender_id' => $clientUser->id,
            'recipient_id' => $therapistUser->id,
            'subject' => 'Re: Welcome to Serenity Care & Telehealth Setup',
            'body' => 'Thank you Dr. Mitchell! I read through the guide and have started tracking my bedtime thoughts using the 3-column thought record worksheet.',
            'is_clinical' => false,
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // 17. Reviews & Support Tickets
        Review::create([
            'tenant_id' => $tenant->id,
            'practitioner_id' => $practitioners[0]->id,
            'client_id' => $primaryClient->id,
            'rating' => 5,
            'comment' => 'Dr. Mitchell has been exceptionally compassionate and insightful. The online portal makes booking sessions, telehealth video, and reviewing receipts completely effortless.',
            'is_anonymous' => true,
            'is_published' => true,
        ]);

        SupportTicket::create([
            'tenant_id' => $tenant->id,
            'user_id' => $owner->id,
            'ticket_number' => 'TKT-2026-001',
            'subject' => 'Inquiring about Biometric ADMS Attendance Bridge sync schedule',
            'category' => 'technical',
            'priority' => 'low',
            'status' => 'resolved',
        ]);

        // 18. Multilingual Blog Posts
        BlogPost::create([
            'title' => 'Overcoming Cognitive Distortions: Evidence-Based CBT Insights',
            'slug' => 'overcoming-cognitive-distortions-cbt-insights',
            'locale' => 'en',
            'category' => 'Cognitive Behavioral Therapy',
            'excerpt' => 'Discover how identifying automatic catastrophic thoughts and behavioral experiments can alleviate chronic anxiety.',
            'content' => "Cognitive Behavioral Therapy (CBT) rests on the foundational premise that our cognitive interpretations—not external occurrences themselves—directly shape our affective and emotional states.\n\nWhen confronted with stressors, the human mind naturally tends toward cognitive shortcuts known as cognitive distortions. Common examples include 'all-or-nothing thinking' and 'catastrophizing'.\n\nBy systematically documenting automatic thoughts in a structured Thought Record, individuals learn to challenge cognitive errors with grounded, objective evidence.",
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(10),
        ]);

        BlogPost::create([
            'title' => 'মানসিক স্বাস্থ্য ও আধুনিক কাউন্সেলিং সেবা: জীবনের সুস্থতার নতুন দিগন্ত',
            'slug' => 'mental-health-and-modern-counseling-in-bangla',
            'locale' => 'bn',
            'category' => 'মানসিক সুস্থতা (Mental Wellness)',
            'excerpt' => 'আধুনিক স্বাস্থ্যসেবায় মানসিক স্বাস্থ্যের গুরুত্ব অপরিসীম। ডিজিটাল প্ল্যাটফর্ম ও পেশাদার থেরাপিস্টদের সাহায্যে মানসিক সুস্থতা অর্জন এখন আরও সহজ।',
            'content' => "শরীরকে সুস্থ রাখতে আমরা যেমন নিয়মিত পুষ্টিকর খাবার খাই ও চিকিৎসকের শরণাপন্ন হই, তেমনি আমাদের মন ও মস্তিষ্কেরও নিয়মিত পরিচর্যার প্রয়োজন হয়। দৈনন্দিন কর্মব্যস্ততা ও মানসিক চাপের কারণে সমাজে হতাশা ও উদ্বেগের প্রকোপ বাড়ছে।\n\nআধুনিক সাইকোথেরাপি এবং কাউন্সেলিং সেশনের মাধ্যমে একজন ব্যক্তি নিজের আবেগ ও চিন্তাভাবনাকে নতুন করে বুঝতে পারেন। আমাদের সেরেনিটি ক্লিনিক্যাল সিস্টেম সম্পূর্ণ গোপনীয়তা বজায় রেখে ক্লায়েন্টদের স্বস্তিদায়ক পরিবেশে পেশাদার মানসিক স্বাস্থ্য সেবা নিশ্চিত করে।",
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(5),
        ]);

        BlogPost::create([
            'title' => 'The Role of Telehealth in Bridging Behavioral Healthcare Gaps',
            'slug' => 'role-of-telehealth-in-behavioral-healthcare',
            'locale' => 'en',
            'category' => 'Telehealth & Accessibility',
            'excerpt' => 'How browser-based encrypted video rooms are democratizing access to specialized psychotherapists worldwide.',
            'content' => "Geographic distance and mobility barriers historically prevented millions from accessing specialized psychological support. The advent of peer-to-peer encrypted WebRTC telehealth has redefined the clinical landscape.",
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(2),
        ]);

        // 19. Categorized FAQs
        $faqsData = [
            [
                'category' => 'Appointments & Booking',
                'question' => 'How does collision-free appointment scheduling work?',
                'answer' => 'Our system calculates real-time clinician availability, respecting defined working hours, buffer intervals between sessions, and approved leave days. Database transaction locks prevent double bookings under concurrent web requests.',
                'sort_order' => 1,
            ],
            [
                'category' => 'Appointments & Booking',
                'question' => 'What is the cancellation and rescheduling policy?',
                'answer' => 'Clients may reschedule or cancel appointments up to 24 hours prior to the scheduled start time without penalty via the Client Portal. Cancellations under 24 hours are subject to clinic late-cancellation policies.',
                'sort_order' => 2,
            ],
            [
                'category' => 'Clinical Privacy & HIPAA',
                'question' => 'Are my clinical notes confidential from receptionists and billing staff?',
                'answer' => 'Yes, absolutely. By design, our architecture isolates clinical records (SOAP/DAP notes and psychotherapy assessments) so they can only be viewed and edited by authorized clinicians. Receptionists and billing managers have strictly zero access to clinical PHI.',
                'sort_order' => 3,
            ],
            [
                'category' => 'Clinical Privacy & HIPAA',
                'question' => 'Can signed clinical notes be edited or deleted?',
                'answer' => 'No. Once a clinician applies their digital signature, clinical notes become immutable legal health records. Corrections are handled strictly via versioned amendments with mandatory clinical justification and comprehensive audit logs.',
                'sort_order' => 4,
            ],
            [
                'category' => 'Telehealth & Technology',
                'question' => 'Do I need to download an application for video therapy sessions?',
                'answer' => 'No application download is required. Telehealth sessions run directly inside modern web browsers (Chrome, Safari, Firefox, Edge) using end-to-end encrypted WebRTC. Audio and video streams are never stored on platform servers.',
                'sort_order' => 5,
            ],
            [
                'category' => 'Invoicing & Insurance',
                'question' => 'How do I obtain a receipt or Superbill for insurance reimbursement?',
                'answer' => 'Official Unicode PDF receipts detailing diagnosis codes, service dates, and payments can be downloaded with a single click from the Invoices tab in the Client Portal.',
                'sort_order' => 6,
            ],
        ];

        foreach ($faqsData as $faqItem) {
            Faq::create(array_merge($faqItem, ['is_active' => true, 'locale' => 'en']));
        }

        // 20. Audit Trail
        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => $superAdmin->id,
            'action' => 'seed_complete',
            'model_type' => Tenant::class,
            'model_id' => (string) $tenant->id,
            'old_values' => null,
            'new_values' => ['status' => 'Enterprise Serenity Wellness dataset deployed successfully.'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'ArtisanSeederCLI',
        ]);
    }
}
