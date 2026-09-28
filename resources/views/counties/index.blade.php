@extends('layouts.app')

@section('title', 'Megyék')

@section('content')

<div class="card">

    <div class="page-header">
        <div>
            <h1>Megyék</h1>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('cities.index') }}" class="btn btn-secondary">
                Települések
            </a>

            <a href="{{ route('counties.create') }}" class="btn btn-success">
                + Új megye
            </a>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Megye</th>
                    <th>Címer</th>
                    <th>Lakosság</th>
                    <th>Települések</th>
                    <th>Műveletek</th>
                </tr>
            </thead>

            <tbody>
                @forelse($counties as $county)
                    <tr>
                        <td>
                            <strong>{{ $county->name }}</strong>
                        </td>

                        <td>
                            @if($county->badge)
                                <img
                                    src="{{ $county->badge }}"
                                    alt="{{ $county->name }} címere"
                                    style="width:50px;height:60px;object-fit:contain;display:block;"
                                >
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ number_format($county->population, 0, ',', ' ') }} fő
                        </td>

                        <td>
                            {{ $county->cities_count }}
                        </td>

                        <td>
                            <div class="actions">

                                <a
                                    href="{{ route('counties.edit', $county) }}"
                                    class="btn btn-warning"
                                >
                                {{ __('buttons.edit') }}
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('counties.destroy', $county) }}"
                                    onsubmit="return confirm('Biztosan törölni szeretnéd ezt a megyét?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                        {{ $county->cities_count > 0 ? 'disabled' : '' }}
                                        title="{{ $county->cities_count > 0 ? 'Előbb a hozzá tartozó településeket kell törölni vagy áthelyezni.' : '' }}"
                                    >
                                        Törlés
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:30px;">
                            Nincs megye.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
