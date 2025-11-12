@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    @livewire('roadmap', ['project' => $project])
</div>
@endsection
