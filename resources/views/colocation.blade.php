<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        @auth

            
            @if(!auth()->user()->hasActiveMembership())

                <div class="text-center mt-24">
                    <h2 class="text-3xl font-black text-slate-900 mb-6">
                        Vous n'avez pas encore de colocation
                    </h2>

                    <a href=""
                       class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all">
                        Créer une colocation
                    </a>
                </div>

            @else

           

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ $colocation->name }}
                    </h2>
                </div>

                <div class="flex gap-2">

        
                    @can('leave', $colocation)
                        <form method="POST" action="{{ route('colocations.leave', $colocation) }}">
                            @csrf
                            <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                                Quitter la coloc
                            </button>
                        </form>
                    @endcan

                  
                    @can('delete', $colocation)
                        <form method="POST" action="{{ route('colocations.destroy', $colocation) }}">
                            @csrf
                            @method('DELETE')
                            <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">
                                Annuler la coloc
                            </button>
                        </form>
                    @endcan

                    @can('create', [App\Models\Expense::class, $colocation])
                        <a href="{{ route('expenses.create', $colocation) }}"
                           class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">
                            + Ajouter une dépense
                        </a>
                    @endcan

                </div>
            </div>


            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            
                <div class="lg:col-span-1 space-y-6">

                 
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h4 class="font-bold mb-4 text-gray-800">Filtrer par mois</h4>
                        <select class="w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm font-medium">
                            <option>Tous les mois</option>
                        </select>
                    </div>

                  
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h4 class="font-bold mb-4 text-gray-800">Membres</h4>

                        <div class="space-y-4">
                            @foreach($colocation->memberships as $membership)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center font-bold text-xs uppercase">
                                            {{ strtoupper(substr($membership->user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ $membership->user->name }}
                                        </span>
                                    </div>
                                    <span class="text-xs font-black text-indigo-600">
                                        {{ $membership->role }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                     
                        @can('invite', $colocation)
                            <a href="{{ route('invitations.create', $colocation) }}"
                               class="w-full mt-6 py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs font-bold text-center block hover:border-indigo-300 hover:text-indigo-400 transition-all">
                                + Inviter un membre
                            </a>
                        @endcan
                    </div>

                </div>


             
                <div class="lg:col-span-3 space-y-6">

                 
                    <div class="bg-amber-50 border border-amber-100 p-6 rounded-3xl">
                        <h4 class="font-bold text-amber-800 mb-4">
                            Remboursements suggérés
                        </h4>

                        
                        <div class="bg-white p-4 rounded-2xl shadow-sm">
                            <p class="text-sm font-medium text-gray-700">
                                Aucun remboursement pour le moment.
                            </p>
                        </div>
                    </div>


                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b uppercase text-xs font-black tracking-widest text-gray-400">
                            Historique des dépenses
                        </div>

                        <div class="p-6">
                            @forelse($colocation->expenses as $expense)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="font-medium">
                                        {{ $expense->title }}
                                    </span>
                                    <span class="font-bold">
                                        {{ $expense->amount }} €
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">
                                    Aucune dépense enregistrée.
                                </p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

            @endif
        @endauth

    </div>
</x-app-layout>