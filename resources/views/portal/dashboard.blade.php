@extends('portal.layout', ['title' => 'Dashboard - Client Portal'])

@section('portal_content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-teal-800 to-cyan-900 rounded-3xl p-6 sm:p-8 text-white shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-teal-600/60 text-teal-100 uppercase tracking-wider">Client Health Dashboard</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-2">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-xs sm:text-sm text-teal-100 mt-1">Review your upcoming therapy sessions, care plans, and clinical documents.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('booking.index') }}" class="px-5 py-2.5 rounded-xl bg-white text-teal-900 font-semibold text-xs uppercase tracking-wider hover:bg-teal-50 transition shadow">
                Schedule New Session
            </a>
        </div>
    </div>

    <!-- Quick Stats & Alerts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Upcoming Sessions</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $upcomingAppointments->count() }}</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Pending Invoices</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $recentInvoices->where('payment_status', 'unpaid')->count() }}</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Signed Consents</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $consents->count() }}</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Unread Messages</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $unreadMessagesCount ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Upcoming Sessions & Recent Invoices -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Upcoming Sessions -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Upcoming Appointments</h2>
                <a href="{{ route('portal.appointments') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800">View All &rarr;</a>
            </div>

            @if($upcomingAppointments->isEmpty())
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center">
                    <p class="text-sm font-semibold text-slate-700">No upcoming appointments scheduled.</p>
                    <p class="text-xs text-slate-500 mt-1">Ready for your next check-in? Schedule a session with your therapist.</p>
                    <div class="mt-4">
                        <a href="{{ route('booking.index') }}" class="px-4 py-2 rounded-xl bg-teal-600 text-white font-semibold text-xs uppercase tracking-wider hover:bg-teal-700 transition">Book Now</a>
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($upcomingAppointments as $apt)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                        {{ $apt->service->name ?? 'Therapy Consultation' }}
                                    </span>
                                    <span class="text-xs text-slate-400">&bull;</span>
                                    <span class="text-xs font-semibold {{ $apt->delivery_mode === 'telehealth' ? 'text-indigo-600' : 'text-slate-600' }}">
                                        {{ ucfirst($apt->delivery_mode) }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-base mt-2">
                                    {{ $apt->practitioner->name ?? 'Assigned Clinician' }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                    <span>📅 {{ \Carbon\Carbon::parse($apt->appointment_date)->format('l, M d, Y') }}</span>
                                    <span>⏰ {{ substr($apt->start_time, 0, 5) }} - {{ substr($apt->end_time, 0, 5) }}</span>
                                </p>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end gap-2 w-full sm:w-auto">
                                @if($apt->delivery_mode === 'telehealth')
                                    <a href="{{ route('portal.telehealth', $apt->id) }}" class="w-full sm:w-auto text-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs uppercase tracking-wider transition shadow-sm">
                                        Join Video Room
                                    </a>
                                @endif
                                <form action="{{ route('portal.appointments.cancel', $apt->id) }}" method="POST" onsubmit="return confirm('Are you sure you wish to cancel this appointment?');" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto text-center px-4 py-1.5 rounded-xl border border-slate-200 text-slate-600 hover:text-red-600 hover:border-red-200 text-xs font-medium transition">
                                        Cancel Session
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Invoices & Billing Summary -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Recent Invoices & Receipts</h2>
                <a href="{{ route('portal.invoices') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800">View All &rarr;</a>
            </div>

            @if($recentInvoices->isEmpty())
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500 text-xs">
                    No invoices generated yet.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentInvoices as $inv)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-slate-900">{{ $inv->invoice_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $inv->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($inv->payment_status) }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">Due: {{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('M d, Y') : 'Due upon receipt' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-slate-900">${{ number_format($inv->total, 2) }}</div>
                                <div class="mt-1 flex items-center justify-end gap-2">
                                    <a href="{{ route('invoices.pdf', $inv->id) }}" class="text-[11px] font-semibold text-teal-600 hover:text-teal-800">PDF Receipt</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
