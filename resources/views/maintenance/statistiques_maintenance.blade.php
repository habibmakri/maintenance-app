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
            <button class="nav-link" id="panne-tab" data-bs-toggle="tab" data-bs-target="#bordered-panne" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Pannes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="piece-tab" data-bs-toggle="tab" data-bs-target="#bordered-piece" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Pieces</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Agents</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent" style = "font-family: 'Tajwal';">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;margin-bottom:15px;">
                <h5 class="mt-5">Sélectionner le mois et la piece pour la récupération des données:</h5>
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        legend: {},
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

                            ],
                            type: 'bar'
                        }]
                    });
                });
            </script>

            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;margin-bottom:15px;">
                <h5 class="mt-5">Sélectionner le Bus et la piece et l'année pour la récupuration des données:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_pdf') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" id="data_type" value="ligne_bus_mois">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="bus" id="bus"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner le Bus</option>
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                                @endforeach

                            </select>
                            <label for="bus">Bus</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="piece2" id="piece2"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                <option value="Gasoile">Gasoile</option>
                                <option value="Kilometrage">Kilometrage</option>
                                <option value="Gasoile/100">Gasoile/100</option>
                                <option value="Huile 15w40">Huile 15w40</option>
                                <option value="Huile 15w40/Sans vidange">Huile 15w40/Sans vidange</option>
                                <option value="Glaciole">Glaciole/Sans vidange</option>
                            </select>
                            <label for="piece">Piece</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="year2" id="year2"
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
            <div id="lineChart" style="min-height: 400px;" class="echart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    echarts.init(document.querySelector("#lineChart")).setOption({
                        xAxis: {
                            type: 'category',
                            data: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                'Septembre', 'Octobre', 'Novembre', 'Décembre'
                            ]
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            data: [],
                            type: 'line',
                            // smooth: true
                        }]
                    });
                });
            </script>
        </div>
        <div class="tab-pane fade" id="bordered-panne" role="tabpanel" aria-labelledby="panne-tab">
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;margin-bottom:15px;">
                <h5 class="mt-5">Sélectionner la piece et l'année pour la récupuration des données:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_pdf') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" id="data_type" value="ligne_bus_mois">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="piecepanne" id="piecepanne"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                <option value="pdéclarer">Panne Déclarer</option>
                            </select>
                            <label for="piecepanne">Type</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="monthpanne" id="monthpanne"
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
                            <label for="monthpanne">Mois</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="yearpanne" id="yearpanne"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner l'année</option>
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <label for="yearpanne">Année</label>
                        </div>
                    </div>
                </form>
            </div>
            <div id="panneChart" style="min-height: 400px;" class="echart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    echarts.init(document.querySelector("#panneChart")).setOption({
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        legend: {},
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

                            ],
                            type: 'bar'
                        }]
                    });
                });
            </script>
        </div>
        <div class="tab-pane fade" id="bordered-piece" role="tabpanel" aria-labelledby="piece-tab">
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;margin-bottom:15px;">
                <h5 class="mt-5">Sélectionner la piece et l'année pour la récupuration des données:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_pdf') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" id="data_type" value="ligne_bus_mois">
                    <div class="col-md-8">
                        <div class="form-floating">
                            <select class="form-select" required name="piece3" id="piece3"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                <option value="Gasoile">Gasoile</option>
                                <option value="Kilometrage">Kilometrage</option>
                                <option value="Gasoile/100">Gasoile/100</option>
                                <option value="Huile 15w40">Huile 15w40</option>
                                <option value="Huile 15w40/Sans vidange">Huile 15w40/Sans vidange</option>
                                <option value="Glaciole">Glaciole/Sans vidange</option>
                                @foreach ($pieces as $piece)
                                    <option value="{{ $piece->id }}">{{ $piece->name }}</option>
                                @endforeach
                            </select>
                            <label for="piece">Piece</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="year3" id="year3"
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
            <div id="lineChartpiece" style="min-height: 400px;" class="echart"></div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    echarts.init(document.querySelector("#lineChartpiece")).setOption({
                        xAxis: {
                            type: 'category',
                            data: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                'Septembre', 'Octobre', 'Novembre', 'Décembre'
                            ]
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            data: [],
                            type: 'line',
                            // smooth: true
                        }]
                    });
                });
            </script>

        </div>
        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">


        </div>

    </div>

    <script>
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
                fetch(
                        `/app/maintenance/statistiques_data?month=${month}&year=${year}&piece=${piece}&data_type=simple_parbus`
                    )
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const xLabels = data.map(item => item.name_bus);


                        const yValues = data.map(item => item.total_gasoile);
                        const filteredValues = yValues.filter(val => val > 0);
                        const meanValue = filteredValues.length > 0 ?
                            filteredValues.reduce((sum, val) => sum + val, 0) / filteredValues.length :
                            0;

                        const barData = yValues.map(value => ({
                            value,
                            itemStyle: {
                                color: value > 2 * meanValue ? '#FF0000' : (value > meanValue ?
                                    '#FFD700' : '#1f78b4')
                            }
                        }));

                        chart.setOption({
                            title: {
                                text: `${piece} Du ${monthInput.options[monthInput.selectedIndex].text} ${year} - Valeur moyenne ${meanValue.toFixed(2)}`,
                                left: 'center',
                            },
                            tooltip: {
                                trigger: 'axis',
                                // formatter: function(params) {
                                //     const dataValue = params[0].data.value;
                                //     return `${params[0].axisValue} : <strong>${dataValue.toFixed(2)}</strong>`;
                                // }
                                formatter: function(params) { 
                                    if (!params[0].data || params[0].data.value == null) {
                                        return `${params[0].axisValue} : <strong>${params[0].data.value}</strong>`;
                                    }
                                    return `${params[0].axisValue} : <strong>${params[0].data.value.toFixed(2)}</strong>`;
                                }
                            },
                            xAxis: {
                                type: 'category',
                                data: xLabels
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                data: barData,
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
        document.addEventListener("DOMContentLoaded", function() {
            const chart = echarts.init(document.querySelector("#panneChart"));
            const monthInput = document.getElementById("monthpanne");
            const pieceInput = document.getElementById("piecepanne");
            const yearInput = document.getElementById("yearpanne");

            function fetchData() {
                const month = monthInput.value;
                const year = yearInput.value;
                const piece = pieceInput.value;

                if (!month || !year || !piece) return;

                fetch(
                        `/app/maintenance/statistiques_data?month=${month}&year=${year}&piece=${piece}&data_type=simple_parbus`
                    )
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const xLabels = data.map(item => item.name_bus);


                        const electriqueValues = data.map(item => item.total_electrique);
                        const tolleValues = data.map(item => item.total_tolle);
                        const moteurValues = data.map(item => item.total_moteur);

                        chart.setOption({
                            title: {
                                text: `Pannes par Bus - ${monthInput.options[monthInput.selectedIndex].text} ${year}`,
                                // left: 'center',
                            },
                            tooltip: {
                                trigger: 'axis',
                            },
                            xAxis: {
                                type: 'category',
                                data: xLabels
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                    name: 'Électrique',
                                    type: 'bar',
                                    stack: 'total',
                                    data: electriqueValues
                                },
                                {
                                    name: 'Tôle',
                                    type: 'bar',
                                    stack: 'total',
                                    data: tolleValues
                                },
                                {
                                    name: 'Moteur',
                                    type: 'bar',
                                    stack: 'total',
                                    data: moteurValues
                                }
                            ]
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données:', error));
            }

            monthInput.addEventListener('change', fetchData);
            pieceInput.addEventListener('change', fetchData);
            yearInput.addEventListener('change', fetchData);
        });
        document.addEventListener("DOMContentLoaded", function() {
            const chart = echarts.init(document.querySelector("#lineChart"));
            const busInput = document.getElementById("bus");
            const pieceInput = document.getElementById("piece2");
            const yearInput = document.getElementById("year2");

            function fetchData() {
                const year = yearInput.value;
                const piece = pieceInput.value;
                const data_type = pieceInput.value;

                if (!month || !year || !piece || !busInput) return;

                fetch(
                        `/app/maintenance/statistiques_data?month=0&year=${year}&piece=${piece}&data_type=ligne_bus_mois&bus=${busInput.value}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        const selectedBusOption = busInput.querySelector(`option[value="${busInput.value}"]`);
                        const selectedBusText = selectedBusOption ? selectedBusOption.textContent : '';
                        console.log(data);
                        const yValues = Array(12).fill(0);
                        data.forEach(item => {
                            let monthIndex = parseInt(item.month.split('-')[1], 10) - 1;
                            yValues[monthIndex] = item.total;
                        });
                        const totalvals = data.map(item => item.total_gasoile);
                        const total = yValues.reduce((sum, val) => sum + val, 0);

                        chart.setOption({
                            title: {
                                text: piece + ' Du ' + selectedBusText + ' l\'Année ' + year +
                                    ' Total: ' + total.toFixed(2),
                                left: 'center',
                                textAlign: 'center',
                            },
                            tooltip: {
                                trigger: 'axis',
                                formatter: function(params) {
                                    return `${params[0].axisValue} : <strong>${params[0].data.toFixed(2)}</strong>`;
                                }
                            },
                            xAxis: {
                                type: 'category',
                                data: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                                    'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                ]
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                // name:piece,
                                data: yValues,
                                type: 'line',
                                // smooth: true
                            }]
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données:', error));
            }

            busInput.addEventListener('change', fetchData);
            pieceInput.addEventListener('change', fetchData);
            yearInput.addEventListener('change', fetchData);
        });
        /*document.addEventListener("DOMContentLoaded", function() {
            const chart = echarts.init(document.querySelector("#lineChartpiece"));
            const pieceInput = document.getElementById("piece3");
            const yearInput = document.getElementById("year3");

            function fetchData() {
                const year = yearInput.value;
                const piece = pieceInput.value;
                const data_type = pieceInput.value;

                if (!month || !year || !piece || !busInput) return;

                fetch(
                        `/app/maintenance/statistiques_data?year=${year}&piece=${piece}&data_type=ligne_piece_mois`
                    )
                    .then(response => response.json())
                    .then(data => {
                        const selectedBusOption = busInput.querySelector(`option[value="${busInput.value}"]`);
                        const selectedBusText = selectedBusOption ? selectedBusOption.textContent : '';
                        console.log(data);
                        const yValues = Array(12).fill(0);
                        data.forEach(item => {
                            let monthIndex = parseInt(item.month.split('-')[1], 10) - 1;
                            yValues[monthIndex] = item.total;
                        });
                        const totalvals = data.map(item => item.total_gasoile);
                        const total = yValues.reduce((sum, val) => sum + val, 0);

                        chart.setOption({
                            title: {
                                text: piece + ' Du ' + selectedBusText + ' l\'Année ' + year +
                                    ' Total: ' + total.toFixed(2),
                                left: 'center',
                                textAlign: 'center',
                            },
                            tooltip: {
                                trigger: 'axis',
                                formatter: function(params) {
                                    return `${params[0].axisValue} : <strong>${params[0].data.toFixed(2)}</strong>`;
                                }
                            },
                            xAxis: {
                                type: 'category',
                                data: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
                                    'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                                ]
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                // name:piece,
                                data: yValues,
                                type: 'line',
                                // smooth: true
                            }]
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données:', error));
            }

            busInput.addEventListener('change', fetchData);
            pieceInput.addEventListener('change', fetchData);
            yearInput.addEventListener('change', fetchData);
        });*/
    </script>
@endsection
