@extends('layouts.app', ['title' => 'Create an Account - Mental Health SaaS'])

@section('content')
<div class="py-12 sm:py-16 bg-slate-50 min-h-[85vh] flex items-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 w-full" x-data="{ accountType: 'client' }">
        <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
            <div class="text-center mb-8">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Get Started</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-2">Create Your Account</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Select whether you are a client booking appointments or a healthcare clinic owner.</p>

                <!-- Account Type Switcher -->
                <div class="mt-6 p-1 bg-slate-100 rounded-2xl flex max-w-md mx-auto">
                    <button 
                        type="button" 
                        @click="accountType = 'client'" 
                        :class="accountType === 'client' ? 'bg-white shadow text-teal-800 font-bold' : 'text-slate-600 font-medium hover:text-slate-900'"
                        class="w-1/2 py-2.5 rounded-xl text-xs sm:text-sm transition text-center"
                    >
                        Client / Patient
                    </button>
                    <button 
                        type="button" 
                        @click="accountType = 'tenant'" 
                        :class="accountType === 'tenant' ? 'bg-white shadow text-teal-800 font-bold' : 'text-slate-600 font-medium hover:text-slate-900'"
                        class="w-1/2 py-2.5 rounded-xl text-xs sm:text-sm transition text-center"
                    >
                        Start Clinic Free Trial
                    </button>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="account_type" :value="accountType">

                <!-- Clinic Owner Fields -->
                <div x-show="accountType === 'tenant'" x-cloak class="space-y-4 p-4 rounded-2xl bg-teal-50/60 border border-teal-100 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-teal-900">Organization / Clinic Information</h3>
                    
                    <div>
                        <label for="clinic_name" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Clinic / Practice Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="clinic_name" 
                            id="clinic_name" 
                            value="{{ old('clinic_name') }}" 
                            placeholder="e.g. Hope Horizon Counseling" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none bg-white"
                        >
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Subdomain Slug <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <input 
                                type="text" 
                                name="slug" 
                                id="slug" 
                                value="{{ old('slug') }}" 
                                placeholder="hope-horizon" 
                                class="w-full rounded-l-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-500 outline-none bg-white"
                            >
                            <span class="px-3 py-2 bg-slate-100 border border-l-0 border-slate-300 rounded-r-xl text-xs text-slate-500 font-mono">.mhcb.test</span>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            required 
                            value="{{ old('name') }}" 
                            placeholder="Alex Morgan" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 outline-none"
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Phone Number</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone') }}" 
                            placeholder="+1 (555) 019-2834" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 outline-none"
                        >
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        value="{{ old('email') }}" 
                        placeholder="alex@example.com" 
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 outline-none"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Password <span class="text-red-500">*</span></label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            placeholder="At least 8 characters" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 outline-none"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required 
                            placeholder="Repeat password" 
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-teal-500 outline-none"
                        >
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-2 cursor-pointer text-xs text-slate-600">
                        <input type="checkbox" required class="mt-0.5 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span>I agree to the <a href="{{ route('legal.terms') }}" target="_blank" class="text-teal-600 underline">Terms of Service</a> and understand the <a href="{{ route('legal.privacy') }}" target="_blank" class="text-teal-600 underline">Privacy & HIPAA Policy</a>.</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm rounded-xl transition shadow mt-2">
                    <span x-text="accountType === 'tenant' ? 'Start 14-Day Free Clinic Trial' : 'Complete Client Registration'"></span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-semibold text-teal-600 hover:text-teal-800">Sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection
