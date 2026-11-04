@extends('layouts.app', ['title' => 'Contact Us - Mental Health and Counseling SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Get In Touch</span>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mt-4 sm:text-5xl">We’re Here to Help Your Practice Flourish</h1>
            <p class="mt-4 text-lg text-slate-600">
                Have questions about our enterprise mental health platform, onboarding your clinic, or scheduling a personalized walkthrough? Our team is at your service.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 max-w-2xl mx-auto rounded-xl bg-teal-50 border border-teal-200 p-4 flex items-center gap-3 text-teal-900">
                <svg class="w-6 h-6 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Details & Branch Addresses -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Main Counseling Center
                    </h3>
                    <p class="text-sm text-slate-600">1204 Harmony Way, Suite 400<br>Central Medical District, CA 94103</p>
                    <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                        <div><strong class="text-slate-700">Phone:</strong> +1 (555) 349-2849</div>
                        <div><strong class="text-slate-700">Hours:</strong> Mon - Fri: 8:00 AM - 7:00 PM</div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Uptown Psychological Clinic
                    </h3>
                    <p class="text-sm text-slate-600">850 Wellness Boulevard, Floor 2<br>North Heights, CA 94110</p>
                    <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                        <div><strong class="text-slate-700">Phone:</strong> +1 (555) 892-1200</div>
                        <div><strong class="text-slate-700">Hours:</strong> Tue - Sat: 9:00 AM - 6:00 PM</div>
                    </div>
                </div>

                <div class="bg-teal-50 p-6 rounded-2xl border border-teal-200">
                    <h4 class="font-bold text-teal-900 text-sm mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        Non-Emergency Inquiries
                    </h4>
                    <p class="text-xs text-teal-800 leading-relaxed">
                        This contact form is for platform inquiries and appointment administrative requests. If you are experiencing a mental health emergency or suicidal crisis, call or text <strong>988</strong> immediately.
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Send Us a Message</h2>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Dr. Jane Doe or Alex Smith" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="jane@example.com" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Phone Number (Optional)</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none">
                                @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="subject" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Subject <span class="text-red-500">*</span></label>
                                <select name="subject" id="subject" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none">
                                    <option value="General Inquiry">General Platform Inquiry</option>
                                    <option value="Clinic SaaS Trial">Start Clinic SaaS Onboarding</option>
                                    <option value="Appointment Question">Appointment & Service Question</option>
                                    <option value="Billing Support">Invoicing & Billing Assistance</option>
                                    <option value="Security & Compliance">HIPAA & Security Verification</option>
                                    <option value="Partnership">Partnership & Clinical Integration</option>
                                </select>
                                @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Message <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="5" required placeholder="Tell us how we can help your clinic or therapy journey..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none">{{ old('message') }}</textarea>
                            @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <p class="text-xs text-slate-500">All submissions are protected under strict data privacy protocols.</p>
                            <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm rounded-xl transition shadow">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
