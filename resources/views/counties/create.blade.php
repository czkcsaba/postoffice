@extends('layouts.app')

@section('title', 'Új megye')

@section('content')

<div class="card">

    <div class="page-header">
        <h1>Új megye</h1>

        <a href="{{ route('counties.index') }}" class="btn btn-secondary">
            Vissza
        </a>
    </div>

    <form method="POST" action="{{ route('counties.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Megye neve</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="50"
                value="{{ old('name') }}"
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
                value="{{ old('badge') }}"
                placeholder="https://..."
                required
            >
            @error('badge')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <p style="color:#6b7280;font-size:14px;">
            Az új megye lakossága kezdetben 0 fő. A hozzáadott települések alapján automatikusan változik.
        </p>

        <button type="submit" class="btn btn-success">
            Létrehozás
        </button>

        <a href="{{ route('counties.index') }}" class="btn btn-secondary">
            Mégse
        </a>
    </form>

</div>

@endsection
