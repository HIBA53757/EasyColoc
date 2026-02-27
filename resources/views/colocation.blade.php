<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

  
    @if(isset($pendingInvitations) && $pendingInvitations->isNotEmpty())
        @foreach($pendingInvitations as $invite)
            <div class="mb-8 bg-indigo-600 p-6 rounded-3xl text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h4 class="font-black text-lg">🏠 Invitation reçue !</h4>
                    <p class="text-indigo-100 text-sm">Vous êtes invité à rejoindre : <strong>{{ $invite->colocation->name }}</strong></p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('invitation.accept', $invite->token) }}" class="bg-white text-indigo-600 px-6 py-2 rounded-xl font-black text-sm hover:bg-indigo-50 transition-all">Accepter</a>
                    <a href="{{ route('invitation.refuse', $invite->token) }}" class="bg-indigo-500 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-400 border border-indigo-400">Refuser</a>
                </div>
            </div>
        @endforeach
    @endif

    @if(isset($colocation) && $colocation)
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                @if(request()->route('id') && request()->route('id') != $activeColocId)
                    <a href="{{ route('Colocation', $activeColocId) }}" class="text-xs font-bold text-indigo-600 hover:underline mb-2 block">← Retour à ma colocation active</a>
                @endif
                <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    {{ $colocation->name }}
                    <span class="text-xs font-bold uppercase tracking-widest {{ request()->route('id') == $activeColocId || !$activeColocId ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} px-2 py-1 rounded-lg">
                        {{ request()->route('id') == $activeColocId || !$activeColocId ? 'Actif' : 'Archive' }}
                    </span>
                </h2>
            </div>
            
            <div class="flex gap-2">
    {{-- On vérifie qu'on regarde bien la colocation active --}}
    @if(request()->route('id') == $activeColocId || (!request()->route('id') && $activeColocId))
        
        {{-- BOUTON QUITTER : Apparaît seulement pour les membres (via leave policy) --}}
        @can('leave', $colocation)
            <form action="{{ route('colocation.leave', $colocation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter cette colocation ?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white border border-rose-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                    Quitter la colocation
                </button>
            </form>
        @endcan

        {{-- BOUTON ANNULER : Apparaît seulement pour l'Owner (via delete policy) --}}
        @can('delete', $colocation)
            <form action="{{ route('colocation.destroy', $colocation) }}" method="POST" onsubmit="return confirm('Attention : Cela supprimera toutes les données de la colocation !')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-500 hover:bg-rose-600 hover:text-white transition-all">
                    Annuler la colocation
                </button>
            </form>
        @endcan

        {{-- BOUTON DÉPENSE : Toujours visible pour les membres actifs --}}
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">
            + Ajouter une dépense
        </button>
    @endif
</div>



            
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- SIDEBAR GAUCHE --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-gray-800 text-sm uppercase tracking-widest">Membres</h4>
                    <div class="space-y-4">
                        @foreach($colocation->memberships as $membership)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 bg-indigo-50 text-indigo-700 rounded-full flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($membership->user->name, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $membership->user->name }}</span>
                                </div>
                                <span class="text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $membership->role }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Modal d'invitation (Owner uniquement) --}}
                    @can('invite', $colocation)
                        <div x-data="{ open: false }" class="mt-6 pt-6 border-t border-gray-100">
                            <button @click="open = true" class="w-full py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs font-bold hover:border-indigo-300 hover:text-indigo-600 transition-all">+ Inviter un membre</button>
                            
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
                                <div @click.away="open = false" class="bg-white w-full max-w-md p-8 rounded-3xl shadow-2xl">
                                    <h3 class="text-xl font-black mb-4">Inviter un colocataire</h3>
                                    <form action="{{ route('colocation.invite', $colocation) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-xs font-black uppercase text-gray-400 mb-2">Email</label>
                                            <input type="email" name="email" required class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500">
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" @click="open = false" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm">Annuler</button>
                                            <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm">Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            {{-- LIENS DE TEST --}}
                            <div class="mt-4 space-y-2">
                                @php $pendingInvites = \App\Models\Invitation::where('colocation_id', $colocation->id)->where('status', 'pending')->get(); @endphp
                                @foreach($pendingInvites as $inv)
                                    <div class="p-2 bg-slate-50 rounded-lg">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase">Lien pour {{ $inv->email }} :</p>
                                        <input type="text" readonly value="{{ route('invitation.accept', $inv->token) }}" class="w-full text-[10px] bg-transparent border-none p-0 text-indigo-600 focus:ring-0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endcan
                </div>

                {{-- Sélecteur de colocation --}}
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-gray-800 text-sm uppercase tracking-widest">Mes Colocations</h4>
                    <div class="space-y-3">
                        @if($activeColocId)
                            <a href="{{ route('Colocation', $activeColocId) }}" 
                               class="flex items-center p-3 rounded-xl border transition-all {{ request()->route('id') == $activeColocId ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-gray-100 text-gray-700' }}">
                                <span class="text-sm font-bold">🏠 Actuelle</span>
                            </a>
                        @endif
                        @foreach($history as $old)
                            <a href="{{ route('Colocation', $old->colocation_id) }}" 
                               class="flex items-center p-3 rounded-xl border transition-all {{ request()->route('id') == $old->colocation_id ? 'bg-indigo-600 text-white' : 'text-gray-500 border-gray-100' }}">
                                <span class="text-xs font-medium">{{ $old->colocation->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 text-center py-24">
                    <p class="text-gray-400 italic">Module de dépenses en cours de développement...</p>
                </div>
            </div>
        </div>

    @else
   
        <div class="text-center mt-24">
            <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-indigo-50 rounded-full">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <h2 class="text-3xl font-black mb-2 text-slate-900">Bienvenue !</h2>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto">Vous ne faites partie d'aucune colocation. Créez la vôtre ou attendez une invitation.</p>
            <a href="{{ route('colocation.create') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl hover:bg-indigo-700 transition-all inline-block">
                Créer ma colocation
            </a>
        </div>
    @endif

</div>
</x-app-layout>