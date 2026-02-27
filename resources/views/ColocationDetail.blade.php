<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <a href="{{ route('Colocation') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:gap-2 transition-all">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2"></path></svg>
            Retour à la liste
        </a>

        @if(request()->route('id') && $activeColocId && request()->route('id') != $activeColocId)
            <a href="{{ route('Colocation', $activeColocId) }}" class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full hover:bg-amber-100 transition-all">
                Voir ma colocation active
            </a>
        @endif
    </div>

    @if(isset($pendingInvitations) && $pendingInvitations->isNotEmpty())
        @foreach($pendingInvitations as $invite)
            <div class="mb-6 bg-indigo-600 p-4 rounded-2xl text-white shadow-lg flex justify-between items-center">
                <p class="text-sm font-medium">🏠 Invitation en attente pour <b>{{ $invite->colocation->name }}</b></p>
                <a href="{{ route('invitation.accept', $invite->token) }}" class="bg-white text-indigo-600 px-4 py-1.5 rounded-lg font-bold text-xs">Voir</a>
            </div>
        @endforeach
    @endif


        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    {{ $colocation->name }}
                    <span class="text-xs font-bold uppercase tracking-widest {{ $colocation->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }} px-2 py-1 rounded-lg">
                        {{ $colocation->status === 'active' ? 'Active' : 'Annulée' }}
                    </span>
                    @if($isHistory && $colocation->status === 'active')
                         <span class="text-xs font-bold uppercase tracking-widest bg-amber-100 text-amber-700 px-2 py-1 rounded-lg">Ancienne</span>
                    @endif
                </h2>

            </div>
            
            <div class="flex gap-2">
             
                @if(!$isHistory && $colocation->status === 'active')
                    @can('leave', $colocation)
                        <form action="{{ route('colocation.leave', $colocation) }}" method="POST" onsubmit="return confirm('Quitter cette colocation ?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white border border-rose-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                                Quitter
                            </button>
                        </form>
                    @endcan

                    @can('delete', $colocation)
                        <form action="{{ route('colocation.destroy', $colocation) }}" method="POST" onsubmit="return confirm('Annuler et archiver cette colocation ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-400 hover:bg-rose-600 hover:text-white transition-all">
                                Annuler la colocation
                            </button>
                        </form>
                    @endcan

                    <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">
                        + Ajouter une dépense
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-gray-800 text-sm uppercase tracking-widest">Membres</h4>
                    <div class="space-y-4">
                        @foreach($colocation->memberships as $membership)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                   
                                    <span class="text-sm font-medium {{ $membership->left_at ? 'text-gray-400 line-through' : 'text-gray-700' }}">
                                        {{ $membership->user->name }}
                                    </span>
                                </div>
                                <span class="text-[10px] font-black uppercase {{ $membership->role === 'owner' ? 'text-amber-600' : 'text-indigo-600' }}">
                                    {{ $membership->role }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                       @if($colocation->status === 'active' && !$isHistory)
                        @can('invite', $colocation)
                            <div x-data="{ open: false }" class="mt-6 pt-6 border-t border-gray-100">
                                <button @click="open = true" class="w-full py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs font-bold hover:border-indigo-300 hover:text-indigo-600 transition-all">+ Inviter</button>
                                
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
                            </div>
                        @endcan
                    @endif
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 text-center py-24">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-gray-400 font-medium">Historique des dépenses</p>
                    <p class="text-gray-300 text-sm mt-1">Bientôt disponible...</p>
                </div>
            </div>
        </div>
   

</div>
</x-app-layout>