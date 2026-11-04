<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\ClinicalNote;
use App\Models\Invoice;
use App\Models\Practitioner;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\TherapySession;
use App\Models\User;
use App\Services\BookingService;
use App\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected ModuleService $moduleService
    ) {
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password credentials.',
            ], 401);
        }

        $deviceName = $validated['device_name'] ?? 'MHCB-Api-Client';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'tenant_id' => $user->tenant_id,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Access token revoked successfully.',
        ]);
    }

    /**
     * GET /api/v1/auth/user
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load(['tenant', 'roles']),
        ]);
    }

    /**
     * GET /api/v1/practitioners
     */
    public function practitioners(): JsonResponse
    {
        $practitioners = Practitioner::with(['user', 'branches'])
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $practitioners,
        ]);
    }

    /**
     * GET /api/v1/services
     */
    public function services(): JsonResponse
    {
        $services = Service::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * GET /api/v1/branches
     */
    public function branches(): JsonResponse
    {
        $branches = Branch::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $branches,
        ]);
    }

    /**
     * GET /api/v1/appointments/slots
     */
    public function appointmentSlots(Request $request): JsonResponse
    {
        $request->validate([
            'practitioner_id' => 'required|integer',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $slots = $this->bookingService->getAvailableSlots(
            (int) $request->input('practitioner_id'),
            $request->input('date')
        );

        return response()->json([
            'success' => true,
            'date' => $request->input('date'),
            'slots' => $slots,
        ]);
    }

    /**
     * GET /api/v1/appointments
     */
    public function appointments(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Appointment::with(['practitioner.user', 'service', 'branch']);

        if ($user->isClient()) {
            $client = $user->clientProfile;
            if ($client) {
                $query->where('client_id', $client->id);
            }
        } elseif ($user->isPractitioner()) {
            $practitioner = $user->practitionerProfile;
            if ($practitioner) {
                $query->where('practitioner_id', $practitioner->id);
            }
        }

        $appointments = $query->orderByDesc('scheduled_at')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ]);
    }

    /**
     * POST /api/v1/appointments
     */
    public function bookAppointment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'practitioner_id' => 'required|exists:practitioners,id',
            'scheduled_at' => 'required|date|after:now',
            'delivery_mode' => 'required|in:in_person,online_telehealth',
            'client_email' => 'required|email',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        try {
            $appointment = $this->bookingService->bookAppointment($validated);

            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully.',
                'appointment' => $appointment->load(['service', 'practitioner.user']),
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * GET /api/v1/therapy-sessions
     */
    public function therapySessions(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isPractitioner() && !$user->isAdmin() && !$user->isSuperAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $sessions = TherapySession::with(['client', 'practitioner.user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $sessions,
        ]);
    }

    /**
     * GET /api/v1/clinical-notes
     */
    public function clinicalNotes(Request $request): JsonResponse
    {
        $user = $request->user();

        // Strict HIPAA / GDPR safeguard: Only licensed clinicians & authorized supervisors can query clinical documentation
        if (!$user->isPractitioner() && !$user->isSuperAdmin() && !$user->isAuditor()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Clinical documentation access restricted to licensed clinicians and authorized compliance auditors.',
            ], 403);
        }

        $notes = ClinicalNote::with(['client', 'practitioner.user', 'signedBy'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $notes,
        ]);
    }

    /**
     * GET /api/v1/invoices
     */
    public function invoices(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Invoice::with(['items', 'client']);

        if ($user->isClient()) {
            $client = $user->clientProfile;
            if ($client) {
                $query->where('client_id', $client->id);
            }
        }

        $invoices = $query->orderByDesc('issue_date')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }

    /**
     * GET /api/v1/modules
     */
    public function modules(): JsonResponse
    {
        $modules = $this->moduleService->getAllModules();

        return response()->json([
            'success' => true,
            'data' => $modules,
        ]);
    }
}
