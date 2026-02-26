<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

@if(isset($colocation) && $colocation)

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                {{ $colocation->name }}
            </h2>
        </div>

        <div class="flex gap-2">
    
            <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-400 cursor-not-allowed">
                Quitter la coloc (Indisponible)
            </button>

           
            @can('delete', $colocation)
            <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-gray-400 cursor-not-allowed">
                Annuler la coloc (Indisponible)
            </button>
            @endcan

           
            <button class="px-6 py-2 bg-indigo-400 text-white rounded-xl font-bold text-sm shadow-lg cursor-not-allowed">
                + Ajouter une dépense
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h4 class="font-bold mb-4 text-gray-800">Membres</h4>
                <div class="space-y-4">
            
                    @foreach($colocation->memberships as $membership)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center font-bold text-xs uppercase">
                                    {{ strtoupper(substr($membership->user->name, 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $membership->user->name }}</span>
                            </div>
                            <span class="text-xs font-black text-indigo-600">{{ $membership->role }}</span>
                        </div>
                    @endforeach
                </div>

                @can('invite', $colocation)
                <div class="w-full mt-6 py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs font-bold text-center">
                    + Inviter un membre (Indisponible)
                </div>
                @endcan
            </div>
        </div>

     
        <div class="lg:col-span-3 space-y-6">
          
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b uppercase text-xs font-black tracking-widest text-gray-400">
                    Historique des dépenses
                </div>
                <div class="p-6">
                    <p class="text-gray-500 text-sm">Les dépenses apparaîtront ici une fois le module prêt.</p>
                </div>
            </div>
        </div>
    </div>

@else

    <div class="text-center mt-24">
        <h2 class="text-3xl font-black mb-6">Vous n'avez pas encore de colocation</h2>
        <a href="{{ route('colocation.create') }}"
           class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg">
            Créer une colocation
        </a>
    </div>
@endif

</div>
</x-app-layout>