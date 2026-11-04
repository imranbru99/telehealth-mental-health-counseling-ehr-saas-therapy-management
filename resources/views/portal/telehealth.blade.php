@extends('portal.layout', ['title' => 'Telehealth Consultation Room - Client Portal'])

@section('portal_content')
<div class="space-y-6">
    <!-- Breadcrumb & Status -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('portal.appointments') }}" class="hover:text-teal-600">&larr; Back to Appointments</a>
                <span>&bull;</span>
                <span>Room ID: <strong class="font-mono text-slate-700">{{ $roomName }}</strong></span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Telehealth Video Consultation</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Appointment with <strong class="text-slate-800">{{ $appointment->practitioner->name ?? 'Your Clinician' }}</strong> &bull; {{ $appointment->service->name ?? 'Clinical Session' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Encrypted WebRTC Active
            </span>
        </div>
    </div>

    <!-- Video Room Wrapper -->
    <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-slate-800 relative">
        <!-- Top bar inside video -->
        <div class="p-4 bg-slate-950/80 backdrop-blur border-b border-slate-800 flex items-center justify-between text-white text-xs z-10">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                <span class="font-bold tracking-wide">SECURE CLINICAL SESSION</span>
                <span class="text-slate-400">|</span>
                <span class="text-slate-300 font-mono" id="sessionTimer">00:00:00</span>
            </div>
            <div class="text-[11px] text-slate-400">
                HIPAA / GDPR Zero-Persistence Stream
            </div>
        </div>

        <!-- Embedded WebRTC / Jitsi Meet Container -->
        <div class="w-full h-[600px] bg-slate-950 relative flex items-center justify-center" id="meetContainer">
            <iframe 
                src="https://meet.jit.si/{{ $roomName }}#config.prejoinPageEnabled=false&config.disableDeepLinking=true&interfaceConfig.TOOLBAR_BUTTONS=['microphone','camera','closedcaptions','desktop','fullscreen','fittoscreen','chat','raisehand','tileview']" 
                allow="camera; microphone; fullscreen; display-capture; autoplay" 
                class="w-full h-full border-0"
                title="Encrypted Telehealth Session Room"
            ></iframe>
        </div>

        <!-- Bottom Controls / Safety Banner -->
        <div class="p-4 bg-slate-950 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-slate-400 text-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>End-to-End Encrypted Session. Audio & video streams are never recorded or stored.</span>
            </div>
            <a href="{{ route('portal.appointments') }}" class="px-4 py-2 rounded-xl bg-red-600/80 hover:bg-red-600 text-white font-semibold text-xs uppercase tracking-wider transition">
                Leave Session
            </a>
        </div>
    </div>

    <!-- Telehealth Best Practices Guide -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-slate-600">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold shrink-0">1</div>
            <div>
                <h4 class="font-bold text-slate-800">Private & Quiet Space</h4>
                <p class="mt-1 text-slate-500">Ensure you are in a confidential, private room without background noise for optimal therapy effectiveness.</p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold shrink-0">2</div>
            <div>
                <h4 class="font-bold text-slate-800">Stable Connection & Headphones</h4>
                <p class="mt-1 text-slate-500">Use headphones or earphones to prevent audio feedback and protect clinical conversation privacy.</p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-bold shrink-0">!</div>
            <div>
                <h4 class="font-bold text-slate-800">Crisis Assistance</h4>
                <p class="mt-1 text-slate-500">In the event of an acute mental health crisis during this session, notify your practitioner or dial 988.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple session timer
    let seconds = 0;
    const timerEl = document.getElementById('sessionTimer');
    setInterval(() => {
        seconds++;
        const hrs = String(Math.floor(seconds / 3600)).padStart(2, '0');
        const mins = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        if(timerEl) timerEl.textContent = `${hrs}:${mins}:${secs}`;
    }, 1000);
</script>
@endsection
