@extends('base')

@section('title', 'Extraction ')

@section('content')

    <div class="pagetitle">
        <h1>Extraction des données de maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Extraire</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">Tableau</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">PDF</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-contact" type="button"
                role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Excel</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <h5 class="mt-2">Selectionner la date:</h5>
            <form class="row g-3" action="" method="post">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input name="datedu" type="date" required class="form-control">
                        <label for="datedu">Du</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input name="dateau" type="date" required class="form-control">
                        <label for="dateau">Au</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" required name="brigade" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled selected>selectionner brigade</option>
                            <option value="jour">Jour</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigade">Brigade</label>
                    </div>
                </div>
            </form>
            <table class="table mt-5">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>bus</th>
                        <th>Ligne</th>
                        <th>H.depart</th>
                        <th>H.Arrivée</th>
                        <th>gasoile</th>
                        <th>KM.global</th>
                        <th>KM.Commerciale</th>
                        <th>Brigade</th>
                        <th data-type="date" data-format="YYYY/DD/MM">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            <h5 class="mt-2">Selectionner la date:</h5>
            <form class="row g-3" action="{{ route('app.maintenance.pdf') }}" method="post">
                @csrf
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="datedupdf" type="date" required class="form-control">
                        <label for="datedupdf">Du</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="dateaupdf" type="date" required class="form-control">
                        <label for="dateaupdf">Au</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" required name="brigadepdf" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled selected>selectionner brigade</option>
                            <option value="jour">Jour</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigadepdf">Brigade</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary col-md-3">Télecharger</button>
            </form>
        </div>
        <div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
            <h5 class="mt-2">Selectionner la date:</h5>
            <form class="row g-3" action="{{ route('app.maintenance.pdf') }}" method="post">
                @csrf
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="datedupdf" type="date" required class="form-control">
                        <label for="datedupdf">Du</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="dateaupdf" type="date" required class="form-control">
                        <label for="dateaupdf">Au</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" required name="brigadepdf" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled selected>selectionner brigade</option>
                            <option value="jour">Jour</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigadepdf">Brigade</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success col-md-3">Télecharger</button>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateduInput = document.querySelector('input[name="datedu"]');
            const dateauInput = document.querySelector('input[name="dateau"]');
            const brigadeSelect = document.getElementById('brigade');
            const maintenanceTableBody = document.querySelector('tbody');

            function fetchData() {
                const datedu = dateduInput.value;
                const dateau = dateauInput.value;
                const brigade = brigadeSelect.value;

                if (!datedu || !dateau || !brigade) return;

                fetch(`/app/maintenance/refreshfichtable?datedu=${datedu}&dateau=${dateau}&brigade=${brigade}`)
                    .then(response => response.json())
                    .then(data => {
                        let rows = '';
                        let i = 0;
                        data.data.forEach(item => {
                            i++;
                            if (item.ligne === "/") {
                                rows += `
                                <tr style="border-color: red;">
                                    <td>${i}</td>
                                    <td>${item.bus}</td>
                                    <td>${item.ligne}</td>
                                    <td>${item.heur_depart}</td>
                                    <td>${item.heur_arrive}</td>
                                    <td>${item.gasoile}</td>
                                    <td>${item.kmgobale}</td>
                                    <td>${item.kmcommerciale}</td>
                                    <td>${item.brigade}</td>
                                    <td>${item.date_fiche}</td>
                                    </tr>
                                    
                                    `;
                            } else {
                                rows += `
                                    <tr style="border-color: green;">
                                        <td>${i}</td>
                                        <td>${item.bus}</td>
                                        <td>${item.ligne}</td>
                                        <td>${item.heur_depart}</td>
                                        <td>${item.heur_arrive}</td>
                                        <td>${item.gasoile}</td>
                                        <td>${item.kmgobale}</td>
                                        <td>${item.kmcommerciale}</td>
                                        <td>${item.brigade}</td>
                                        <td>${item.date_fiche}</td>
                                    </tr>
    
                                `;
                            }
                        });
                        maintenanceTableBody.innerHTML = rows;

                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Add event listeners to trigger fetch on change
            dateduInput.addEventListener('change', fetchData);
            dateauInput.addEventListener('change', fetchData);
            brigadeSelect.addEventListener('change', fetchData);
        });
    </script>



@endsection
