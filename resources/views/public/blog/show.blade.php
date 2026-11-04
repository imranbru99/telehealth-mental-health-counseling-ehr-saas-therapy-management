@extends('layouts.app', ['title' => $post->title . ' - Mental Health SaaS'])

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back link -->
        <div class="mb-8">
            <a href="{{ route('blog') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800 flex items-center gap-1">
                &larr; Back to all articles
            </a>
        </div>

        <article class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-8 sm:p-12">
            <div class="flex items-center gap-3 mb-6">
                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-100 text-teal-800">
                    {{ $post->category ?? 'Clinical Practice' }}
                </span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500">{{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
                <span class="text-xs text-slate-400">&bull;</span>
                <span class="text-xs font-mono uppercase bg-slate-100 px-2 py-0.5 rounded text-slate-600">Language: {{ $post->locale ?? 'en' }}</span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
                {{ $post->title }}
            </h1>

            <div class="flex items-center gap-3 pb-8 mb-8 border-b border-slate-100">
                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                    {{ substr($post->author_name ?? 'IA', 0, 2) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-slate-900">{{ $post->author_name ?? 'Editorial Staff' }}</div>
                    <div class="text-xs text-slate-500">Verified Clinical Contributor</div>
                </div>
            </div>

            @if($post->cover_image)
                <div class="mb-8 rounded-2xl overflow-hidden">
                    <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-auto max-h-96 object-cover">
                </div>
            @endif

            <!-- Article Body -->
            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- Tags / Meta -->
            @if($post->tags && count($post->tags) > 0)
                <div class="mt-10 pt-6 border-t border-slate-100 flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium">#{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </article>

        <!-- Related Posts -->
        @if(isset($related) && $related->isNotEmpty())
            <div class="mt-16">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Related Clinical Insights</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $rel)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-0.5 rounded">{{ $rel->category ?? 'Insights' }}</span>
                                <h4 class="font-bold text-slate-900 text-sm mt-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $rel->slug) }}" class="hover:text-teal-600">{{ $rel->title }}</a>
                                </h4>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400">{{ $rel->published_at ? $rel->published_at->format('M d') : '' }}</span>
                                <a href="{{ route('blog.show', $rel->slug) }}" class="font-semibold text-teal-600">Read &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
