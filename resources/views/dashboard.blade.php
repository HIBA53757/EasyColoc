<x-app-layout>
    <div class="min-h-screen bg-gray-50 flex">
        <main class="flex-1 p-8">
            <header class="flex justify-between items-center mb-10">
                <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium mb-1">Dépenses Globales ({{ now()->translatedFormat('F') }})</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalGlobalMonth, 2) }} dh</h3>
                </div>

                <div class="{{ $myBalance >= 0 ? 'bg-indigo-600' : 'bg-rose-600' }} p-6 rounded-3xl shadow-lg text-white transition-colors">
                    <p class="text-indigo-100 text-sm font-medium mb-1">Mon Solde</p>
                    <h3 class="text-3xl font-black">{{ $myBalance >= 0 ? '+' : '' }}{{ number_format($myBalance, 2) }} dh</h3>
                    <p class="text-[10px] mt-2 opacity-80 uppercase font-bold">
                        {{ $myBalance >= 0 ? 'On vous doit cet argent' : 'Vous devez cet argent' }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Réputation</p>
                        <h3 class="text-3xl font-black text-slate-900">{{ $reputation }}</h3>
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
                                <th class="px-6 py-4">Payé par</th>
                                <th class="px-6 py-4">Montant</th>
                                <th class="px-6 py-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentExpenses as $expense)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ $expense->title }}</td>
                                    <td class="px-6 py-4 flex items-center gap-2">
                                        <div class="h-7 w-7 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold">
                                            {{ strtoupper(substr($expense->payer->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium">{{ $expense->payer->name == auth()->user()->name ? 'Moi' : $expense->payer->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-black text-gray-900">{{ number_format($expense->amount, 2) }} dh</td>
                                    <td class="px-6 py-4 text-right text-gray-500 text-sm">
                                        {{ \Carbon\Carbon::parse($expense->date)->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">Aucune dépense enregistrée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>