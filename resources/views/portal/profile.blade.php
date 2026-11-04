@extends('portal.layout', ['title' => 'Profile & Emergency Contacts - Client Portal'])

@section('portal_content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900">Personal Profile & Emergency Contacts</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Keep your demographic details, communication preferences, and emergency contacts accurate.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl">
        <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- User Information -->
            <div>
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                    Account Credentials
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Full Legal Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Primary Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" required value="{{ old('phone', auth()->user()->phone ?? ($client->phone ?? '')) }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $client->date_of_birth ?? '') }}" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Gender Identity</label>
                        <select name="gender" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                            <option value="">Select Gender Identity</option>
                            <option value="Female" {{ ($client->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Male" {{ ($client->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Non-Binary" {{ ($client->gender ?? '') === 'Non-Binary' ? 'selected' : '' }}>Non-Binary</option>
                            <option value="Prefer Not to Say" {{ ($client->gender ?? '') === 'Prefer Not to Say' ? 'selected' : '' }}>Prefer Not to Say</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="pt-4">
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    Mandatory Clinical Emergency Contact
                </h2>
                <p class="text-xs text-slate-500 mb-4">Required by psychological practice safety standards in the event of an acute mental health emergency.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $client->emergency_contact_name ?? '') }}" placeholder="Jane Doe" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Emergency Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $client->emergency_contact_phone ?? '') }}" placeholder="+1 (555) 000-1111" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Relationship</label>
                        <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $client->emergency_contact_relationship ?? '') }}" placeholder="Spouse / Parent / Friend" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs uppercase tracking-wider rounded-xl transition shadow">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
