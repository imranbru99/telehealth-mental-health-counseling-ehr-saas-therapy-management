@extends('layouts.app', ['title' => 'Clinical & Mental Health Insights - Blog'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 pb-6 border-b border-slate-200">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 tracking-wide uppercase">Practice Insights</span>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-2 sm:text-4xl">Clinical & Mental Health Journal</h1>
                <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                    Evidence-based articles on behavioral healthcare documentation, modern counseling methodologies, and healthcare SaaS management.
                </p>
            </div>

            <!-- Language Switcher for Blog -->
            <div class="mt-6 md:mt-0 flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500 uppercase">Filter Language:</span>
                <a href="{{ route('blog') }}" class="px-3 py-1 rounded-lg text-xs font-medium {{ empty($locale) ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}">All</a>
                <a href="{{ route('blog', ['lang' => 'en']) }}" class="px-3 py-1 rounded-lg text-xs font-medium {{ $locale === 'en' ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}">English</a>
                <a href="{{ route('blog', ['lang' => 'bn']) }}" class="px-3 py-1 rounded-lg text-xs font-medium {{ $locale === 'bn' ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}">বাংলা (Bengali)</a>
            </div>
        </div>

        @if($posts->isEmpty())
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-500">
                <p class="text-base font-semibold">No articles found in this category.</p>
                <p class="text-xs mt-1">Please check back soon for fresh insights from our clinical editorial board.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            @if($post->cover_image)
                                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-teal-700 to-cyan-900 flex items-center justify-center p-6 text-white text-center">
                                    <span class="font-bold text-lg opacity-90">{{ $post->title }}</span>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-teal-50 text-teal-700">
                                        {{ $post->category ?? 'Practice' }}
                                    </span>
                                    <span class="text-xs text-slate-400">&bull;</span>
                                    <span class="text-xs text-slate-500">{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                                    <span class="text-xs text-slate-400">&bull;</span>
                                    <span class="text-[10px] font-mono uppercase bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">{{ $post->locale ?? 'en' }}</span>
                                </div>

                                <h2 class="text-lg font-bold text-slate-900 leading-snug hover:text-teal-600 transition">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                <p class="mt-3 text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </p>
                            </div>
                        </div>

                        <div class="px-6 pb-6 pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-600">{{ $post->author_name ?? 'Editorial Staff' }}</span>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800">Read Article &rarr;</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
