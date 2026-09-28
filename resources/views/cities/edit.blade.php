@extends('layouts.app')

@section('title', 'Település módosítása')

@section('content')

<div class="card">

    <div class="page-header">
        <h1>Település módosítása</h1>

        <a href="{{ route('cities.index') }}" class="btn btn-secondary">
            Vissza
        </a>
    </div>

    <form method="POST" action="{{ route('cities.update', $city) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="zip_code">Irányítószám</label>
            <input
                type="number"
                id="zip_code"
                name="zip_code"
                min="1000"
                max="9999"
                value="{{ old('zip_code', $city->zip_code) }}"
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
                value="{{ old('city', $city->city) }}"
                required
            >
            @error('city')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="id_county">Megye</label>
            <select id="id_county" name="id_county" required>
                @foreach($counties as $county)
                    <option
                        value="{{ $county->id }}"
                        {{ old('id_county', $city->id_county) == $county->id ? 'selected' : '' }}
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
                value="{{ old('population', $city->population) }}"
                required
            >
            @error('population')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Mentés
        </button>

        <a href="{{ route('cities.index') }}" class="btn btn-secondary">
            Mégse
        </a>
    </form>

</div>

@endsection
