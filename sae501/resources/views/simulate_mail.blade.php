@extends('layouts.app')

@section('title', 'Simulation Email')

@section('content')
<h2 class="text-2xl font-bold text-green-600 mb-6">Simulation du mail de vérification</h2>

<button id="show-mail" class="bg-green-600 px-4 py-2 rounded">Voir le mail</button>

<div id="mail-modal" style="display:none;position:fixed;top:10%;left:10%;width:80%;height:80%;background:white;border:1px solid #ccc;padding:20px;overflow:auto;z-index:9999;">
</div>

<script>
document.getElementById('show-mail').addEventListener('click', async () => {
    const res = await fetch('/simulate-mail-content'); 
    const html = await res.text();
    const modal = document.getElementById('mail-modal');
    modal.innerHTML = html;
    modal.style.display = 'block';
});
</script>
@endsection
