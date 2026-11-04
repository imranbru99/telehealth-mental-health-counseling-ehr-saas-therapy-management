<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            AuditLog::log('login', $user);

            return $this->redirectBasedOnRole($user);
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our registered records.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:25',
            'register_type' => 'required|in:client,organization',
            'organization_name' => 'nullable|string|max:150',
        ]);

        if ($validated['register_type'] === 'organization') {
            $tenantName = $validated['organization_name'] ?? "{$validated['name']}'s Practice";
            $tenant = Tenant::create([
                'name' => $tenantName,
                'slug' => Str::slug($tenantName) . '-' . Str::random(4),
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'organization_owner',
                'tenant_id' => $tenant->id,
                'phone' => $validated['phone'],
            ]);

            app(\App\Services\ModuleService::class)->initializeTenantModules($tenant);
        } else {
            // Register as Client
            $tenant = Tenant::first();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'client',
                'tenant_id' => $tenant ? $tenant->id : null,
                'phone' => $validated['phone'],
            ]);

            $names = explode(' ', $validated['name'], 2);
            Client::create([
                'tenant_id' => $tenant ? $tenant->id : 1,
                'user_id' => $user->id,
                'first_name' => $names[0] ?? 'Client',
                'last_name' => $names[1] ?? 'User',
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => 'active',
            ]);
        }

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if (in_array($user->role, ['client', 'guardian'])) {
            return redirect()->route('portal.dashboard');
        }

        // Platform & Clinic Staff to Filament Admin Panel
        return redirect()->to('/admin');
    }
}
