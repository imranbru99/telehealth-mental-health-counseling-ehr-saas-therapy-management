@extends('portal.layout', ['title' => 'Secure Messaging - Client Portal'])

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Secure Clinical Messaging</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Communicate directly and confidentially with your clinical care team and front desk.</p>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl flex items-center gap-3 text-xs text-amber-900">
        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span><strong>Urgent Notice:</strong> Messages are reviewed during regular business hours (Mon-Fri 8am-7pm). If you are experiencing a life-threatening psychological crisis, please call <strong>988</strong> immediately.</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Message History Thread -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between min-h-[450px]">
            <div>
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Conversation History</h2>

                @if($messages->isEmpty())
                    <div class="text-center py-16 text-slate-400 text-xs">
                        No previous messages in this thread. Send your first inquiry below.
                    </div>
                @else
                    <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2">
                        @foreach($messages as $msg)
                            @php
                                $isMe = ($msg->sender_id === auth()->id());
                            @endphp
                            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-md {{ $isMe ? 'bg-teal-600 text-white rounded-tr-none' : 'bg-slate-100 text-slate-800 rounded-tl-none' }} p-4 rounded-2xl shadow-sm space-y-1">
                                    <div class="flex items-center justify-between gap-4 text-[10px] {{ $isMe ? 'text-teal-200' : 'text-slate-400' }}">
                                        <span class="font-bold">{{ $isMe ? 'You' : ($msg->sender->name ?? 'Clinical Team') }}</span>
                                        <span>{{ $msg->created_at->format('M d, h:i A') }}</span>
                                    </div>
                                    @if($msg->subject)
                                        <div class="font-bold text-xs {{ $isMe ? 'text-teal-100' : 'text-slate-900' }}">{{ $msg->subject }}</div>
                                    @endif
                                    <p class="text-xs leading-relaxed">{{ $msg->body }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Compose Box -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Send New Message</h2>

            <form action="{{ route('portal.messages.send') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="subject" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Subject</label>
                    <input 
                        type="text" 
                        name="subject" 
                        id="subject" 
                        required 
                        placeholder="e.g. Question regarding therapy homework / scheduling" 
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-teal-500 outline-none"
                    >
                </div>

                <div>
                    <label for="body" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Message Body</label>
                    <textarea 
                        name="body" 
                        id="body" 
                        rows="6" 
                        required 
                        placeholder="Type your message confidentially here..." 
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-teal-500 outline-none"
                    ></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs uppercase tracking-wider rounded-xl transition shadow">
                    Send Confidential Message &rarr;
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
