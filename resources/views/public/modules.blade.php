@extends('layouts.app')

@section('title', 'All 26 SaaS Business Modules - Serenity Health')
@section('meta_description', 'Explore all 26 centralized, independently manageable SaaS modules of our mental healthcare and counseling practice platform.')

@section('content')
<div class="py-16 bg-gradient-to-b from-teal-50/50 via-white to-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                Modular SaaS Architecture
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-4 tracking-tight">
                26 Business Management Modules
            </h1>
            <p class="text-slate-600 text-base mt-4 leading-relaxed">
                Every organization retains autonomous control over which business and clinical features to activate. Built dynamically from our centralized Module Management System.
            </p>
        </div>

        <!-- Categories & Modules -->
        @foreach($categories as $categoryName => $catModules)
            <div class="mb-14">
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-200">
                    <span class="w-3 h-3 rounded-full bg-teal-600"></span>
                    <h2 class="text-xl font-bold uppercase tracking-wide text-slate-800">
                        {{ ucfirst($categoryName) }} Architecture
                    </h2>
                    <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                        {{ count($catModules) }} {{ Str::plural('module', count($catModules)) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($catModules as $mod)
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-teal-300 transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 font-bold flex items-center justify-center text-sm">
                                        #{{ $mod['sort_order'] }}
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        @if($mod['is_core'])
                                            <span class="text-[10px] font-bold uppercase bg-slate-100 text-slate-700 px-2 py-0.5 rounded">Core</span>
                                        @endif
                                        <span class="text-[10px] font-bold uppercase bg-teal-50 text-teal-800 px-2 py-0.5 rounded border border-teal-200">
                                            Tier: {{ $mod['min_plan_tier'] }}
                                        </span>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $mod['name'] }}</h3>
                                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                                    {{ $mod['description'] }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span>Version <strong>{{ $mod['version'] }}</strong></span>
                                <span>
                                    @if(!empty($mod['dependencies']))
                                        Requires: <strong class="text-slate-700">{{ implode(', ', $mod['dependencies']) }}</strong>
                                    @else
                                        <span class="text-emerald-700 font-medium">Standalone</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
