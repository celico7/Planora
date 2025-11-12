@extends('layouts.app')

@section('title', $project->nom)

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded shadow-lg">

    {{-- Header Projet --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">{{ $project->nom }}</h1>
        <p class="text-gray-600 text-lg mb-4">{{ $project->description }}</p>
        <div class="flex gap-3">
            @can('updateSettings', $project)
            <a href="{{ route('projects.edit', $project->id) }}" class="rounded px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-900 border border-yellow-300 shadow-sm transition">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            @endcan
            @can('delete', $project)
            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Supprimer ce projet&nbsp;?');" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="rounded px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 border border-red-300 shadow-sm transition">
                    <i class="bi bi-trash"></i> Supprimer le projet
                </button>
            </form>
            @endcan
        </div>
    </div>

    {{-- Bouton Créer un sprint --}}
    @can('update', $project)
    <div class="mb-8">
        <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
           class="rounded px-4 py-2 mb-4 bg-primary text-white hover:bg-primary/90 font-semibold shadow transition">
            <i class="bi bi-plus-circle mr-2"></i>Créer un sprint</a>
    </div>
    @endcan

    {{-- Liste des sprints --}}
    <h3 class="text-xl font-semibold mt-8 mb-4">Vos sprints :</h3>

    <div class="mb-4">
    <a href="{{ route('projects.roadmap', $project->id) }}" class="rounded px-4 py-2 bg-gradient-purple text-white hover:shadow-lg font-semibold shadow transition">
        <i class="bi bi-calendar-event mr-2"></i>
                <span>Voir la Roadmap</span>
            </a>
    </div>

    <ul class="space-y-4">
        @forelse($project->sprints as $sprint)
            <li>
                <div class="bg-gray-50 rounded shadow-sm flex flex-row md:flex-row md:items-center md:justify-between p-4 border border-gray-200 relative">
                    <div>
                        <div class="text-lg font-semibold text-primary mb-1">{{ $sprint->nom }}</div>
                        <div class="text-gray-500 text-sm">Du <span class="font-medium">{{ $sprint->begining }}</span> au <span class="font-medium">{{ $sprint->end }}</span></div>
                    </div>
                    <div class="flex gap-3 mt-3 md:mt-0 items-center">
                        <a href="{{ route('projects.sprints.show', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                        class="flex items-center space-x-1 rounded px-4 py-2 bg-primary text-white hover:bg-primary/80 text-sm font-semibold shadow transition">
                            <i class="bi bi-kanban-fill mr-2"></i>
                            <span>Vue Kanban</span>
                        </a>
                        @can('update', $project)
                        <!-- Bouton menu Sprint -->
                        <div class="relative group">
                            <button class="p-2 rounded hover:bg-gray-200" onclick="event.stopPropagation(); this.nextElementSibling.classList.toggle('hidden');">
                                <i class="bi bi-three-dots-vertical text-xl"></i>
                            </button>
                            <div class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-30 group-hover:block">
                                <form method="POST" action="{{ route('projects.sprints.destroy', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                                    onsubmit="return confirm('Supprimer ce sprint ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left text-red-600 px-4 py-2 hover:bg-gray-100">
                                        <i class="bi bi-trash mr-1"></i> Supprimer le sprint
                                    </button>
                                </form>
                                <a href="{{ route('projects.sprints.edit', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                                    class="w-full text-left text-gray-600 px-4 py-2 flex items-center hover:bg-gray-100">
                                    <i class="bi bi-pencil mr-1"></i> Modifier le sprint
                                </a>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
            </li>

        @empty
            <li class="text-gray-500">Aucun sprint disponible pour ce projet.</li>
        @endforelse
    </ul>
</div>

{{-- Section Membres --}}
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Membres du projet</h3>
        @can('manageMembers', $project)
        <button onclick="document.getElementById('addMemberForm').classList.toggle('hidden')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
            <i class="bi bi-plus-circle mr-1"></i> Ajouter un membre
        </button>
        @endcan
    </div>

    @can('manageMembers', $project)
    <form id="addMemberForm" action="{{ route('projects.members.store', $project) }}" method="POST" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="email" name="email" class="form-input rounded-lg" placeholder="Email de l'utilisateur" required>
            <select name="role" class="form-select rounded-lg">
                <option value="membre">Membre</option>
                <option value="invite">Invité</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Ajouter</button>
        </div>
    </form>
    @endcan

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <ul class="divide-y divide-gray-200">
        @foreach($project->users as $member)
            <li class="py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=0cbaba&color=fff"
                         alt="Avatar"
                         class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium text-gray-800">{{ $member->name }}</p>
                        <p class="text-xs text-gray-500">{{ $member->email }}</p>
                    </div>
                    @if($project->chef_projet === $member->id)
                        <span class="ml-2 px-2 py-1 text-xs bg-gradient-orange text-white rounded-full font-semibold">Créateur</span>
                    @else
                        @php
                            $roleLabel = match($member->pivot->role) {
                                'admin' => 'Admin',
                                'membre' => 'Membre',
                                'invite' => 'Invité',
                                default => ucfirst($member->pivot->role)
                            };
                            $roleBg = match($member->pivot->role) {
                                'admin' => 'bg-purple-100 text-purple-700',
                                'membre' => 'bg-blue-100 text-blue-700',
                                'invite' => 'bg-gray-100 text-gray-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="ml-2 px-2 py-1 text-xs {{ $roleBg }} rounded-full font-semibold">{{ $roleLabel }}</span>
                    @endif
                </div>

                @if($project->chef_projet !== $member->id)
                    <div class="flex items-center gap-2">
                        @can('manageMembers', $project)
                        <form method="POST" action="{{ route('projects.members.update', [$project, $member]) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="form-select text-sm rounded-lg">
                                @foreach(['admin'=>'Admin','membre'=>'Membre','invite'=>'Invité'] as $val=>$label)
                                    <option value="{{ $val }}" @selected($member->pivot->role === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1 text-sm bg-gray-800 text-white rounded-lg hover:bg-gray-700">Modifier</button>
                        </form>

                        <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Retirer ce membre ?')" class="px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @else
                            @php
                                $roleDisplay = match($member->pivot->role) {
                                    'admin' => 'Admin',
                                    'membre' => 'Membre',
                                    'invite' => 'Invité',
                                    default => ucfirst($member->pivot->role)
                                };
                            @endphp
                            <span class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg">{{ $roleDisplay }}</span>
                        @endcan
                    </div>
                @else
                    <span class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg">Admin (Créateur)</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection
