@extends('portal.layout', ['title' => 'Clinical Documents - Client Portal'])

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Clinical Documents & Records</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your intake summaries, assessment reports, and care documents released to you by your clinician.</p>
        </div>
    </div>

    <div class="bg-teal-50 border border-teal-200 p-4 rounded-2xl flex items-center gap-3 text-xs text-teal-900">
        <svg class="w-5 h-5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span><strong>HIPAA Confidentiality Note:</strong> Under psychological licensing standards, internal psychotherapy process notes are confidential to your licensed clinician. Documents shown here represent official clinical summaries and releases.</span>
    </div>

    @if($documents->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center">
            <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">No Documents Available</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">When your care team generates treatment plans, intake evaluations, or educational handouts, they will appear here.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($documents as $doc)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-teal-300 transition">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                                {{ strtoupper($doc->category ?? 'Document') }}
                            </span>
                            <span class="text-[11px] text-slate-400">{{ $doc->created_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base leading-snug">{{ $doc->title }}</h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                            {{ $doc->description ?? 'Official clinical file or practice agreement.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-mono text-slate-400">{{ $doc->file_size ? number_format($doc->file_size / 1024, 1) . ' KB' : 'Secure' }}</span>
                        <a href="{{ $doc->file_path ? asset($doc->file_path) : '#' }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-teal-600 hover:text-teal-800">
                            Download File &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
