<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

    {{-- 1. NAVIGATION --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('Colocation') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
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
        
        <div class="flex items-center gap-3">
            @if(!$isHistory)
                
                {{-- BOUTONS POUR LE PROPRIÉTAIRE UNIQUEMENT --}}
                @if(auth()->id() == $colocation->owner_id)
                    {{-- Inviter un membre --}}
                    <div x-data="{ openInvite: false }">
                        <button @click="openInvite = true" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-sm hover:bg-emerald-100 transition-all">
                            Inviter
                        </button>

                        <div x-show="openInvite" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak>
                            <div @click.away="openInvite = false" class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl">
                                <h3 class="text-2xl font-black mb-4">Inviter un colocataire</h3>
                                <form action="{{ route('colocation.invite', $colocation->id) }}" method="POST">
                                    @csrf
                                    <input type="email" name="email" placeholder="Email du futur coloc" required class="w-full bg-slate-50 border-none rounded-xl mb-4 focus:ring-2 focus:ring-indigo-500">
                                    <div class="flex gap-3">
                                        <button type="button" @click="openInvite = false" class="flex-1 py-3 bg-slate-100 rounded-xl font-bold">Annuler</button>
                                        <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold">Envoyer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- annuler la coloc --}}
                    <form action="{{ route('colocation.destroy', $colocation->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette colocation ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl font-bold text-sm hover:bg-rose-100 transition-all">
                            annuler
                        </button>
                    </form>
                @endif

                {{-- BOUTON QUITTER (Pour tout le monde sauf si tu veux restreindre aux non-owners) --}}
                @if(auth()->id() != $colocation->owner_id)
                    <form action="{{ route('colocation.leave', $colocation->id) }}" method="POST" onsubmit="return confirm('Quitter cette colocation ?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white border border-rose-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                            Quitter
                        </button>
                    </form>
                @endif

                {{-- AJOUTER UNE DÉPENSE --}}
                <div x-data="{ openExpense: false }">
                    <button @click="openExpense = true" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">
                        + Dépense
                    </button>

                    <div x-show="openExpense" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak>
                        <div @click.away="openExpense = false" class="bg-white w-full max-w-md p-8 rounded-[2.5rem] shadow-2xl text-left">
                            <h3 class="text-2xl font-black mb-6">Nouvelle dépense</h3>
                            <form action="{{ route('expenses.store', $colocation->id) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <input type="text" name="title" placeholder="Titre" required class="w-full bg-slate-50 border-none rounded-xl">
                                    <input type="number" step="0.01" name="amount" placeholder="Montant" required class="w-full bg-slate-50 border-none rounded-xl">
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-none rounded-xl">
                                    <select name="category_id" class="w-full bg-slate-50 border-none rounded-xl">
                                        @foreach(\App\Models\Category::all() as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
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

    {{-- 3. GRILLE PRINCIPALE (Soldes & Dépenses) --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- COLONNE GAUCHE : RÉSUMÉ DES SOLDES --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h4 class="font-black mb-6 text-gray-800 text-xs uppercase tracking-widest">Résumé des Soldes</h4>
                <div class="space-y-4">
                    @foreach($balances as $userId => $data)
                        <div class="flex items-center justify-between p-2 {{ auth()->id() == $userId ? 'bg-indigo-50 rounded-xl' : '' }}">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold {{ $data['is_left'] ? 'text-gray-400 line-through' : 'text-slate-700' }}">
                                    {{ $data['user']->name }}
                                </span>
                                <span class="text-[10px] text-gray-400 uppercase font-black">
                                    {{ $data['balance'] >= 0 ? 'À recevoir' : 'À rembourser' }}
                                </span>
                            </div>
                            <span class="text-sm font-black {{ $data['balance'] >= 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $data['balance'] >= 0 ? '+' : '' }}{{ number_format($data['balance'], 2) }} dh
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE : HISTORIQUE --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-slate-50/50">
                    <h3 class="font-black text-slate-900 uppercase text-xs tracking-widest">Dernières Dépenses</h3>
                </div>
                @if($colocation->expenses->isEmpty())
                    <div class="py-20 text-center text-slate-400 font-medium">Aucune dépense enregistrée.</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($colocation->expenses()->orderBy('date', 'desc')->get() as $expense)
                            <div class="p-6 flex justify-between items-center hover:bg-slate-50 transition-all">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $expense->title }}</p>
                                    <p class="text-xs text-slate-400">
                                        Payé par <span class="text-indigo-600">{{ $expense->payer->name }}</span> • {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-slate-900 text-lg">{{ number_format($expense->amount, 2) }} dh</p>
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