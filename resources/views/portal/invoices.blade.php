@extends('portal.layout', ['title' => 'Invoices & Billing - Client Portal'])

@section('portal_content')
<div class="space-y-6" x-data="{ payModal: false, selectedInvoice: null }">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Invoices & Billing Receipts</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your therapy billing records, settle pending balances, and download official Unicode PDF receipts.</p>
        </div>
    </div>

    @if($invoices->isEmpty())
        <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center">
            <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">No Invoices on Record</h3>
            <p class="text-xs text-slate-500 mt-1">Invoices generated for your therapy sessions and assessments will be displayed here.</p>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Invoice #</th>
                            <th class="py-4 px-6">Issue / Due Date</th>
                            <th class="py-4 px-6">Amount</th>
                            <th class="py-4 px-6">Payment Status</th>
                            <th class="py-4 px-6 text-right">Receipt / Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @foreach($invoices as $inv)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-slate-900">{{ $inv->invoice_number }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-slate-800">{{ $inv->issue_date ? \Carbon\Carbon::parse($inv->issue_date)->format('M d, Y') : $inv->created_at->format('M d, Y') }}</div>
                                    <div class="text-[11px] text-slate-400">Due: {{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('M d, Y') : 'Due Upon Receipt' }}</div>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    ${{ number_format($inv->total, 2) }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $inv->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($inv->payment_status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Download PDF Receipt -->
                                        <a href="{{ route('invoices.pdf', $inv->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:text-teal-700 hover:border-teal-300 font-semibold text-[11px] transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            PDF Receipt
                                        </a>

                                        @if($inv->payment_status !== 'paid')
                                            <button 
                                                type="button" 
                                                @click="selectedInvoice = { id: {{ $inv->id }}, number: '{{ $inv->invoice_number }}', total: '{{ number_format($inv->total, 2) }}' }; payModal = true;"
                                                class="px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold text-[11px] transition shadow-sm"
                                            >
                                                Pay Now
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        </div>
    @endif

    <!-- Payment Simulation Modal -->
    <div 
        x-show="payModal" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
    >
        <div 
            @click.away="payModal = false" 
            class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 sm:p-8 shadow-2xl relative"
        >
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Settle Invoice Balance</h3>
                <button type="button" @click="payModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <template x-if="selectedInvoice">
                <form :action="'/portal/invoices/' + selectedInvoice.id + '/pay'" method="POST" class="space-y-4">
                    @csrf
                    <div class="p-3 bg-slate-50 rounded-xl text-xs space-y-1">
                        <div class="flex justify-between text-slate-600">
                            <span>Invoice Number:</span>
                            <span class="font-mono font-bold text-slate-900" x-text="selectedInvoice.number"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Due:</span>
                            <span class="font-bold text-teal-700 text-sm" x-text="'$' + selectedInvoice.total"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Select Payment Method</label>
                        <select name="payment_method" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs focus:border-teal-500 outline-none">
                            <option value="credit_card">Credit / Debit Card (Stripe Test Gateway)</option>
                            <option value="bank_transfer">HSA / FSA Healthcare Account</option>
                            <option value="cash">Clinical Office Cash / Check</option>
                        </select>
                    </div>

                    <div class="p-3 bg-teal-50 rounded-xl border border-teal-100 text-[11px] text-teal-800">
                        ⚡ <strong>Test Mode Sandbox:</strong> Clicking "Confirm Payment" will immediately mark this invoice as paid and issue a verified receipt.
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="payModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold uppercase tracking-wider transition shadow">Confirm Payment</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection
