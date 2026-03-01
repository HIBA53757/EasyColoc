<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

    {{-- 1. NAVIGATION --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('Colocation') }}" class="inline-flex items-center text-sm font-bold text-indigo-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2"></path></svg>
            Retour à la liste
        </a>
    </div>

    {{-- 2. HEADER --}}
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                {{ $colocation->name }}
            </h2>
        </div>
        
        <div class="flex gap-3">
            @if(!$isHistory && $colocation->status === 'active')
                
                @can('leave', $colocation)
                    <form action="{{ route('colocation.leave', $colocation->id) }}" method="POST" onsubmit="return confirm('Quitter cette colocation ?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white border border-rose-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                            Quitter
                        </button>
                    </form>
                @endcan

               
                <div x-data="{ openExpense: false }">
                    <button @click="openExpense = true" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">
                        + Ajouter une dépense
                    </button>

                    <div x-show="openExpense" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak>
                        <div @click.away="openExpense = false" class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl text-left">
                            <h3 class="text-2xl font-black mb-6">Nouvelle dépense</h3>
                            
                            <form action="{{ route('expenses.store', $colocation->id) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Description</label>
                                        <input type="text" name="title" required class="w-full bg-slate-50 border-none rounded-xl">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Montant (€)</label>
                                            <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border-none rounded-xl">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Date</label>
                                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border-none rounded-xl">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Catégorie</label>
                                        <select name="category_id" required class="w-full bg-slate-50 border-none rounded-xl">
                                            @foreach(\App\Models\Category::all() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-6">
                                    <button type="button" @click="openExpense = false" class="flex-1 py-3 bg-slate-100 rounded-xl font-bold">Annuler</button>
                                    <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h4 class="font-black mb-6 text-gray-800 text-xs uppercase tracking-widest">Résumé des Soldes</h4>
                <div class="space-y-4">
                    @foreach($balances as $data)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold {{ $data['is_left'] ? 'text-gray-400 line-through' : 'text-slate-700' }}">
                                {{ $data['user']->name }}
                            </span>
                            <span class="text-sm font-black {{ $data['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $data['balance'] >= 0 ? '+' : '' }}{{ number_format($data['balance'], 2) }} €
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-black text-slate-900 uppercase text-xs tracking-widest">Dernières Dépenses</h3>
                </div>
                @if($colocation->expenses->isEmpty())
                    <div class="py-20 text-center text-gray-400">Aucune dépense.</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($colocation->expenses()->orderBy('date', 'desc')->get() as $expense)
                            <div class="p-6 flex justify-between items-center hover:bg-slate-50 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($expense->title, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $expense->title }}</p>
                                        <p class="text-xs text-slate-400">Payé par {{ $expense->payer->name }} • {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-slate-900">{{ number_format($expense->amount, 2) }} €</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>