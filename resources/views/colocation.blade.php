<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Appartement Grand-Place</h2>

            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">Quitter la coloc</button>
                <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-sm text-rose-600 hover:bg-rose-50 transition-all">annuler la coloc</button>
                <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">créer une colocation</button>
                <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-indigo-700 transition-all">+ Ajouter une dépense</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-gray-800">Filtrer par mois</h4>
                    <select class="w-full bg-gray-50 border-gray-200 rounded-xl py-2 px-3 text-sm font-medium focus:ring-indigo-500">
                        <option>Tous les mois</option>
                        <option>.</option>

                    </select>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h4 class="font-bold mb-4 text-gray-800">Membres</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">

                                <span class="text-sm font-bold text-gray-700"></span>
                            </div>
                            <span class="text-xs font-black text-emerald-500">reputaion</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center font-bold text-xs uppercase">AM</div>
                                <span class="text-sm font-medium text-gray-700">Alex M.</span>
                            </div>
                            <span class="text-xs font-black text-rose-400">-2</span>
                        </div>
                    </div>
                    <button class="w-full mt-6 py-2 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 text-xs font-bold hover:border-indigo-300 hover:text-indigo-400 transition-all">
                        + Inviter un membre
                    </button>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <div class="bg-amber-50 border border-amber-100 p-6 rounded-3xl">
                    <h4 class="font-bold text-amber-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Remboursements suggérés
                    </h4>
                    <div class="space-y-3">
                        <div class="bg-white p-4 rounded-2xl flex justify-between items-center shadow-sm">
                            <p class="text-sm font-medium text-gray-700">
                                <span class="font-black">Alex M.</span> doit <span class="font-black text-indigo-600">45.00 €</span> à <span class="font-black">Moi</span>
                            </p>
                            <button class="text-xs bg-emerald-500 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-emerald-600 transition-all">
                                Marquer payé
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-50 uppercase text-xs font-black tracking-widest text-gray-400">
                        Historique des dépenses - Octobre
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>