@extends('base')

@section('title', 'Extraire')

@section('content')
    <style>
        /* @font-face {
                                font-family: 'lateef';
                                src: url('{{ asset('theme/fonts/lateef/Lateef-Regular.ttf') }}') format('truetype');
                                font-weight: normal;
                                font-style: normal;
                            } */
        label {
            inset-inline-end: auto !important;
        }
    </style>
    <div class="pagetitle">
        <h1>Statistiques Maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Statistiques</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">BUS</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Pieces</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Agents</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent" style = "font-family: 'Tajwal';">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le mois et la piece pour l'extraction de l'état:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="piece" id="piece"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                <option value="Gasoile">Gasoile</option>
                                <option value="Kilometrage">Kilometrage</option>
                                <option value="Gasoile/100">Gasoile/100</option>
                                <option value="Huile 15w40">Huile 15w40</option>
                                <option value="Huile 15w40/Sans vidange">Huile 15w40/Sans vidange</option>
                                <option value="Glaciole">Glaciole</option>
                            </select>
                            <label for="piece">Piece</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select" required name="month" id="month"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner le mois</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                            <label for="month">Mois</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select" required name="year" id="year"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner l'année</option>
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <label for="year">Année</label>
                        </div>
                    </div>
                </form>
            </div>

            <div id="barChart" style="min-height: 400px;" class="echart"></div>
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    echarts.init(document.querySelector("#barChart")).setOption({
                        xAxis: {
                            type: 'category',
                            data: [
                                'A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07', 'A08', 'A09', 'A10',
                                'A11', 'A12', 'A13', 'A14', 'A15', 'A16', 'A17', 'A18', 'A19', 'A20',
                                'A21', 'A22', 'A23', 'A24', 'A25', 'A26', 'A27', 'A28', 'A29', 'A30',
                                'A31', 'A32', 'A33', 'A34'
                            ]
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            data: [
                                0,
                                {
                                    value: 0,
                                    itemStyle: {
                                        color: '#a90000'
                                    }
                                },
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0,
                                0
                            ],
                            type: 'bar'
                        }]
                    });
                });
            </script>


        </div>

        <div class="tab-pane fade" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">

        </div>
        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">


        </div>

    </div>


    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chart = echarts.init(document.querySelector("#barChart"));
            const monthInput = document.getElementById("month");
            const pieceInput = document.getElementById("piece");
            const yearInput = document.getElementById("year");

            function fetchData() {
                const month = monthInput.value;
                const year = yearInput.value;
                const piece = pieceInput.value;

                if (!month || !year || !piece) return;

                fetch(`/app/maintenance/statistiques_data?month=${month}&year=${year}&piece=${piece}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        const xLabels = data.map(item => `A${item.id_bus.toString().padStart(2, '0')}`);
                        const yValues = data.map(item => item.total_gasoile);

                        chart.setOption({
                            xAxis: {
                                type: 'category',
                                data: xLabels
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                data: yValues.map((value, index) => ({
                                    value,
                                    itemStyle: value === 0 ? {
                                        color: '#a90000'
                                    } : {}
                                })),
                                type: 'bar'
                            }]
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données:', error));
            }

            monthInput.addEventListener('change', fetchData);
            pieceInput.addEventListener('change', fetchData);
            yearInput.addEventListener('change', fetchData);
        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const chart = echarts.init(document.querySelector("#barChart"));
            const monthInput = document.getElementById("month");
            const pieceInput = document.getElementById("piece");
            const yearInput = document.getElementById("year");
    
            function fetchData() {
                const month = monthInput.value;
                const year = yearInput.value;
                const piece = pieceInput.value;
    
                if (!month || !year || !piece) return;
    
                fetch(`/app/maintenance/statistiques_data?month=${month}&year=${year}&piece=${piece}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const xLabels = data.map(item => `A${item.id_bus.toString().padStart(2, '0')}`);
                        const yValues = data.map(item => item.total_gasoile);
                        const meanValue = yValues.reduce((sum, val) => sum + val, 0) / yValues.length;
                        const barData = yValues.map(value => ({
                            value,
                            itemStyle: {
                                color: value > meanValue ? '#FFD700' : '#1f78b4'
                            }
                        }));
                        chart.setOption({
                            xAxis: { type: 'category', data: xLabels },
                            yAxis: { type: 'value' },
                            series: [{ data: barData, type: 'bar' }]
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données:', error));
            }
    
            monthInput.addEventListener('change', fetchData);
            pieceInput.addEventListener('change', fetchData);
            yearInput.addEventListener('change', fetchData);
        });
    </script>
@endsection
