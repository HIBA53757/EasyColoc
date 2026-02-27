<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

@if(isset($colocation) && $colocation)

    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>

            @if(request()->route('id') && request()->route('id') != $activeColocId)
                <a href="{{ route('Colocation', $activeColocId) }}" class="text-xs font-bold text-indigo-600 hover:underline mb-2 block">
                    ← Retour à ma colocation active
                </a>
            @endif
            
            <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                {{ $colocation->name }}
             
                @if(request()->route('id') && request()->route('id') != $activeColocId)
                    <span class="text-xs font-bold uppercase tracking-widest bg-amber-100 text-amber-700 px-2 py-1 rounded-lg">Archive</span>
                @else
                    <span class="text-xs font-bold uppercase tracking-widest bg-green-100 text-green-700 px-2 py-1 rounded-lg">Actif</span>
                @endif
            </h2>
        </div>

        <div class="flex gap-2">
       
            @if(request()->route('id') == $activeColocId || (!request()->route('id') && $activeColocId))
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                    Quitter
                </button>

                @can('delete', $colocation)
                    <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-400 hover:bg-gray-50">
                        Annuler
                    </button>
                @endcan

                <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">
                    + Ajouter une dépense
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- LEFT SIDEBAR --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Members List --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h4 class="font-bold mb-4 text-gray-800 text-sm uppercase tracking-widest">Membres</h4>
                <div class="space-y-4">
                   
                    @foreach($colocation->memberships as $membership)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-indigo-50 text-indigo-700 rounded-full flex items-center justify-center font-bold text-xs uppercase">
                                    {{ strtoupper(substr($membership->user->name, 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $membership->user->name }}</span>
                            </div>
                            <span class="text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                {{ $membership->role }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Invitation Modal (Only for ACTIVE owner) --}}
                @if(request()->route('id') == $activeColocId)
                    @can('invite', $colocation)
                        <div x-data="{ open: false }">
                            <button @click="open = true" 
                                    class="w-full mt-6 py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs font-bold text-center hover:border-indigo-300 hover:text-indigo-500 transition-all">
                                + Inviter un membre
                            </button>

                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak x-transition>
                                <div @click.away="open = false" class="bg-white w-full max-w-md p-8 rounded-3xl shadow-2xl">
                                    <h3 class="text-xl font-black mb-2">Inviter un colocataire</h3>
                                    <p class="text-sm text-gray-500 mb-6">L'invité recevra un accès à cette colocation.</p>
                                    <form action="{{ route('colocation.invite', $colocation) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Email</label>
                                            <input type="email" name="email" required class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500 shadow-sm">
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" @click="open = false" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm">Annuler</button>
                                            <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg">Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endif
            </div>

           
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h4 class="font-bold mb-4 text-gray-800 text-sm uppercase tracking-widest">Mes Colocations</h4>
                <div class="space-y-3">
                    {{-- Active Link --}}
                    @if($activeColocId)
                    <a href="{{ route('Colocation', $activeColocId) }}" 
                       class="flex items-center justify-between p-3 rounded-xl border transition-all {{ request()->route('id') == $activeColocId ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white border-gray-100 text-gray-700 hover:bg-gray-50' }}">
                        <span class="text-sm font-bold">🏠 Actuelle</span>
                    </a>
                    @endif

                    <div class="pt-2 pb-1 border-t border-gray-50 mt-4">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Historique</span>
                    </div>

                    @forelse($history as $oldMembership)
                        <a href="{{ route('Colocation', $oldMembership->colocation_id) }}" 
                           class="flex items-center justify-between p-3 rounded-xl border transition-all {{ request()->route('id') == $oldMembership->colocation_id ? 'bg-indigo-600 border-indigo-600 text-white shadow-md' : 'bg-white border-gray-100 text-gray-500 hover:bg-gray-50' }}">
                            <span class="text-xs font-medium">{{ $oldMembership->colocation->name }}</span>
                            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @empty
                        <p class="text-[10px] text-gray-400 italic">Aucun historique.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <span class="uppercase text-xs font-black tracking-widest text-gray-400">Dépenses</span>
                    <span class="text-xs font-bold text-gray-400">{{ $colocation->name }}</span>
                </div>
                <div class="p-6 text-center py-16">
                    <p class="text-gray-400 text-sm italic">Module de dépenses en cours de développement...</p>
                </div>
            </div>
        </div>
    </div>

@else
    {{-- Empty State --}}
    <div class="text-center mt-24">
        <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-indigo-50 rounded-full">
            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </div>
        <h2 class="text-3xl font-black mb-2 text-slate-900">Bienvenue !</h2>
        <p class="text-gray-500 mb-8 max-w-sm mx-auto">Vous ne faites partie d'aucune colocation pour le moment.</p>
        <a href="{{ route('colocation.create') }}"
           class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl hover:shadow-indigo-200 transition-all inline-block">
            Créer ma colocation
        </a>
    </div>
@endif

</div>
</x-app-layout>