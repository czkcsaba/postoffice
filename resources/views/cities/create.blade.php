@extends('layouts.app')

@section('title', 'Új település')

@section('content')

<div class="card">

    <div class="page-header">
        <h1>Új település</h1>

        <a href="{{ route('cities.index') }}" class="btn btn-secondary">
            Vissza
        </a>
    </div>

    <form method="POST" action="{{ route('cities.store') }}">
        @csrf

        <div class="form-group">
            <label for="zip_code">Irányítószám</label>
            <input
                type="number"
                id="zip_code"
                name="zip_code"
                min="1000"
                max="9999"
                value="{{ old('zip_code') }}"
                required
            >
            @error('zip_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="city">Település neve</label>
            <input
                type="text"
                id="city"
                name="city"
                maxlength="50"
                value="{{ old('city') }}"
                required
            >
            @error('city')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="id_county">Megye</label>
            <select id="id_county" name="id_county" required>
                <option value="">Válassz megyét...</option>

                @foreach($counties as $county)
                    <option
                        value="{{ $county->id }}"
                        {{ old('id_county') == $county->id ? 'selected' : '' }}
                    >
                        {{ $county->name }}
                    </option>
                @endforeach
            </select>

            @error('id_county')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="population">Lakosságszám</label>
            <input
                type="number"
                id="population"
                name="population"
                min="0"
                value="{{ old('population') }}"
                required
            >
            @error('population')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            Létrehozás
        </button>

        <a href="{{ route('cities.index') }}" class="btn btn-secondary">
            Mégse
        </a>
    </form>

</div>

@endsection
