@extends('layouts.app', ['title' => 'Frequently Asked Questions - Mental Health SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Knowledge Base</span>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mt-4 sm:text-5xl">Frequently Asked Questions</h1>
            <p class="mt-4 text-lg text-slate-600">
                Explore answers to common questions about clinic onboarding, HIPAA/GDPR data isolation, clinical documentation, billing, and telehealth.
            </p>
        </div>

        @php
            $groupedFaqs = isset($categories) && $categories->isNotEmpty() ? $categories : $faqs->groupBy('category');
        @endphp

        @if($faqs->isEmpty())
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500">
                Frequently asked questions will appear here once seeded.
            </div>
        @else
            <div class="space-y-12">
                @foreach($groupedFaqs as $category => $items)
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                            {{ $category ?? 'General Inquiries' }}
                        </h2>

                        <div class="space-y-3" x-data="{ active: null }">
                            @foreach($items as $faq)
                                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm transition">
                                    <button 
                                        type="button" 
                                        @click="active = (active === {{ $faq->id }} ? null : {{ $faq->id }})" 
                                        class="w-full px-6 py-4 text-left font-semibold text-slate-800 flex items-center justify-between hover:bg-slate-50 focus:outline-none"
                                    >
                                        <span class="text-sm sm:text-base">{{ $faq->question }}</span>
                                        <svg 
                                            class="w-5 h-5 text-slate-400 transform transition-transform shrink-0 ml-4" 
                                            :class="{ 'rotate-180': active === {{ $faq->id }} }" 
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div 
                                        x-show="active === {{ $faq->id }}" 
                                        x-collapse 
                                        x-cloak
                                        class="px-6 pb-5 pt-1 text-slate-600 text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50"
                                    >
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Still have questions card -->
        <div class="mt-16 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
            <h3 class="text-lg font-bold text-slate-900">Still have unanswered questions?</h3>
            <p class="text-sm text-slate-500 mt-2">Our support specialists and clinical compliance advisors are ready to assist you.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact') }}" class="px-6 py-2.5 rounded-xl bg-teal-600 text-white font-semibold text-xs uppercase tracking-wider hover:bg-teal-700 transition">Contact Support</a>
                <a href="{{ route('demo.request') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs uppercase tracking-wider hover:bg-slate-200 transition">Test Live Demo</a>
            </div>
        </div>
    </div>
</div>
@endsection
