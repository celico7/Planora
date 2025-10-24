@extends('layouts.app')
@section('title', $epic->nom)
@section('content')

<div class="max-w-2xl mx-auto p-6 rounded bg-white shadow">
    <h1 class="font-bold text-2xl mb-2">{{ $epic->nom }}</h1>
    <p class="mb-3">{{ $epic->description }}</p>
    <div>
        <span class="mr-3">Début : {{ $epic->begining }}</span>
        <span>Fin : {{ $epic->end }}</span>
    </div>
    <div class="mt-2">
        <span class="inline-block rounded px-2 py-1 bg-[#0cbaba]/20 text-[#0cbaba]">{{ ucfirst($epic->statut) }}</span>
    </div>
    <a href="{{ route('projects.show', $project) }}" class="inline-block mt-6 hover:underline text-[#0cbaba]">Retour au projet</a>
</div>
@endsection
