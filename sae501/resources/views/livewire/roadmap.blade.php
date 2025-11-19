<div class="space-y-6">

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- En-tête avec actions --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-dark-text">Roadmap Projet</h2>
            <p class="text-gray-600 dark:text-dark-muted mt-1">{{ $project->nom }}</p>
        </div>
    @can('update', $project)
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
               class="rounded px-4 py-2 bg-primary text-white hover:bg-primary/90 dark:hover:bg-primary/80 font-semibold shadow transition">
                <i class="bi bi-plus-circle mr-2"></i>Nouveau Sprint
            </a>
        </div>
    @endcan
    </div>

    {{-- Statistiques Dashboard --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-gradient-purple dark:bg-purple-900/30 border border-purple-200 dark:border-purple-700 rounded-lg p-4 hover:shadow-lg dark:hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-calendar3 text-3xl text-purple-600 dark:text-purple-400"></i>
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-200 dark:bg-purple-800 px-2 py-1 rounded-full">
                    {{ $stats['active_sprints'] }} actifs
                </span>
            </div>
            <div class="text-sm text-purple-600 dark:text-purple-400 font-semibold">Sprints</div>
            <div class="text-3xl font-bold text-purple-700">{{ $stats['total_sprints'] }}</div>
        </div>

        <div class="bg-gradient-blue dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 hover:shadow-lg dark:hover:shadow-xl transition">
            <i class="bi bi-box-seam text-3xl text-blue-600 dark:text-blue-400 mb-2"></i>
            <div class="text-sm text-blue-600 dark:text-blue-400 font-semibold">Epics</div>
            <div class="text-3xl font-bold text-blue-700">{{ $stats['total_epics'] }}</div>
        </div>

        <div class="bg-gradient-green dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4 hover:shadow-lg dark:hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-check-circle-fill text-3xl text-green-600 dark:text-green-400"></i>
                <span class="text-xs font-semibold text-green-700">{{ $stats['completion_rate'] }}%</span>
            </div>
            <div class="text-sm text-green-600 dark:text-green-400 font-semibold">Terminées</div>
            <div class="text-3xl font-bold text-green-700">{{ $stats['completed_tasks'] }}</div>
        </div>

        <div class="bg-gradient-orange dark:bg-orange-900/30 border border-orange-200 dark:border-orange-700 rounded-lg p-4 hover:shadow-lg dark:hover:shadow-xl transition">
            <i class="bi bi-hourglass-split text-3xl text-orange-600 dark:text-orange-400 mb-2"></i>
            <div class="text-sm text-orange-600 dark:text-orange-400 font-semibold">En cours</div>
            <div class="text-3xl font-bold text-orange-700">{{ $stats['in_progress_tasks'] }}</div>
        </div>

        <div class="bg-gradient-red dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg p-4 hover:shadow-lg dark:hover:shadow-xl transition">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-exclamation-triangle-fill text-3xl text-red-600 dark:text-red-400"></i>
                @if($stats['overdue_tasks'] > 0)
                    <span class="text-xs font-semibold text-red-700 dark:text-red-400 bg-red-200 dark:bg-red-800 px-2 py-1 rounded-full animate-pulse">
                        Urgent !
                    </span>
                @endif
            </div>
            <div class="text-sm text-red-600 dark:text-red-400 font-semibold">En retard</div>
            <div class="text-3xl font-bold text-red-700">{{ $stats['overdue_tasks'] }}</div>
        </div>
    </div>

    {{-- Gantt Chart --}}
    <div class="bg-white dark:bg-dark-card rounded-lg shadow-lg dark:shadow-none border border-gray-200 dark:border-dark-border overflow-hidden">
        <div class="relative" style="min-height: 500px;">
            {{-- Boutons de vue stylisés avec état actif --}}
            <div class="flex gap-2 p-4 bg-gray-50 dark:bg-dark-hover border-b dark:border-dark-border items-center" x-data="{ activeView: '{{ $viewMode }}' }">

                <button id="btn-day"
                        @click="activeView = 'Day'"
                        :class="activeView === 'Day' ? 'bg-secondary text-white shadow-lg scale-105' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-dark-text hover:bg-gray-100 dark:hover:bg-dark-hover'"
                        class="px-4 py-2 rounded-lg transition-all duration-200 font-medium border border-gray-200 dark:border-dark-border flex items-center gap-2">
                    <span>Jour</span>
                </button>

                <button id="btn-week"
                        @click="activeView = 'Week'"
                        :class="activeView === 'Week' ? 'bg-secondary text-white shadow-lg scale-105' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-dark-text hover:bg-gray-100 dark:hover:bg-dark-hover'"
                        class="px-4 py-2 rounded-lg transition-all duration-200 font-medium border border-gray-200 dark:border-dark-border flex items-center gap-2">
                    <span>Semaine</span>
                </button>

                <button id="btn-month"
                        @click="activeView = 'Month'"
                        :class="activeView === 'Month' ? 'bg-secondary text-white shadow-lg scale-105' : 'bg-white dark:bg-dark-card text-gray-700 dark:text-dark-text hover:bg-gray-100 dark:hover:bg-dark-hover'"
                        class="px-4 py-2 rounded-lg transition-all duration-200 font-medium border border-gray-200 dark:border-dark-border flex items-center gap-2">
                    <span>Mois</span>
                </button>

                <div class="flex-1"></div>

                <button id="btn-today"
                        class="px-4 py-2 rounded-lg bg-gradient-purple dark:bg-purple-600 text-white hover:shadow-xl dark:hover:bg-purple-700 transition-all duration-200 font-semibold flex items-center gap-2 border-2 border-transparent hover:border-purple-400">
                    <i class="bi bi-pin-map-fill"></i>
                    <span>Aujourd'hui</span>
                </button>
            </div>
            {{-- Conteneur Gantt --}}
            <div id="gantt" class="relative min-w-full p-4 bg-white dark:bg-dark-card" wire:ignore></div>
        </div>
    </div>

    {{-- Liste détaillée avec actions --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800 dark:text-dark-text flex items-center gap-2">
                Vue détaillée
            </h3>
            <span class="text-sm text-gray-500 dark:text-dark-muted">{{ $sprints->count() }} sprint(s) • {{ $epics->count() }} epic(s)</span>
        </div>

        @forelse($sprints as $sprint)
            @php
                $progress = $sprint->computed_progress ?? 0;
                $total = $sprint->computed_total ?? 0;
                $done = $sprint->computed_done ?? 0;
            @endphp

            <div class="bg-white dark:bg-dark-card rounded-lg shadow dark:shadow-none border border-purple-200 dark:border-purple-700 p-5 hover:shadow-xl dark:hover:shadow-2xl transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="text-lg font-bold text-purple-700 dark:text-purple-400">{{ $sprint->nom }}</h4>
                            @php
                                $isActive = Carbon\Carbon::parse($sprint->end)->isFuture();
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                                {{ $isActive ? '✓ Actif' : '✓ Terminé' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-dark-muted">
                            <i class="bi bi-calendar3"></i>
                            {{ Carbon\Carbon::parse($sprint->begining)->format('d/m/Y') }}
                            →
                            {{ Carbon\Carbon::parse($sprint->end)->format('d/m/Y') }}
                            <span class="ml-2 text-gray-400 dark:text-dark-muted">({{ Carbon\Carbon::parse($sprint->begining)->diffInDays($sprint->end) }} jours)</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-purple-700 dark:text-purple-400">{{ $progress }}%</div>
                            <div class="text-xs text-gray-500 dark:text-dark-muted">{{ $done }}/{{ $total }} tâches</div>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-dark-hover text-gray-700 dark:text-dark-text">
                                <i class="bi bi-three-dots-vertical text-xl"></i>
                            </button>
                            <div x-show="open" @click.outside="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border rounded shadow-lg z-20">
                                <a href="{{ route('projects.sprints.show', [$project->id, $sprint->id]) }}"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-dark-hover text-gray-700 dark:text-dark-text">
                                    <i class="bi bi-eye mr-2"></i>Voir détails
                                </a>
                                @can('update', $project)
                                <a href="{{ route('projects.sprints.edit', [$project->id, $sprint->id]) }}"
                                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-dark-hover text-gray-700 dark:text-dark-text">
                                    <i class="bi bi-pencil mr-2"></i>Modifier
                                </a>
                                @endcan

                                @can('delete', $project)
                                <button wire:click="deleteSprint({{ $sprint->id }})"
                                        wire:confirm="Supprimer ce sprint ?"
                                        class="block w-full text-left px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400">
                                    <i class="bi bi-trash mr-2"></i>Supprimer
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Barre de progression --}}
                <div class="w-full bg-gray-200 dark:bg-dark-hover rounded-full h-4 mb-4 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full flex items-center justify-center text-[10px] font-bold text-white transition-all duration-700 ease-out"
                         style="width: {{ $progress }}%; background: linear-gradient(to right,#343464,#5a5a8f);">
                        @if($progress > 15)
                            {{ $progress }}%
                        @endif
                    </div>
                </div>
                @if($progress <= 15 && $progress > 0)
                    <div class="text-xs text-gray-500 dark:text-dark-muted -mt-3 mb-2">{{ $progress }}%</div>
                @endif

                <div class="space-y-2">
                    @foreach($sprint->epics as $epic)
                        @php
                            $epicProgress = $epic->computed_progress ?? 0;
                            $epicDone = $epic->computed_done ?? 0;
                            $epicTotal = $epic->computed_total ?? 0;
                        @endphp
                        <div class="flex items-center gap-3 bg-gray-50 dark:bg-dark-hover rounded p-3 hover:bg-gray-100 dark:hover:bg-dark-border transition group">
                            <div class="flex-1">
                                <div class="font-semibold text-primary">{{ $epic->nom }}</div>
                                <div class="text-xs text-gray-500 dark:text-dark-muted flex items-center gap-3 mt-1">
                                    <span>
                                        <i class="bi bi-calendar3"></i>
                                        {{ Carbon\Carbon::parse($epic->begining)->format('d/m') }}
                                        →
                                        {{ Carbon\Carbon::parse($epic->end)->format('d/m') }}
                                    </span>
                                    <span class="text-gray-400 dark:text-dark-muted">•</span>
                                    <span>{{ $epicTotal }} tâche(s)</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-gray-700 dark:text-dark-text">{{ $epicProgress }}%</div>
                                <div class="text-xs text-gray-500 dark:text-dark-muted">{{ $epicDone }}/{{ $epicTotal }}</div>
                            </div>
                            <div class="w-28 bg-gray-200 dark:bg-dark-hover rounded-full h-2 overflow-hidden shadow-inner">
                                <div class="h-full rounded-full bg-primary transition-all duration-700 ease-out"
                                     style="width: {{ $epicProgress }}%"
                                     title="Epic {{ $epic->nom }} {{ $epicProgress }}%">
                                </div>
                            </div>
    
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-dark-card rounded-lg shadow dark:shadow-none border dark:border-dark-border p-12 text-center">
                <i class="bi bi-inbox text-6xl text-gray-300 dark:text-dark-muted mb-4"></i>
                <p class="text-gray-500 dark:text-dark-muted mb-4">Aucun sprint trouvé</p>
                <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
                   class="inline-block px-6 py-3 rounded bg-purple-600 text-white hover:bg-purple-700 dark:hover:bg-purple-800 transition">
                    Créer votre premier sprint
                </a>
            </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
let ganttInstance = null;

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

async function initializeGantt() {
    const ganttEl = document.getElementById('gantt');
    if (!ganttEl) return;

    console.log('⏳ Initialisation Gantt...');

    if (!window.ganttLoadedPromise) {
        ganttEl.innerHTML = '<div class="text-center text-red-500 dark:text-red-400 py-12">Erreur chargement</div>';
        return;
    }

    const loaded = await window.ganttLoadedPromise;
    if (!loaded || !window.Gantt) {
        ganttEl.innerHTML = '<div class="text-center text-red-500 dark:text-red-400 py-12">Gantt non disponible</div>';
        return;
    }

    const tasks = @json($ganttTasks);
    if (tasks.length === 0) {
        ganttEl.innerHTML = '<div class="text-center text-gray-500 dark:text-dark-muted py-12">🗓️ Aucune donnée<br><span class="text-sm">Créez des sprints et epics</span></div>';
        return;
    }

    try {
        if (ganttInstance) {
            ganttEl.innerHTML = '';
            ganttInstance = null;
        }

        ganttInstance = new Gantt("#gantt", tasks, {
            view_mode: '{{ $viewMode }}',
            bar_height: 40,
            padding: 24,
            date_format: 'YYYY-MM-DD',
            language: 'fr',
            on_click: task => {
                console.log('Clic:', task);
            },
            on_date_change: (task, start, end) => {
                console.log('Date changée:', task, start, end);
            },
            custom_popup_html: function(task) {
                const color = task.color || '#2ab7ca';
                const icon = task.type === 'sprint' ? '🏃' : task.type === 'milestone' ? '🎯' : '📦';
                const startDate = formatDate(task.start);
                const endDate = formatDate(task.end);

                let tasksInfo = '';
                if (task.total_tasks !== undefined) {
                    tasksInfo = `
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-dark-muted">Tâches:</span>
                            <span class="font-semibold dark:text-dark-text">${task.done_tasks}/${task.total_tasks}</span>
                        </div>
                    `;
                }

                return `
                    <div class="p-4 min-w-[300px] max-w-[400px]">
                        <div class="font-bold text-lg mb-3 flex items-center gap-2" style="color: ${color}">
                            ${icon} ${task.name}
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-dark-muted">Dates:</span>
                                <span class="font-semibold dark:text-dark-text">${startDate} → ${endDate}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-dark-muted">Progression:</span>
                                <span class="font-semibold dark:text-dark-text">${task.progress}%</span>
                            </div>
                            ${tasksInfo}
                            <div class="w-full bg-gray-200 dark:bg-dark-hover rounded-full h-2 mt-2">
                                <div class="h-2 rounded-full transition-all" style="width: ${task.progress}%; background: ${color};"></div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });

        setTimeout(() => {
            if (ganttInstance && ganttInstance.bars) {
                ganttInstance.bars.forEach(bar => {
                    const color = bar.task.color || '#343464ff';
                    bar.$bar.style.fill = color;
                    bar.$bar.style.stroke = color;

                    if (bar.task.type === 'milestone') {
                        bar.$bar.setAttribute('rx', '50%');
                        bar.$bar.setAttribute('ry', '50%');
                    }
                });
            }
        }, 300);

        console.log('✅ Gantt initialisé avec succès');
    } catch (error) {
        console.error('❌ Erreur Gantt:', error);
        ganttEl.innerHTML = `<div class="text-center text-red-500 dark:text-red-400 py-12">Erreur: ${error.message}</div>`;
    }
}

function centerToday() {
    const gantt = document.getElementById('gantt');
    if (!gantt) return;

    const today = new Date();
    const todayDay = today.getDate().toString();
    const dayEls = gantt.querySelectorAll('.grid-header .tick');

    dayEls.forEach(el => {
        const text = el.textContent.trim();
        if (text === todayDay || text === todayDay.padStart(2, '0')) {
            gantt.parentElement.scrollLeft = el.offsetLeft - 200;
        }
    });
}

// Event Listeners pour les boutons
document.addEventListener('DOMContentLoaded', () => {
    const btnDay = document.getElementById('btn-day');
    const btnWeek = document.getElementById('btn-week');
    const btnMonth = document.getElementById('btn-month');
    const btnToday = document.getElementById('btn-today');

    if (btnDay) {
        btnDay.addEventListener('click', () => {
            if (ganttInstance) {
                ganttInstance.change_view_mode('Day');
                setTimeout(positionTodayLine, 300);
                setTimeout(centerToday, 400);
            }
        });
    }

    if (btnWeek) {
        btnWeek.addEventListener('click', () => {
            if (ganttInstance) {
                ganttInstance.change_view_mode('Week');
                setTimeout(positionTodayLine, 300);
            }
        });
    }

    if (btnMonth) {
        btnMonth.addEventListener('click', () => {
            if (ganttInstance) {
                ganttInstance.change_view_mode('Month');
                setTimeout(positionTodayLine, 300);
            }
        });
    }

    if (btnToday) {
        btnToday.addEventListener('click', () => {
            if (ganttInstance) {
                ganttInstance.change_view_mode('Day');
                setTimeout(() => {
                    positionTodayLine();
                    centerToday();
                }, 300);
            }
        });
    }
});

document.addEventListener('livewire:initialized', () => {
    initializeGantt();

    Livewire.on('updateGanttView', (data) => {
        if (ganttInstance) {
            ganttInstance.change_view_mode(data[0].mode);
            setTimeout(() => {
                if (data[0].mode === 'Day') {
                    centerToday();
                }
            }, 300);
        }
    });

    Livewire.on('centerToday', () => {
        setTimeout(centerToday, 250);
    });

    Livewire.on('ganttRefresh', () => {
        setTimeout(initializeGantt, 200);
    });
});
</script>
@endpush
