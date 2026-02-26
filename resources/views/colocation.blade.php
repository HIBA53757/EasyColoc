<x-app-layout>
<div class="max-w-6xl mx-auto p-6">

@auth

{{-- USER WITHOUT COLOCATION --}}
@if(!auth()->user()->hasActiveMembership())

<div class="text-center mt-24">
    <h2 class="text-3xl font-black text-slate-900 mb-6">
        Vous n'avez pas encore de colocation
    </h2>

    <a href="{{ route('colocation.create') }}"
       class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all">
        Créer une colocation
    </a>
</div>

@else

{{-- USER HAS COLOCATION — SHOW DATABASE INFO ONLY --}}

<div class="bg-white p-6 rounded-2xl shadow-sm border">

<h2 class="text-2xl font-black mb-4">
{{ $colocation->name ?? 'Colocation' }}
</h2>

@if(isset($colocation->description))
<p class="text-gray-500">
{{ $colocation->description }}
</p>
@endif

</div>

@endif

@endauth

</div>
</x-app-layout>