@extends('layouts.app')
@section('title', $epic->nom)
@section('content')

<div class="max-w-2xl mx-auto p-6 rounded bg-white dark:bg-dark-card shadow-lg dark:shadow-none border dark:border-dark-border">
    <h1 class="font-bold text-2xl mb-2 text-gray-800 dark:text-dark-text">{{ $epic->nom }}</h1>
    <p class="mb-3 text-gray-600 dark:text-dark-muted">{{ $epic->description }}</p>
    <div class="text-gray-700 dark:text-dark-text mb-3">
        <span class="mr-3"><i class="bi bi-calendar-event text-primary mr-1"></i>Début : {{ $epic->begining }}</span>
        <span><i class="bi bi-calendar-check text-primary mr-1"></i>Fin : {{ $epic->end }}</span>
    </div>
    <div class="mt-2">
        @php
            $statusBg = match($epic->statut) {
                'prévu' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                'en cours' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                'terminé' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                default => 'bg-[#0cbaba]/20 dark:bg-primary/30 text-[#0cbaba] dark:text-primary'
            };
        @endphp
        <span class="inline-block rounded px-3 py-1 {{ $statusBg }} font-semibold">{{ ucfirst($epic->statut) }}</span>
    </div>
    <a href="{{ route('projects.show', $project) }}" class="inline-block mt-6 hover:underline text-[#0cbaba] dark:text-primary font-semibold">
        ← Retour au projet
    </a>
</div>
@endsection
