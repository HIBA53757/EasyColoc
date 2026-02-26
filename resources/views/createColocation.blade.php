<x-app-layout>
    <div class="max-w-xl mx-auto mt-20 bg-white p-8 rounded-3xl shadow">

        <h2 class="text-2xl font-bold mb-6">Créer une colocation</h2>

        <form method="POST" action="{{ route('colocation.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                    Nom de la colocation
                </label>
                <input type="text"
                       name="name"
                       class="w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                   descreption
                </label>
                <input type="text"
                       name="descreption"
                       class="w-full border rounded-xl px-4 py-2"
                       required>
            </div>

            <button class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold">
                Créer
            </button>
        </form>

    </div>
</x-app-layout>