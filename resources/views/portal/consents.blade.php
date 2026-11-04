@extends('portal.layout', ['title' => 'Informed Consents - Client Portal'])

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Informed Consents & Agreements</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review legal, clinical, and telehealth consent agreements required for your ongoing therapy.</p>
        </div>
    </div>

    @if($consents->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center">
            <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">All Consents Up to Date</h3>
            <p class="text-xs text-slate-500 mt-1">You have no pending consent documents requiring your electronic signature.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($consents as $consent)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $consent->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $consent->status === 'accepted' ? '✓ Signed' : 'Pending Signature' }}
                            </span>
                            <span class="text-xs text-slate-400">&bull;</span>
                            <span class="text-xs text-slate-500 font-mono">v{{ $consent->version ?? '1.0' }}</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">{{ $consent->consent_type ?? 'Therapy & Confidentiality Agreement' }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $consent->notes ?? 'Informed consent for psychological counseling, telehealth communication safeguards, and cancellation fee policy.' }}
                        </p>
                        @if($consent->status === 'accepted')
                            <div class="text-[11px] text-slate-400 flex items-center gap-3 pt-1">
                                <span>Signed: {{ $consent->signed_at ? \Carbon\Carbon::parse($consent->signed_at)->format('M d, Y h:i A') : 'Recorded' }}</span>
                                <span>IP: {{ $consent->ip_address ?? '127.0.0.1' }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="shrink-0 w-full md:w-auto">
                        @if($consent->status !== 'accepted')
                            <form action="{{ route('portal.consents.sign', $consent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs uppercase tracking-wider transition shadow">
                                    Electronically Sign
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                On File
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
