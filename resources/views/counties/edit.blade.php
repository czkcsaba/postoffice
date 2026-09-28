@extends('layouts.app')

@section('title', 'Megye módosítása')

@section('content')

<div class="card">

    <div class="page-header">
        <h1>Megye módosítása</h1>

        <a href="{{ route('counties.index') }}" class="btn btn-secondary">
            Vissza
        </a>
    </div>

    <form method="POST" action="{{ route('counties.update', $county) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Megye neve</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="50"
                value="{{ old('name', $county->name) }}"
                required
            >
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="badge">Címer URL</label>
            <input
                type="url"
                id="badge"
                name="badge"
                maxlength="2048"
                value="{{ old('badge', $county->badge) }}"
                required
            >
            @error('badge')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:20px;padding:15px;background:#f8fafc;border-radius:10px;">
            <strong>Jelenlegi lakosság:</strong>
            {{ number_format($county->population, 0, ',', ' ') }} fő
            <br>
            <small style="color:#6b7280;">
                Ezt nem kell kézzel módosítani, a településekből számolódik.
            </small>
        </div>

        @if($county->badge)
            <div style="margin-bottom:20px;">
                <strong>Címer előnézet:</strong><br><br>
                <img
                    src="{{ $county->badge }}"
                    alt="{{ $county->name }} címere"
                    style="width:90px;height:105px;object-fit:contain;"
                >
            </div>
        @endif

        <button type="submit" class="btn btn-primary">
            Mentés
        </button>

        <a href="{{ route('counties.index') }}" class="btn btn-secondary">
            Mégse
        </a>
    </form>

</div>

@endsection
