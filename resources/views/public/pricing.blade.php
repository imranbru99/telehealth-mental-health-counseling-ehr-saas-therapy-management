@extends('layouts.app')

@section('title', 'Subscription Pricing Plans - Serenity Health')
@section('meta_description', 'Choose the right mental healthcare practice management plan for your therapy clinic. Free, Starter, Pro, Business, Enterprise.')

@section('content')
<div class="py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                Transparent SaaS Monetization
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-4 tracking-tight">
                Simple, Scalable Pricing for Practices of Any Size
            </h1>
            <p class="text-slate-600 text-base mt-4 leading-relaxed">
                Whether you are an independent therapist starting your private practice or an established psychiatric health center with dozens of locations, our modular plans scale with you.
            </p>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-20">
            @foreach($plans as $plan)
                <div class="bg-white rounded-3xl border {{ $plan->slug === 'professional' ? 'border-teal-500 ring-2 ring-teal-500 shadow-2xl relative' : 'border-slate-200 shadow-sm' }} p-6 flex flex-col justify-between">
                    <div>
                        @if($plan->slug === 'professional')
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-teal-600 text-white px-2.5 py-1 rounded-full block text-center mb-3">Recommended</span>
                        @endif

                        <h3 class="font-extrabold text-slate-900 text-xl">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 min-h-[32px]">{{ $plan->description }}</p>

                        <div class="my-6">
                            <span class="text-4xl font-black text-slate-900">${{ number_format($plan->price_monthly, 0) }}</span>
                            <span class="text-xs text-slate-500 font-medium">/month</span>
                            <div class="text-[11px] text-teal-700 font-semibold mt-1">
                                or ${{ number_format($plan->price_annual, 0) }}/year (billed annually)
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Capacity Quotas:</div>
                            <ul class="space-y-2.5 text-xs text-slate-700">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span><strong>{{ $plan->max_practitioners }}</strong> Clinician{{ $plan->max_practitioners > 1 ? 's' : '' }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span><strong>{{ $plan->max_clients }}</strong> Active Clients</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span><strong>{{ $plan->max_branches }}</strong> Practice Location{{ $plan->max_branches > 1 ? 's' : '' }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Unicode Invoicing & PDF</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Self-Service Client Portal</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="w-full mt-8 py-3 px-4 rounded-xl text-center text-xs font-bold transition-all {{ $plan->slug === 'professional' ? 'bg-teal-700 hover:bg-teal-800 text-white shadow-lg shadow-teal-700/25' : 'bg-slate-900 hover:bg-slate-800 text-white' }}">
                        Choose {{ $plan->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
