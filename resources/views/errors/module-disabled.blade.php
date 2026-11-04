@extends('layouts.app', ['title' => 'Feature Unavailable - Mental Health SaaS'])

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 bg-slate-50">
    <div class="max-w-md mx-auto px-4 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-700 mx-auto flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Module Currently Deactivated</h1>
        <p class="mt-3 text-sm text-slate-600 leading-relaxed">
            The feature or module you are attempting to access has been deactivated for this organization or requires an upgraded subscription tier.
        </p>

        @if(isset($module))
            <div class="mt-4 p-3 bg-white rounded-xl border border-slate-200 inline-block text-xs font-mono text-slate-700">
                Module ID: <span class="font-bold text-teal-600">{{ $module }}</span>
            </div>
        @endif

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="javascript:history.back()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 text-xs font-semibold uppercase tracking-wider hover:bg-slate-50 transition">
                &larr; Go Back
            </a>
            @auth
                <a href="{{ auth()->user()->hasRole('Client') ? route('portal.dashboard') : '/admin' }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-teal-600 text-white text-xs font-semibold uppercase tracking-wider hover:bg-teal-700 transition">
                    Return to Dashboard
                </a>
            @else
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-teal-600 text-white text-xs font-semibold uppercase tracking-wider hover:bg-teal-700 transition">
                    Homepage
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
