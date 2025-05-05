@extends('base')

@section('title', 'Validation')

@section('content')

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
    <div class="pagetitle">
        <h1>Validation des données de maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Valider</li>
            </ol>
        </nav>
    </div>



    <div class="row g-3" action="" method="">
        @csrf
        <div class="col-md-7">
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
        <div class="col-md-5">
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
    </div>

    <div style="display: flex;  margin: auto; margin-top: 40px; gap: 20px;">
        <div style="flex: 0 0 60%;">
            <div
                style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;max-width:700px;margin-left:100px;">
                @for ($i = 1; $i <= 31; $i++)
                    <div id="day{{ $i }}"
                        style="
                            text-align: center;
                            font-size: 24px;
                            height: 78px;
                            font-family: Poppins;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: 900;
                            border: 1px solid black;
                            transition: 0.7s;
                            cursor: pointer;
                        ">
                        {{ $i }}
                    </div>
                @endfor
            </div>
        </div>
        <div style="flex: 0 0 40%;">
            <h4>Détails du jour</h4>
            <p>Sélectionnez un jour pour voir plus d'informations.</p>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthInput = document.getElementById('month');
            const yearInput = document.getElementById('year');
            const actualisé = document.getElementById('actualisé');

            function resetDays(totalDays) {
                for (let i = 1; i <= 31; i++) {
                    const dayDiv = document.getElementById(`day${i}`);
                    if (dayDiv) {
                        if (i > totalDays) {
                            dayDiv.style.backgroundColor = '#dddddd';
                            dayDiv.style.opacity = '0.5';
                            dayDiv.style.pointerEvents = 'none';
                        } else {
                            dayDiv.style.backgroundColor = 'white';
                            dayDiv.style.opacity = '1';
                            dayDiv.style.pointerEvents = 'auto';
                        }
                    }
                }
            }

            function fetchData(event) {
                event.preventDefault();

                const month = monthInput.value;
                const year = yearInput.value;

                if (!month || !year) return;

                fetch(`/app/maintenance/refresh_validate?month=${month}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        const totalDays = new Date(year, month, 0).getDate();
                        resetDays(totalDays);
                        data.forEach(entry => {
                            const date = new Date(entry.day);
                            const dayNumber = date.getDate();
                            const dayDiv = document.getElementById(`day${dayNumber}`);
                            if (dayDiv) {
                                if (entry.validated) {
                                    dayDiv.style.backgroundColor = 'green';
                                } else if (entry.fiches) {
                                    dayDiv.style.backgroundColor = 'yellow';
                                } else {
                                    dayDiv.style.backgroundColor = 'white';
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des données :', error));
            }
            monthInput.addEventListener('change', fetchData);
            yearInput.addEventListener('change', fetchData);
            // actualisé.addEventListener('click', fetchData);
        });
    </script>
@endsection
