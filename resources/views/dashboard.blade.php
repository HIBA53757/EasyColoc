<x-app-layout>
    
<div class="min-h-screen bg-gray-50 flex">
    

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-10">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
  
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-500 text-sm font-medium mb-1">Dépenses Globales (Mois)</p>
                <h3 class="text-3xl font-black text-gray-900">.</h3>
               
            </div>

            <div class="bg-indigo-600 p-6 rounded-3xl shadow-lg text-white">
                <p class="text-indigo-100 text-sm font-medium mb-1">Mon Solde</p>
                <h3 class="text-3xl font-black">.</h3>
     
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Score de Confiance</p>
                    <h3 class="text-3xl font-black text-slate-900">.</h3>
                </div>
                
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Toutes les dépenses récentes</h3>

            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Titre</th>
                            <th class="px-6 py-4">Catégorie</th>
                            <th class="px-6 py-4">Payé par</th>
                            <th class="px-6 py-4">Montant</th>
                            <th class="px-6 py-4 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-800">Courses Carrefour</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-xs font-bold">Alimentation</span></td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                
                                <span class="text-sm font-medium">.</span>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-900">.</td>
                            <td class="px-6 py-4 text-right text-gray-500 text-sm">24 Oct 2023</td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
    
</x-app-layout>
