@extends('layouts.app')

@section('title', 'Tâches du Sprint')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Tâches du sprint : {{ $sprint->nom }}</h1>

    <p class="mb-4">
        Projet : <strong>{{ $sprint->project->nom }}</strong>
    </p>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('tasks.create', ['project' => $sprint->project->id, 'sprint' => $sprint->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600">Créer une tâche</a>

    @if($tasks->isEmpty())
        <p>Aucune tâche pour ce sprint.</p>
    @else
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Nom</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Responsable</th>
                    <th class="border px-4 py-2">Échéance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td class="border px-4 py-2">{{ $task->nom }}</td>
                    <td class="border px-4 py-2">{{ $task->description }}</td>
                    <td class="border px-4 py-2">{{ $task->statut }}</td>
                    <td class="border px-4 py-2">{{ $task->responsable->name ?? 'Non attribué' }}</td>
                    <td class="border px-4 py-2">{{ $task->echeance }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
