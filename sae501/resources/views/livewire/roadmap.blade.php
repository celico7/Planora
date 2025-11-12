<div class="space-y-6" x-data="{ showFilters: false }">

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- En-tête avec actions --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">📅 Roadmap Projet</h2>
            <p class="text-gray-600 mt-1">{{ $project->nom }}</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <button @click="showFilters = !showFilters"
                    class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition flex items-center gap-2">
                <i class="bi bi-funnel"></i>
                Filtres
                <i class="bi" :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
            <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
           class="rounded px-4 py-2 bg-primary text-white hover:bg-primary/90 font-semibold shadow transition">
            <i class="bi bi-plus-circle mr-2"></i>Nouveau Sprint</a>
            <button wire:click="exportData"
                    class="px-4 py-2 rounded bg-secondary text-white hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-download"></i>
                Exporter
            </button>
        </div>
    </div>

    {{-- Panneau Filtres --}}
    <div x-show="showFilters"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-gray-50 rounded-lg p-6 border border-gray-200">
        <h3 class="font-semibold text-gray-700 mb-4">🔍 Filtres avancés</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select wire:model.live="filterStatus" class="w-full rounded border-gray-300">
                    <option value="all">Tous</option>
                    <option value="active">Actifs</option>
                    <option value="completed">Terminés</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sprint</label>
                <select wire:model.live="filterSprint" class="w-full rounded border-gray-300">
                    <option value="all">Tous les sprints</option>
                    @foreach($sprints as $sprint)
                        <option value="{{ $sprint->id }}">{{ $sprint->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text"
                       wire:model.live.debounce.500ms="searchTerm"
                       placeholder="Rechercher un epic..."
                       class="w-full rounded border-gray-300">
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button wire:click="applyFilters"
                    class="px-4 py-2 rounded bg-primary text-white hover:bg-primary/90 transition">
                Appliquer
            </button>
            <button wire:click="resetFilters"
                    class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                Réinitialiser
            </button>
        </div>
    </div>

    {{-- Modes de vue Gantt --}}
    <div class="flex gap-3 items-center bg-white p-4 rounded-lg shadow border">
        <span class="text-sm font-semibold text-gray-700">Vue :</span>
        <button wire:click="changeViewMode('Day')"
                class="px-4 py-2 rounded transition {{ $viewMode === 'Day' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Jour
        </button>
        <button wire:click="changeViewMode('Week')"
                class="px-4 py-2 rounded transition {{ $viewMode === 'Week' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Semaine
        </button>
        <button wire:click="changeViewMode('Month')"
                class="px-4 py-2 rounded transition {{ $viewMode === 'Month' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Mois
        </button>
        <button wire:click="viewToday"
                class="px-4 py-2 rounded bg-secondary text-white hover:opacity-90 ml-auto flex items-center gap-2 transition">
            <i class="bi bi-pin-map"></i>Aujourd'hui
        </button>
    </div>

    {{-- Statistiques Dashboard --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-calendar3 text-3xl text-purple-600"></i>
                <span class="text-xs font-semibold text-purple-600 bg-purple-200 px-2 py-1 rounded-full">
                    {{ $stats['active_sprints'] }} actifs
                </span>
            </div>
            <div class="text-sm text-purple-600 font-semibold">Sprints</div>
            <div class="text-3xl font-bold text-purple-700">{{ $stats['total_sprints'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:shadow-lg transition">
            <i class="bi bi-box-seam text-3xl text-blue-600 mb-2"></i>
            <div class="text-sm text-blue-600 font-semibold">Epics</div>
            <div class="text-3xl font-bold text-blue-700">{{ $stats['total_epics'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-check-circle-fill text-3xl text-green-600"></i>
                <span class="text-xs font-semibold text-green-700">{{ $stats['completion_rate'] }}%</span>
            </div>
            <div class="text-sm text-green-600 font-semibold">Terminées</div>
            <div class="text-3xl font-bold text-green-700">{{ $stats['completed_tasks'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4 hover:shadow-lg transition">
            <i class="bi bi-hourglass-split text-3xl text-orange-600 mb-2"></i>
            <div class="text-sm text-orange-600 font-semibold">En cours</div>
            <div class="text-3xl font-bold text-orange-700">{{ $stats['in_progress_tasks'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-4 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-exclamation-triangle-fill text-3xl text-red-600"></i>
                @if($stats['overdue_tasks'] > 0)
                    <span class="text-xs font-semibold text-red-700 bg-red-200 px-2 py-1 rounded-full animate-pulse">
                        Urgent !
                    </span>
                @endif
            </div>
            <div class="text-sm text-red-600 font-semibold">En retard</div>
            <div class="text-3xl font-bold text-red-700">{{ $stats['overdue_tasks'] }}</div>
        </div>
    </div>


    {{-- Gantt Chart --}}
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        <div class="relative" style="min-height: 500px;">
            <div id="today-bar" class="absolute top-0 bottom-0 pointer-events-none z-30"
                 style="width: 2px; background: #f43f5e; display: none;"></div>
            <div id="gantt" class="relative min-w-full p-4" wire:ignore></div>
        </div>
    </div>

    {{-- Liste détaillée avec actions --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-list-ul"></i>
                Vue détaillée
            </h3>
            <span class="text-sm text-gray-500">{{ $sprints->count() }} sprint(s) • {{ $epics->count() }} epic(s)</span>
        </div>

        @forelse($sprints as $sprint)
            @php
                $progress = $sprint->computed_progress ?? 0;
                $total = $sprint->computed_total ?? 0;
                $done = $sprint->computed_done ?? 0;
            @endphp

            <div class="bg-white rounded-lg shadow border border-purple-200 p-5 hover:shadow-xl transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="text-lg font-bold text-purple-700">🏃 {{ $sprint->nom }}</h4>
                            @php
                                $isActive = Carbon\Carbon::parse($sprint->end)->isFuture();
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $isActive ? '✓ Actif' : '✓ Terminé' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            <i class="bi bi-calendar3"></i>
                            {{ Carbon\Carbon::parse($sprint->begining)->format('d/m/Y') }}
                            →
                            {{ Carbon\Carbon::parse($sprint->end)->format('d/m/Y') }}
                            <span class="ml-2 text-gray-400">({{ Carbon\Carbon::parse($sprint->begining)->diffInDays($sprint->end) }} jours)</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-purple-700">{{ $progress }}%</div>
                            <div class="text-xs text-gray-500">{{ $done }}/{{ $total }} tâches</div>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 rounded hover:bg-gray-100">
                                <i class="bi bi-three-dots-vertical text-xl"></i>
                            </button>
                            <div x-show="open" @click.outside="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-20">
                                <a href="{{ route('projects.sprints.show', [$project->id, $sprint->id]) }}"
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="bi bi-eye mr-2"></i>Voir détails
                                </a>
                                <a href="{{ route('projects.sprints.edit', [$project->id, $sprint->id]) }}"
                                   class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="bi bi-pencil mr-2"></i>Modifier
                                </a>
                                <button wire:click="deleteSprint({{ $sprint->id }})"
                                        wire:confirm="Supprimer ce sprint ?"
                                        class="block w-full text-left px-4 py-2 hover:bg-red-50 text-red-600">
                                    <i class="bi bi-trash mr-2"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Barre de progression --}}
                <div class="w-full bg-gray-200 rounded-full h-4 mb-4 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full flex items-center justify-center text-[10px] font-bold text-white transition-all duration-700 ease-out"
                         style="width: {{ $progress }}%; background: linear-gradient(to right,#9333ea,#8b5cf6);">
                        @if($progress > 15)
                            {{ $progress }}%
                        @endif
                    </div>
                </div>
                @if($progress <= 15 && $progress > 0)
                    <div class="text-xs text-gray-500 -mt-3 mb-2">{{ $progress }}%</div>
                @endif

                <div class="space-y-2">
                    @foreach($sprint->epics as $epic)
                        @php
                            $epicProgress = $epic->computed_progress ?? 0;
                            $epicDone = $epic->computed_done ?? 0;
                            $epicTotal = $epic->computed_total ?? 0;
                        @endphp
                        <div class="flex items-center gap-3 bg-gray-50 rounded p-3 hover:bg-gray-100 transition group">
                            <div class="flex-1">
                                <div class="font-semibold text-primary">📦 {{ $epic->nom }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-3 mt-1">
                                    <span>
                                        <i class="bi bi-calendar3"></i>
                                        {{ Carbon\Carbon::parse($epic->begining)->format('d/m') }}
                                        →
                                        {{ Carbon\Carbon::parse($epic->end)->format('d/m') }}
                                    </span>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ $epicTotal }} tâche(s)</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-gray-700">{{ $epicProgress }}%</div>
                                <div class="text-xs text-gray-500">{{ $epicDone }}/{{ $epicTotal }}</div>
                            </div>
                            <div class="w-28 bg-gray-200 rounded-full h-2 overflow-hidden shadow-inner">
                                <div class="h-full rounded-full bg-primary transition-all duration-700 ease-out"
                                     style="width: {{ $epicProgress }}%"
                                     title="Epic {{ $epic->nom }} {{ $epicProgress }}%">
                                </div>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 transition">
                                <button wire:click="deleteEpic({{ $epic->id }})"
                                        wire:confirm="Supprimer cet epic ?"
                                        class="p-2 rounded hover:bg-red-100 text-red-600">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow border p-12 text-center">
                <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 mb-4">Aucun sprint trouvé</p>
                <a href="{{ route('projects.sprints.create', ['project' => $project->id]) }}"
                   class="inline-block px-6 py-3 rounded bg-purple-600 text-white hover:bg-purple-700 transition">
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
        ganttEl.innerHTML = '<div class="text-center text-red-500 py-12">Erreur chargement</div>';
        return;
    }

    const loaded = await window.ganttLoadedPromise;
    if (!loaded || !window.Gantt) {
        ganttEl.innerHTML = '<div class="text-center text-red-500 py-12">Gantt non disponible</div>';
        return;
    }

    const tasks = @json($ganttTasks);
    if (tasks.length === 0) {
        ganttEl.innerHTML = '<div class="text-center text-gray-500 py-12">📭 Aucune donnée<br><span class="text-sm">Créez des sprints et epics</span></div>';
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
                const icon = task.type === 'sprint' ? '🏃' : task.type === 'milestone' ? '🚀' : '📦';
                const startDate = formatDate(task.start);
                const endDate = formatDate(task.end);

                let tasksInfo = '';
                if (task.total_tasks !== undefined) {
                    tasksInfo = `
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tâches:</span>
                            <span class="font-semibold">${task.done_tasks}/${task.total_tasks}</span>
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
                                <span class="text-gray-600">Dates:</span>
                                <span class="font-semibold">${startDate} → ${endDate}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Progression:</span>
                                <span class="font-semibold">${task.progress}%</span>
                            </div>
                            ${tasksInfo}
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
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
                    const color = bar.task.color || '#18bcc6';
                    bar.$bar.style.fill = color;
                    bar.$bar.style.stroke = color;

                    if (bar.task.type === 'milestone') {
                        bar.$bar.setAttribute('rx', '50%');
                        bar.$bar.setAttribute('ry', '50%');
                    }
                });
                positionTodayLine();
            }
        }, 300);

        console.log('✅ Gantt initialisé avec succès');
    } catch (error) {
        console.error('❌ Erreur Gantt:', error);
        ganttEl.innerHTML = `<div class="text-center text-red-500 py-12">Erreur: ${error.message}</div>`;
    }
}

function positionTodayLine() {
    const gantt = document.getElementById('gantt');
    if (!gantt) return;

    const today = new Date();
    const dayEls = gantt.querySelectorAll('.grid-header .tick');

    dayEls.forEach(el => {
        if (el.textContent.trim() == today.getDate().toString()) {
            const bar = document.getElementById('today-bar');
            if (bar) {
                bar.style.left = (el.offsetLeft + el.offsetWidth / 2) + 'px';
                bar.style.display = 'block';
            }
        }
    });
}

function centerToday() {
    const gantt = document.getElementById('gantt');
    if (!gantt) return;
    const today = new Date();
    const dayEls = gantt.querySelectorAll('.grid-header .tick');
    dayEls.forEach(el => {
        if (el.textContent.trim() == today.getDate().toString()) {
            gantt.parentElement.scrollLeft = el.offsetLeft - 200;
        }
    });
}

document.addEventListener('livewire:initialized', () => {
    initializeGantt();

    Livewire.on('updateGanttView', (data) => {
        console.log('📡 Événement reçu updateGanttView:', data);
        if (ganttInstance) {
            ganttInstance.change_view_mode(data[0].mode);
            setTimeout(() => {
                positionTodayLine();
                if (data[0].mode === 'Day') {
                    centerToday();
                }
            }, 300);
        } else {
            console.warn('⚠️ Gantt instance non disponible');
        }
    });

    Livewire.on('centerToday', () => {
        console.log('📡 Événement reçu centerToday');
        setTimeout(centerToday, 250);
    });

    Livewire.on('ganttRefresh', () => {
        console.log('📡 Événement reçu ganttRefresh');
        setTimeout(initializeGantt, 200);
    });
});
</script>
@endpush
