@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-bell-fill text-primary mr-3"></i>
                Notifications
                @if($unreadCount > 0)
                <span class="ml-3 px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded-full">
                    {{ $unreadCount }}
                </span>
                @endif
            </h1>
            @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition text-sm font-semibold">
                    <i class="bi bi-check-all mr-2"></i>Tout marquer comme lu
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center">
            <i class="bi bi-check-circle-fill mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="space-y-3">
            @forelse($notifications as $notification)
            <div class="p-4 rounded-lg border transition {{ $notification->read_at ? 'bg-gray-50 border-gray-200' : 'bg-blue-50 border-blue-200' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            @php
                                $icon = match($notification->data['type'] ?? 'default') {
                                    'retard' => 'bi-exclamation-triangle-fill text-red-500',
                                    'proche' => 'bi-clock-fill text-yellow-500',
                                    default => 'bi-info-circle-fill text-blue-500'
                                };
                            @endphp
                            <i class="bi {{ $icon }} text-2xl"></i>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if(isset($notification->data['project_nom']))
                        <div class="ml-11 text-sm text-gray-600 space-y-1">
                            <p><i class="bi bi-folder-fill text-primary mr-2"></i>Projet : <span class="font-medium">{{ $notification->data['project_nom'] }}</span></p>
                            <p><i class="bi bi-calendar3 text-primary mr-2"></i>Sprint : <span class="font-medium">{{ $notification->data['sprint_nom'] }}</span></p>
                            @if(isset($notification->data['echeance']))
                            <p><i class="bi bi-alarm-fill text-primary mr-2"></i>Échéance : <span class="font-medium">{{ $notification->data['echeance'] }}</span></p>
                            @endif
                            @if(isset($notification->data['changes']))
                            <div class="mt-2 p-2 bg-white rounded border border-gray-200">
                                <p class="font-semibold text-xs text-gray-700 mb-1">Modifications :</p>
                                @foreach($notification->data['changes'] as $key => $change)
                                <p class="text-xs text-gray-600">• {{ $key }} : {{ $change }}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @if(!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-xs bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold">
                                <i class="bi bi-check"></i> Marquer lu
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer cette notification ?')" class="px-3 py-1 text-xs bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune notification pour le moment</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
