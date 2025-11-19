@extends('layouts.app')

@section('title', 'Recherche de tâches')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-dark-bg py-12 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-secondary dark:text-primary mb-2">Recherche de tâches</h1>
            <p class="text-gray-600 dark:text-dark-muted">Trouvez rapidement vos tâches par nom, projet, sprint ou epic.</p>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border p-6">
            @livewire('task-search')
        </div>
    </div>
</div>
@endsection
