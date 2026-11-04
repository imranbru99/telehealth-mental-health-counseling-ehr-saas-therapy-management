@extends('layouts.app')

@section('title', 'Book a Therapy Session - Serenity Health')

@section('content')
<div class="py-12 bg-slate-50 border-b border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                Online Booking Engine
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">Schedule Your Counseling Session</h1>
            <p class="text-slate-600 text-sm mt-2 max-w-xl mx-auto">
                Select your preferred therapy service, clinical practitioner, modality, and date. Safe, confidential, and convenient.
            </p>
        </div>

        <!-- Alpine.js Multi-Step Booking Form -->
        <div x-data="{
            step: 1,
            serviceId: '{{ $selectedService ? $selectedService->id : ($services->first()->id ?? 1) }}',
            practitionerId: '{{ $selectedPractitioner ? $selectedPractitioner->id : ($practitioners->first()->id ?? 1) }}',
            branchId: '{{ $branches->first()->id ?? 1 }}',
            deliveryMode: 'in_person',
            scheduledDate: '{{ today()->addDay()->toDateString() }}',
            scheduledTime: '',
            availableSlots: [],
            loadingSlots: false,
            firstName: '',
            lastName: '',
            email: '',
            phone: '',
            language: 'en',
            consentAgreed: false,
            emergencyName: '',
            emergencyPhone: '',
            intakeNotes: '',

            init() {
                this.fetchSlots();
            },

            fetchSlots() {
                if (!this.practitionerId || !this.scheduledDate) return;
                this.loadingSlots = true;
                this.scheduledTime = '';
                fetch(`/book/slots?practitioner_id=${this.practitionerId}&date=${this.scheduledDate}`)
                    .then(res => res.json())
                    .then(data => {
                        this.availableSlots = data.slots || [];
                        this.loadingSlots = false;
                        if (this.availableSlots.length > 0) {
                            this.scheduledTime = this.availableSlots[0];
                        }
                    })
                    .catch(() => {
                        this.availableSlots = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
                        this.scheduledTime = '10:00';
                        this.loadingSlots = false;
                    });
            }
        }" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">

            <!-- Step Progress Indicator -->
            <div class="bg-slate-900 px-6 py-4 border-b border-slate-800">
                <div class="flex items-center justify-between max-w-2xl mx-auto text-xs font-semibold">
                    <button type="button" @click="step = 1" class="flex items-center gap-2" :class="step >= 1 ? 'text-teal-400' : 'text-slate-500'">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs" :class="step >= 1 ? 'bg-teal-500 text-slate-950 font-bold' : 'bg-slate-700 text-slate-400'">1</span>
                        <span>Service & Modality</span>
                    </button>
                    <div class="w-8 h-0.5" :class="step >= 2 ? 'bg-teal-500' : 'bg-slate-800'"></div>
                    <button type="button" @click="if(step > 2) step = 2" class="flex items-center gap-2" :class="step >= 2 ? 'text-teal-400' : 'text-slate-500'">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs" :class="step >= 2 ? 'bg-teal-500 text-slate-950 font-bold' : 'bg-slate-700 text-slate-400'">2</span>
                        <span>Clinician & Time</span>
                    </button>
                    <div class="w-8 h-0.5" :class="step >= 3 ? 'bg-teal-500' : 'bg-slate-800'"></div>
                    <button type="button" @click="if(step > 3) step = 3" class="flex items-center gap-2" :class="step >= 3 ? 'text-teal-400' : 'text-slate-500'">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs" :class="step >= 3 ? 'bg-teal-500 text-slate-950 font-bold' : 'bg-slate-700 text-slate-400'">3</span>
                        <span>Intake & Consent</span>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('booking.store') }}" class="p-6 sm:p-10">
                @csrf

                <!-- STEP 1: Service & Modality -->
                <div x-show="step === 1" x-transition>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Step 1: Choose Your Therapy Service & Modality</h2>
                    <p class="text-xs text-slate-500 mb-6">Select the counseling service and format that suits your personal schedule.</p>

                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Consultation Service</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($services as $svc)
                                <label class="p-4 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                                       :class="serviceId == '{{ $svc->id }}' ? 'border-teal-600 bg-teal-50/50 ring-2 ring-teal-600' : 'border-slate-200 hover:border-slate-300'">
                                    <div class="flex items-start justify-between">
                                        <div class="font-bold text-slate-900 text-sm">{{ $svc->name }}</div>
                                        <input type="radio" name="service_id" value="{{ $svc->id }}" x-model="serviceId" class="text-teal-600 focus:ring-teal-500">
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $svc->description }}</div>
                                    <div class="mt-3 flex items-center justify-between text-xs font-semibold text-slate-700 pt-2 border-t border-slate-200/50">
                                        <span>{{ $svc->duration_minutes }} min</span>
                                        <span class="text-teal-800 font-bold">${{ number_format($svc->price, 2) }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Modality Selection -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Consultation Format (Modality)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="p-4 rounded-xl border cursor-pointer transition-all"
                                   :class="deliveryMode === 'in_person' ? 'border-teal-600 bg-teal-50/50 ring-2 ring-teal-600' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="delivery_mode" value="in_person" x-model="deliveryMode" class="text-teal-600 focus:ring-teal-500">
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">In-Person Consultation</div>
                                        <div class="text-xs text-slate-500">Visit our comfortable physical clinic suite.</div>
                                    </div>
                                </div>
                            </label>

                            <label class="p-4 rounded-xl border cursor-pointer transition-all"
                                   :class="deliveryMode === 'online_telehealth' ? 'border-teal-600 bg-teal-50/50 ring-2 ring-teal-600' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="delivery_mode" value="online_telehealth" x-model="deliveryMode" class="text-teal-600 focus:ring-teal-500">
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">Online Telehealth Video</div>
                                        <div class="text-xs text-slate-500">Encrypted video session accessible anywhere.</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Branch selection (if in-person) -->
                    <div class="mb-8" x-show="deliveryMode === 'in_person'">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Clinic Location Branch</label>
                        <select name="branch_id" x-model="branchId" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 focus:border-teal-500 p-3 bg-white">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }} - {{ $branch->city }}, {{ $branch->state }} ({{ $branch->address }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="step = 2; fetchSlots()" class="px-6 py-3 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm shadow-md transition-all">
                            Continue to Clinician & Time &rarr;
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Clinician & Availability -->
                <div x-show="step === 2" x-transition>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Step 2: Select Practitioner, Date & Time Slot</h2>
                    <p class="text-xs text-slate-500 mb-6">Choose a therapist and an available time slot. Double booking is automatically prevented.</p>

                    <!-- Practitioner selector -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Select Therapist</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($practitioners as $p)
                                <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                                       :class="practitionerId == '{{ $p->id }}' ? 'border-teal-600 bg-teal-50/50 ring-2 ring-teal-600' : 'border-slate-200 hover:border-slate-300'"
                                       @click="practitionerId = '{{ $p->id }}'; fetchSlots()">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-teal-700 text-white font-bold flex items-center justify-center text-sm">
                                            {{ substr($p->user->name ?? 'Dr', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $p->name }}</div>
                                            <div class="text-[11px] text-teal-700 font-medium">{{ $p->title }} &bull; {{ $p->session_duration_minutes }} min</div>
                                        </div>
                                    </div>
                                    <input type="radio" name="practitioner_id" value="{{ $p->id }}" x-model="practitionerId" class="text-teal-600 focus:ring-teal-500">
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Date picker -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Appointment Date</label>
                            <input type="date" name="scheduled_date" min="{{ today()->toDateString() }}" x-model="scheduledDate" @change="fetchSlots()"
                                   class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 focus:border-teal-500 p-3 bg-white" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Available Time Slots</label>

                            <div x-show="loadingSlots" class="p-6 text-center text-slate-500 text-xs">
                                <span class="animate-spin inline-block w-4 h-4 border-2 border-teal-600 border-t-transparent rounded-full mr-2"></span>
                                Calculating available slots...
                            </div>

                            <div x-show="!loadingSlots && availableSlots.length === 0" class="p-4 bg-amber-50 text-amber-800 text-xs rounded-xl border border-amber-200">
                                No open slots available on this date. Please select another date or practitioner.
                            </div>

                            <div x-show="!loadingSlots && availableSlots.length > 0" class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto p-1">
                                <template x-for="slot in availableSlots" :key="slot">
                                    <label class="p-2.5 rounded-lg border text-center text-xs font-semibold cursor-pointer transition-all"
                                           :class="scheduledTime === slot ? 'bg-teal-700 text-white border-teal-700' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300'">
                                        <input type="radio" name="scheduled_time" :value="slot" x-model="scheduledTime" class="hidden">
                                        <span x-text="slot"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <button type="button" @click="step = 1" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-medium text-sm hover:bg-slate-50">
                            &larr; Back
                        </button>
                        <button type="button" @click="if(scheduledTime) step = 3; else alert('Please select a time slot.')" class="px-6 py-3 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm shadow-md transition-all">
                            Continue to Intake & Consent &rarr;
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Intake & Consent Information -->
                <div x-show="step === 3" x-transition>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Step 3: Client Intake & Informed Consent</h2>
                    <p class="text-xs text-slate-500 mb-6">All information submitted is confidential and encrypted under strict medical privacy safeguards.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" x-model="firstName" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" x-model="lastName" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address *</label>
                            <input type="email" name="email" x-model="email" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number *</label>
                            <input type="tel" name="phone" x-model="phone" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Preferred Language</label>
                            <select name="preferred_language" x-model="language" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5 bg-white">
                                <option value="en">English (US/UK)</option>
                                <option value="bn">Bengali (বাংলা)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Emergency Contact (Name & Phone)</label>
                            <input type="text" name="emergency_contact_name" placeholder="e.g. Jane Doe (555) 019-2831" class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Initial Consultation Focus / Pre-Session Notes (Optional)</label>
                        <textarea name="intake_notes" rows="3" placeholder="Briefly describe what challenges or goals you would like to address..." class="w-full rounded-xl border-slate-300 text-sm focus:ring-teal-500 p-2.5"></textarea>
                    </div>

                    <!-- Mandatory Consent Checkbox -->
                    <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 mb-8">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="consent_agreed" value="1" required class="mt-1 rounded text-teal-600 focus:ring-teal-500">
                            <span class="text-xs text-slate-700 leading-relaxed">
                                <strong>Informed Consent & Privacy Notice:</strong> I understand that Serenity Mental Wellness Center provides licensed clinical counseling. I acknowledge the privacy notice and agree to the 24-hour cancellation policy. I understand this service is not an emergency hotline; for immediate psychiatric crises I will dial 988.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <button type="button" @click="step = 2" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-medium text-sm hover:bg-slate-50">
                            &larr; Back
                        </button>
                        <button type="submit" class="px-8 py-3.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-base shadow-lg shadow-teal-700/25 transition-all">
                            Confirm & Complete Booking &rarr;
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
