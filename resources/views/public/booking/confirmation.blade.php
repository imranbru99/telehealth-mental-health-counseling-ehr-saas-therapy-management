@extends('layouts.app')

@section('title', 'Appointment Confirmed - Serenity Health')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-10 text-center">
            <!-- Success Icon -->
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Booking Confirmed
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3 mb-2">Your Therapy Session is Scheduled</h1>
            <p class="text-slate-600 text-sm mb-8">
                A confirmation receipt and calendar invitation have been registered for <strong>{{ $appointment->client->email }}</strong>.
            </p>

            <!-- Appointment Details Card -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 text-left mb-8 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <span class="text-xs text-slate-500 font-medium">Appointment Number</span>
                    <span class="text-xs font-mono font-bold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200">{{ $appointment->appointment_number }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-medium">Consultation Service</span>
                    <span class="text-sm font-bold text-slate-900">{{ $appointment->service->name }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-medium">Clinical Practitioner</span>
                    <span class="text-sm font-bold text-teal-800">{{ $appointment->practitioner->name }} ({{ $appointment->practitioner->title }})</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-medium">Date & Time</span>
                    <span class="text-sm font-bold text-slate-900">{{ $appointment->scheduled_at->format('l, F j, Y \a\t g:i A') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-medium">Modality</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded uppercase {{ $appointment->delivery_mode === 'online_telehealth' ? 'bg-sky-100 text-sky-800' : 'bg-slate-200 text-slate-800' }}">
                        {{ str_replace('_', ' ', $appointment->delivery_mode) }}
                    </span>
                </div>

                @if($appointment->delivery_mode === 'in_person' && $appointment->branch)
                    <div class="flex items-start justify-between pt-2 border-t border-slate-200">
                        <span class="text-xs text-slate-500 font-medium">Location</span>
                        <span class="text-xs text-right text-slate-800 font-semibold">{{ $appointment->branch->name }} &bull; {{ $appointment->branch->address }}</span>
                    </div>
                @endif

                @if($appointment->delivery_mode === 'online_telehealth')
                    <div class="p-4 bg-sky-50 rounded-xl border border-sky-200 text-sky-900 text-xs mt-3">
                        <div class="font-bold mb-1">Telehealth Joining Instructions:</div>
                        <p class="mb-2">Your video consultation room will unlock 10 minutes prior to session start. No software download required.</p>
                        @if($appointment->telehealth_meeting_url)
                            <a href="{{ $appointment->telehealth_meeting_url }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-sky-700 underline">
                                <span>Preview Video Room URL</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if($appointment->invoice)
                    <a href="{{ route('invoices.pdf', $appointment->invoice->id) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Download Invoice PDF (Unicode)</span>
                    </a>
                @endif

                <a href="{{ route('portal.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm shadow-md transition-all">
                    <span>Enter Client Portal</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
