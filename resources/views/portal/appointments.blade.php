@extends('portal.layout', ['title' => 'My Appointments - Client Portal'])

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">My Appointments</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your scheduled, completed, and upcoming therapy appointments.</p>
        </div>
        <a href="{{ route('booking.index') }}" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs uppercase tracking-wider transition shadow">
            + Book New Appointment
        </a>
    </div>

    @if($appointments->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center">
            <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">No Appointments Recorded</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">You do not currently have any scheduled or past appointments in this practice.</p>
            <div class="mt-6">
                <a href="{{ route('booking.index') }}" class="px-5 py-2.5 rounded-xl bg-teal-600 text-white font-semibold text-xs uppercase tracking-wider">Book Your First Session</a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Reference / Date</th>
                            <th class="py-4 px-6">Service & Modality</th>
                            <th class="py-4 px-6">Practitioner</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @foreach($appointments as $apt)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-6 font-medium">
                                    <div class="font-mono font-bold text-slate-900">{{ $apt->appointment_number }}</div>
                                    <div class="text-slate-500 mt-0.5">
                                        {{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }} &bull; {{ substr($apt->start_time, 0, 5) }} - {{ substr($apt->end_time, 0, 5) }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-900">{{ $apt->service->name ?? 'Consultation' }}</div>
                                    <div class="mt-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $apt->delivery_mode === 'telehealth' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ ucfirst($apt->delivery_mode) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-900">{{ $apt->practitioner->name ?? 'Assigned Clinician' }}</div>
                                    <div class="text-slate-400 text-[11px]">{{ $apt->branch->name ?? 'Main Practice' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusClasses = [
                                            'confirmed' => 'bg-emerald-100 text-emerald-800',
                                            'scheduled' => 'bg-teal-100 text-teal-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'no_show' => 'bg-slate-100 text-slate-700',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$apt->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ ucfirst($apt->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($apt->delivery_mode === 'telehealth' && in_array($apt->status, ['scheduled', 'confirmed']))
                                            <a href="{{ route('portal.telehealth', $apt->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-semibold text-[11px] hover:bg-indigo-700 transition">
                                                Join Video
                                            </a>
                                        @endif

                                        @if(in_array($apt->status, ['scheduled', 'confirmed']))
                                            <form action="{{ route('portal.appointments.cancel', $apt->id) }}" method="POST" onsubmit="return confirm('Cancel this appointment?');">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-200 font-medium text-[11px] transition">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $appointments->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
