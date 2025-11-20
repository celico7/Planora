@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white dark:bg-dark-card rounded-lg shadow-lg dark:shadow-none border dark:border-dark-border p-6">
    @livewire('roadmap', ['project' => $project])
</div>
@endsection
