@extends('layouts.app')

@section('title', $project->nom)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Projet --}}
    <div class="bg-white dark:bg-dark-card p-8 rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-dark-text">{{ $project->nom }}</h1>
            <p class="text-gray-600 dark:text-dark-muted text-lg mb-4">{{ $project->description }}</p>
            <div class="flex gap-3">
                @can('updateSettings', $project)
                <a href="{{ route('projects.edit', $project->id) }}" class="rounded px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 text-yellow-900 dark:text-yellow-400 border border-yellow-300 dark:border-yellow-700 shadow-sm transition">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                @endcan
                @can('delete', $project)
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Supprimer ce projet ?');" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded px-4 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-700 shadow-sm transition">
                        <i class="bi bi-trash"></i> Supprimer le projet
                    </button>
                </form>
                @endcan
                <a href="{{ route('projects.roadmap', $project->id) }}" class="rounded px-4 py-2 bg-gradient-purple dark:bg-purple-600 text-white hover:shadow-lg dark:hover:bg-purple-700 font-semibold shadow transition">
                    <i class="bi bi-calendar-event mr-2"></i>Voir la Roadmap
                </a>
            </div>
        </div>
    </div>

    {{-- Statistiques visuelles (section dépliante) --}}
    @if($totalTasks > 0)
    <details id="statsCollapse" open class="group bg-white dark:bg-dark-card p-6 rounded-2xl shadow-lg dark:shadow-none border dark:border-dark-border">
        <summary class="cursor-pointer list-none flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-dark-text flex items-center">
                <i class="bi bi-graph-up text-primary mr-3 text-3xl"></i>
                Statistiques du projet
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-dark-muted hidden md:inline">Cliquez pour replier/déplier</span>
                <svg class="w-6 h-6 text-gray-500 dark:text-dark-muted transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </summary>

        <div id="statsContent">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- KPI Card: Progression globale --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-6 rounded-xl border-2 border-green-200 dark:border-green-700">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-green-800 dark:text-green-400 uppercase">Progression</h3>
                        <i class="bi bi-speedometer2 text-green-600 dark:text-green-400 text-2xl"></i>
                    </div>
                    <div class="flex items-end gap-3">
                        <p class="text-4xl font-bold text-green-700 dark:text-green-300">{{ $globalProgress }}%</p>
                        <p class="text-sm text-green-600 dark:text-green-400 mb-1">{{ $completedTasks }}/{{ $totalTasks }}</p>
                    </div>
                    <div class="mt-3 bg-green-200 dark:bg-green-800 rounded-full h-2 overflow-hidden">
                        <div class="bg-green-600 dark:bg-green-400 h-full transition-all duration-500" style="width: {{ $globalProgress }}%"></div>
                    </div>
                </div>

                {{-- KPI Card: Total tâches --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-6 rounded-xl border-2 border-blue-200 dark:border-blue-700">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-400 uppercase">Total Tâches</h3>
                        <i class="bi bi-list-check text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <p class="text-4xl font-bold text-blue-700 dark:text-blue-300">{{ $totalTasks }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-2">Sur {{ $project->sprints->count() }} sprint(s)</p>
                </div>

                {{-- Graphique Statut (compact) --}}
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-6 rounded-xl border-2 border-purple-200 dark:border-purple-700">
                    <h3 class="text-sm font-semibold text-purple-800 dark:text-purple-400 uppercase mb-3 flex items-center">
                        <i class="bi bi-pie-chart-fill text-purple-600 dark:text-purple-400 mr-2"></i>Par Statut
                    </h3>
                    <div class="flex justify-center">
                        <canvas id="statusChart" class="max-w-[140px] max-h-[140px]"></canvas>
                    </div>
                </div>

                {{-- Graphique Priorité (compact) --}}
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 p-6 rounded-xl border-2 border-orange-200 dark:border-orange-700">
                    <h3 class="text-sm font-semibold text-orange-800 dark:text-orange-400 uppercase mb-3 flex items-center">
                        <i class="bi bi-exclamation-triangle-fill text-orange-600 dark:text-orange-400 mr-2"></i>Par Priorité
                    </h3>
                    <div class="flex justify-center">
                        <canvas id="priorityChart" class="max-w-[140px] max-h-[140px]"></canvas>
                    </div>
                </div>
            </div>

            {{-- Progression par sprint (barre horizontale compacte) --}}
            @if($sprintProgress->count() > 0)
            <div class="mt-8 p-6 bg-gray-50 dark:bg-dark-hover rounded-xl border border-gray-200 dark:border-dark-border">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-dark-text flex items-center">
                    <i class="bi bi-bar-chart-fill text-primary mr-2"></i>
                    Progression par sprint
                </h3>
                <div class="max-h-64">
                    <canvas id="sprintProgressChart"></canvas>
                </div>
            </div>
            @endif
        </div>
    </details>
    @endif

    {{-- Bouton Créer un sprint --}}
    @can('update', $project)
    <div class="bg-white dark:bg-dark-card p-6 rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border">
        <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
           class="rounded px-4 py-2 bg-primary text-white hover:bg-primary/90 dark:hover:bg-primary/80 font-semibold shadow transition">
            <i class="bi bi-plus-circle mr-2"></i>Créer un sprint
        </a>
    </div>
    @endcan

    {{-- Liste des sprints --}}
    <div class="bg-white dark:bg-dark-card p-8 rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border">
        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-dark-text">Vos sprints :</h3>
        <ul class="space-y-4">
            @forelse($project->sprints as $sprint)
                <li>
                    <div class="bg-gray-50 dark:bg-dark-hover rounded shadow-sm flex flex-row md:flex-row md:items-center md:justify-between p-4 border border-gray-200 dark:border-dark-border relative hover:shadow-md dark:hover:shadow-lg transition">
                        <div>
                            <div class="text-lg font-semibold text-primary mb-1">{{ $sprint->nom }}</div>
                            <div class="text-gray-500 dark:text-dark-muted text-sm">Du <span class="font-medium">{{ $sprint->begining }}</span> au <span class="font-medium">{{ $sprint->end }}</span></div>
                            @php
                                $sprintData = $sprintProgress->firstWhere('name', $sprint->nom);
                            @endphp
                            @if($sprintData)
                            <div class="mt-2 text-xs text-gray-600 dark:text-dark-muted">
                                <span class="font-semibold">{{ $sprintData['completed'] }}/{{ $sprintData['total'] }}</span> tâches terminées
                                <span class="ml-2 px-2 py-0.5 rounded-full {{ $sprintData['percentage'] == 100 ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' }} font-semibold">{{ $sprintData['percentage'] }}%</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex gap-3 mt-3 md:mt-0 items-center">
                            <a href="{{ route('projects.sprints.show', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                            class="flex items-center space-x-1 rounded px-4 py-2 bg-primary text-white hover:bg-primary/80 dark:hover:bg-primary/70 text-sm font-semibold shadow transition">
                                <i class="bi bi-kanban-fill mr-2"></i>
                                <span>Vue Kanban</span>
                            </a>
                            @can('update', $project)
                            <div class="relative group">
                                <button class="p-2 rounded hover:bg-gray-200 dark:hover:bg-dark-border text-gray-700 dark:text-dark-text" onclick="event.stopPropagation(); this.nextElementSibling.classList.toggle('hidden');">
                                    <i class="bi bi-three-dots-vertical text-xl"></i>
                                </button>
                                <div class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border rounded shadow-lg z-30 group-hover:block">
                                    <form method="POST" action="{{ route('projects.sprints.destroy', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                                        onsubmit="return confirm('Supprimer ce sprint ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-left text-red-600 dark:text-red-400 px-4 py-2 hover:bg-gray-100 dark:hover:bg-dark-hover">
                                            <i class="bi bi-trash mr-1"></i> Supprimer le sprint
                                        </button>
                                    </form>
                                    <a href="{{ route('projects.sprints.edit', ['project' => $project->id, 'sprint' => $sprint->id]) }}"
                                        class="w-full text-left text-gray-600 dark:text-dark-text px-4 py-2 flex items-center hover:bg-gray-100 dark:hover:bg-dark-hover">
                                        <i class="bi bi-pencil mr-1"></i> Modifier le sprint
                                    </a>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 dark:text-dark-muted">Aucun sprint disponible pour ce projet.</li>
            @endforelse
        </ul>
    </div>

    {{-- Section Membres --}}
    <div class="bg-white dark:bg-dark-card p-6 rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-dark-text">Membres du projet</h3>
            @can('manageMembers', $project)
            <button onclick="document.getElementById('addMemberForm').classList.toggle('hidden')" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 dark:hover:bg-primary/80 transition">
                <i class="bi bi-plus-circle mr-1"></i> Ajouter un membre
            </button>
            @endcan
        </div>

        @can('manageMembers', $project)
        <form id="addMemberForm" action="{{ route('projects.members.store', $project) }}" method="POST" class="hidden mb-6 p-4 bg-gray-50 dark:bg-dark-hover rounded-lg border border-gray-200 dark:border-dark-border">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="email" name="email" class="form-input rounded-lg bg-white dark:bg-dark-card text-gray-900 dark:text-dark-text border-gray-300 dark:border-dark-border" placeholder="Email de l'utilisateur" required>
                <select name="role" class="form-select rounded-lg bg-white dark:bg-dark-card text-gray-900 dark:text-dark-text border-gray-300 dark:border-dark-border">
                    <option value="membre">Membre</option>
                    <option value="invite">Invité</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 dark:hover:bg-primary/80">Ajouter</button>
            </div>
        </form>
        @endcan

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg border border-green-200 dark:border-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg border border-red-200 dark:border-red-700">
                {{ session('error') }}
            </div>
        @endif

        <ul class="divide-y divide-gray-200 dark:divide-dark-border">
            @foreach($project->users as $member)
                <li class="py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background={{ $member->avatar_color ?? '0cbaba' }}&color=fff"
                             alt="Avatar de {{ $member->name }}"
                             class="w-10 h-10 rounded-full shadow-sm">
                        <div>
                            <p class="font-medium text-gray-800 dark:text-dark-text">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-dark-muted">{{ $member->email }}</p>
                        </div>
                        @if($project->chef_projet === $member->id)
                            <span class="ml-2 px-2 py-1 text-xs bg-gradient-orange dark:bg-orange-600 text-white rounded-full font-semibold">Créateur</span>
                        @else
                            @php
                                $roleLabel = match($member->pivot->role) {
                                    'admin' => 'Admin',
                                    'membre' => 'Membre',
                                    'invite' => 'Invité',
                                    default => ucfirst($member->pivot->role)
                                };
                                $roleBg = match($member->pivot->role) {
                                    'admin' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                    'membre' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                    'invite' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                    default => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
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
                                <select name="role" class="form-select text-sm rounded-lg bg-white dark:bg-dark-hover text-gray-900 dark:text-dark-text border-gray-300 dark:border-dark-border">
                                    @foreach(['admin'=>'Admin','membre'=>'Membre','invite'=>'Invité'] as $val=>$label)
                                        <option value="{{ $val }}" @selected($member->pivot->role === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button class="px-3 py-1 text-sm bg-gray-800 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600">Modifier</button>
                            </form>

                            <form method="POST" action="{{ route('projects.members.destroy', [$project, $member]) }}">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Retirer ce membre ?')" class="px-3 py-1 text-sm bg-red-500 dark:bg-red-600 text-white rounded-lg hover:bg-red-600 dark:hover:bg-red-700">
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
                                <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-dark-hover text-gray-600 dark:text-dark-muted rounded-lg">{{ $roleDisplay }}</span>
                            @endcan
                        </div>
                    @else
                        <span class="px-3 py-1 text-sm bg-gray-100 dark:bg-dark-hover text-gray-600 dark:text-dark-muted rounded-lg">Admin (Créateur)</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($totalTasks > 0)
    const colors = {
        primary:'#0CBABA',
        green:'#22c55e',
        yellow:'#eab308',
        red:'#ef4444',
        blue:'#3b82f6',
        gray:'#6b7280'
    };

    // Statut (compact)
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) new Chart(statusCtx, {
        type:'doughnut',
        data:{
            labels: {!! json_encode($statusChart['labels']) !!},
            datasets:[{
                data: {!! json_encode($statusChart['data']) !!},
                backgroundColor:[colors.gray, colors.blue, colors.green],
                borderWidth:0
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:true,
            cutout:'65%',
            plugins:{
                legend:{
                    display:true,
                    position:'bottom',
                    labels:{
                        boxWidth:12,
                        font:{ size:10 },
                        padding:8,
                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#374151'
                    }
                },
                tooltip:{ enabled:true }
            }
        }
    });

    // Priorité (compact)
    const priorityCtx = document.getElementById('priorityChart');
    if (priorityCtx) new Chart(priorityCtx, {
        type:'doughnut',
        data:{
            labels: {!! json_encode($priorityChart['labels']) !!},
            datasets:[{
                data: {!! json_encode($priorityChart['data']) !!},
                backgroundColor:[colors.green, colors.yellow, colors.red],
                borderWidth:0
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:true,
            cutout:'65%',
            plugins:{
                legend:{
                    display:true,
                    position:'bottom',
                    labels:{
                        boxWidth:12,
                        font:{ size:10 },
                        padding:8,
                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#374151'
                    }
                },
                tooltip:{ enabled:true }
            }
        }
    });

    // Barres par sprint
    @if($sprintProgress->count() > 0)
    const sp = document.getElementById('sprintProgressChart');
    if (sp) new Chart(sp, {
        type:'bar',
        data:{
            labels: {!! $sprintProgress->pluck('name')->toJson() !!},
            datasets:[
                {
                    label:'Terminées',
                    data:{!! $sprintProgress->pluck('completed')->toJson() !!},
                    backgroundColor:colors.green
                },
                {
                    label:'Restantes',
                    data:{!! $sprintProgress->map(fn($s)=>$s['total']-$s['completed'])->toJson() !!},
                    backgroundColor:colors.gray
                }
            ]
        },
        options:{
            indexAxis: 'y',
            responsive:true,
            maintainAspectRatio:false,
            scales:{
                x:{
                    stacked:true,
                    grid:{ display:false },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#6b7280' }
                },
                y:{
                    stacked:true,
                    grid:{ display:false },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#6b7280' }
                }
            },
            plugins:{
                legend:{
                    position:'top',
                    labels:{
                        font:{ size:11 },
                        padding:10,
                        color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#374151'
                    }
                }
            }
        }
    });
    @endif
    @endif
});
</script>
@endpush
@endsection
