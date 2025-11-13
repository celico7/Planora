@extends('layouts.app')

@section('title', 'Recherche de tâches')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-6">
    @livewire('task-search')
</div>
@endsection
