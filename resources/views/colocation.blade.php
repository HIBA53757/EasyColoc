<x-app-layout>
<div class="max-w-5xl mx-auto p-6">
    
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-4xl font-black text-slate-900">Mes Colocations</h2>
        @if($canCreate)
            <a href="{{ route('colocation.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition-all">
                + Créer une colocation
            </a>
        @endif
    </div>


    @if($pendingInvitations->isNotEmpty())
        <div class="grid gap-4 mb-10">
            @foreach($pendingInvitations as $invite)
                <div class="bg-indigo-600 p-6 rounded-3xl text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h4 class="font-black text-lg">🏠 Invitation reçue !</h4>
                        <p class="text-indigo-100 text-sm">Vous êtes invité à rejoindre : <strong>{{ $invite->colocation->name }}</strong></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('invitation.accept', $invite->token) }}" class="bg-white text-indigo-600 px-6 py-2 rounded-xl font-black text-sm hover:bg-indigo-50 transition-all">
                            Accepter
                        </a>
                        <a href="{{ route('invitation.refuse', $invite->token) }}" class="bg-indigo-500 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-400 border border-indigo-400 transition-all">
                            Refuser
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mb-10">
        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Colocation Actuelle</h3>
        @if($active)
            <a href="{{ route('Colocation', $active->colocation_id) }}" class="group block bg-white border-2 border-indigo-600 p-8 rounded-3xl shadow-xl hover:scale-[1.01] transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-indigo-600 font-black text-xs uppercase">En cours</span>
                        <h4 class="text-2xl font-black text-slate-900">{{ $active->colocation->name }}</h4>
                        <p class="text-slate-500">{{ $active->colocation->memberships->count() }} membres</p>
                    </div>
                    <div class="h-12 w-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"></path></svg>
                    </div>
                </div>
            </a>
        @else
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 p-8 rounded-3xl text-center">
                <p class="text-slate-400 font-medium">Vous n'avez aucune colocation active pour le moment.</p>
            </div>
        @endif
    </div>
    @if($history->isNotEmpty())
        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Anciennes Colocations</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($history as $membership)
                    <a href="{{ route('Colocation', $membership->colocation_id) }}" class="bg-white border border-gray-100 p-6 rounded-3xl hover:bg-gray-50 transition-all flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-slate-800">{{ $membership->colocation->name }}</h4>
                            <p class="text-xs text-slate-400 italic">Quittée le {{ $membership->left_at->format('d/m/Y') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2"></path></svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
</x-app-layout>