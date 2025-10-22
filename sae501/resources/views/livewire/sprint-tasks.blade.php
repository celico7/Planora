<div>
    <h3>Progression du sprint</h3>
    <div class="progress mb-3" style="height: 25px;">
        <div class="progress-bar bg-success" style="width: {{ $progress }}%">
            {{ $progress }}%
        </div>
    </div>

    <ul>
        <li>✅ Terminées : {{ $done }}</li>
        <li>⚙️ En cours : {{ $inProgress }}</li>
        <li>📝 À faire : {{ $todo }}</li>
    </ul>

    <hr>
    <h3>Tâches du sprint</h3>
    <ul>
        @foreach($tasks as $task)
            <li>
                <strong>{{ $task->nom }}</strong> —
                <select wire:change="updateStatut({{ $task->id }}, $event.target.value)">
                    <option value="à faire" {{ $task->statut == 'à faire' ? 'selected' : '' }}>À faire</option>
                    <option value="en cours" {{ $task->statut == 'en cours' ? 'selected' : '' }}>En cours</option>
                    <option value="terminé" {{ $task->statut == 'terminé' ? 'selected' : '' }}>Terminée</option>
                </select>
                <br>
                {{ $task->description }}
            </li>
        @endforeach
    </ul>
</div>
