@extends('layouts.app')

@section('title', 'Települések')

@section('content')

<div class="card">

    <div class="page-header">
        <div>
            <h1>Települések</h1>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            <a
                href="{{ route('counties.index') }}"
                class="btn btn-secondary"
            >
                Megyék
            </a>

            <a
                href="{{ route('cities.create') }}"
                class="btn btn-success"
            >
                + Új település
            </a>

        </div>
    </div>


    <form
        method="GET"
        action="{{ route('cities.index') }}"
        class="filters"
    >

        <input
            type="text"
            name="search"
            placeholder="Keresés..."
            value="{{ request('search') }}"
        >

        <select name="county">
            <option value="">
                Minden megye
            </option>

            @foreach($counties as $county)
                <option
                    value="{{ $county->id }}"
                    {{ request('county') == $county->id ? 'selected' : '' }}
                >
                    {{ $county->name }}
                </option>
            @endforeach
        </select>

        <select name="per_page">
            <option value="25" {{ request('per_page', 50) == 25 ? 'selected' : '' }}>
                25 / oldal
            </option>

            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>
                50 / oldal
            </option>

            <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>
                100 / oldal
            </option>

            <option value="200" {{ request('per_page', 50) == 200 ? 'selected' : '' }}>
                200 / oldal
            </option>
        </select>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Keresés
        </button>

        @if(request('search') || request('county') || request('per_page'))
            <a
                href="{{ route('cities.index') }}"
                class="btn btn-secondary"
            >
                Szűrés törlése
            </a>
        @endif

    </form>


    <div style="overflow-x:auto;">

        <table>

            <thead>
                <tr>
                    <th>Irányítószám</th>
                    <th>Település</th>
                    <th>Megye</th>
                    <th>Címer</th>
                    <th>Lakosság</th>
                    <th>Műveletek</th>
                </tr>
            </thead>

            <tbody>

                @forelse($cities as $city)

                    <tr>

                        <td>
                            {{ $city->zip_code }}
                        </td>

                        <td>
                            <strong>
                                {{ $city->city }}
                            </strong>
                        </td>

                        <td>
                            {{ $city->county?->name ?? 'Nincs megadva' }}
                        </td>

                        <td>

                            @if($city->county && $city->county->badge)

                                <img
                                    src="{{ $city->county->badge }}"
                                    alt="{{ $city->county->name }} címere"
                                    style="
                                        width:48px;
                                        height:58px;
                                        object-fit:contain;
                                        display:block;
                                    "
                                >

                            @else

                                -

                            @endif

                        </td>

                        <td>
                            {{ number_format($city->population, 0, ',', ' ') }} fő
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="{{ route('cities.edit', $city) }}"
                                    class="btn btn-warning"
                                >
                                    Módosítás
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('cities.destroy', $city) }}"
                                    onsubmit="return confirm('Biztosan törölni szeretnéd ezt a települést?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Törlés
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px;">
                            Nincs találat.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($cities->hasPages())

        @php
            $currentPage = $cities->currentPage();
            $lastPage = $cities->lastPage();

            $startPage = max(1, $currentPage - 2);
            $endPage = min($lastPage, $startPage + 4);

            if (($endPage - $startPage) < 4) {
                $startPage = max(1, $endPage - 4);
            }
        @endphp


        <div class="pagination-wrapper">

            <div class="custom-pagination">

                @if($cities->onFirstPage())

                    <span class="page-arrow disabled">
                        ‹
                    </span>

                @else

                    <a
                        href="{{ $cities->previousPageUrl() }}"
                        class="page-arrow"
                        title="Előző oldal"
                    >
                        ‹
                    </a>

                @endif


                @for($page = $startPage; $page <= $endPage; $page++)

                    @if($page == $currentPage)

                        <span class="page-number active">
                            {{ $page }}
                        </span>

                    @else

                        <a
                            href="{{ $cities->url($page) }}"
                            class="page-number"
                        >
                            {{ $page }}
                        </a>

                    @endif

                @endfor


                @if($cities->hasMorePages())

                    <a
                        href="{{ $cities->nextPageUrl() }}"
                        class="page-arrow"
                        title="Következő oldal"
                    >
                        ›
                    </a>

                @else

                    <span class="page-arrow disabled">
                        ›
                    </span>

                @endif

            </div>


            <form
                method="GET"
                action="{{ route('cities.index') }}"
                class="jump-form"
            >

                @if(request('search'))
                    <input
                        type="hidden"
                        name="search"
                        value="{{ request('search') }}"
                    >
                @endif

                @if(request('county'))
                    <input
                        type="hidden"
                        name="county"
                        value="{{ request('county') }}"
                    >
                @endif

                <input
                    type="hidden"
                    name="per_page"
                    value="{{ request('per_page', 50) }}"
                >

                <span>
                    Ugrás oldalra:
                </span>

                <input
                    type="number"
                    name="page"
                    min="1"
                    max="{{ $cities->lastPage() }}"
                    value="{{ $cities->currentPage() }}"
                    class="page-jump-input"
                >

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Ugrás
                </button>

            </form>

        </div>

    @endif


    <div
        style="
            margin-top:18px;
            color:#6b7280;
            font-size:14px;
            text-align:center;
        "
    >
        {{ $cities->firstItem() ?? 0 }}
        –
        {{ $cities->lastItem() ?? 0 }}

        /

        {{ $cities->total() }}

        település
    </div>

</div>


<style>

    .pagination-wrapper {
        margin-top: 28px;

        display: flex;
        justify-content: center;
        align-items: center;

        gap: 20px;

        flex-wrap: wrap;
    }


    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;

        gap: 6px;

        flex-wrap: wrap;
    }


    .page-number,
    .page-arrow {
        min-width: 38px;
        height: 38px;

        padding: 0 10px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border-radius: 9px;

        border: 1px solid #d6c4b2;

        background: white;

        color: #5f0f40;

        text-decoration: none;

        font-size: 14px;
        font-weight: bold;

        transition: 0.2s ease;
    }


    .page-number:hover,
    .page-arrow:hover {
        background: #fdf1e6;

        border-color: #9a031e;

        transform: translateY(-1px);
    }


    .page-number.active {
        background: #5f0f40;

        border-color: #5f0f40;

        color: white;
    }


    .page-arrow {
        font-size: 24px;

        line-height: 1;
    }


    .page-arrow.disabled {
        opacity: 0.35;

        cursor: default;

        pointer-events: none;
    }


    .jump-form {
        display: flex;
        align-items: center;

        gap: 8px;

        flex-wrap: wrap;
    }


    .page-jump-input {
        width: 80px;

        text-align: center;
    }


    @media (max-width: 700px) {

        .pagination-wrapper {
            flex-direction: column;

            gap: 15px;
        }


        .page-number,
        .page-arrow {
            min-width: 34px;

            height: 34px;

            padding: 0 8px;

            font-size: 13px;
        }


        .page-arrow {
            font-size: 21px;
        }

    }

</style>

@endsection