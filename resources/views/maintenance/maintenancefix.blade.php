@extends('base')

@section('title', 'Modification')

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
        <h1>Modification des données de maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Modifier</li>
            </ol>
        </nav>
    </div>
    <form class="row g-3" action="" method="post">
        <div class="col-md-12">
            <div class="form-floating">
                <input name="date" type="date" required class="form-control">
                <label for="date">Jour</label>
            </div>
        </div>
    </form>
    <table class="table mt-5">
        <thead>
            <tr>
                <th>N°</th>
                <th data-type="date" data-format="MM/DD/YYYY">Date</th>
                <th>bus</th>
                <th>Brigade</th>
                <th>Ligne</th>
                <th>H.depart</th>
                <th>KM.global</th>
                <th>Actions</th>
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
            </tr>
        </tbody>
    </table>
    <div id="notification" class="notification hidden">
        <p id="notification-message"></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateduInput = document.querySelector('input[name="date"]');
            const maintenanceTableBody = document.querySelector('tbody');

            function fetchData() {
                const date = dateduInput.value;

                if (!date) return;

                fetch(`/app/maintenance/refreshfixtable?date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        let rows = '';
                        console.log(data)
                        let i = 0;
                        data.data.forEach(item => {
                            i++;
                            rows += `
                                <tr>
                                    <td>${i}</td>
                                    <td>${item.date_fiche}</td>
                                    <td>${item.bus}</td>
                                    <td>${item.brigade}</td>
                                    <td>${item.ligne}</td>
                                    <td>${item.heur_depart}</td>
                                    <td>${item.kmcommerciale}</td>
                                    <td>
                                     <i class="bi bi-pencil edit-icon" data-id="${item.id}" style="margin-right:15%;cursor: pointer;"></i>     
                                     <i class="bi bi-trash delete-icon" data-id="${item.id}" style="cursor: pointer;"></i>     
                                    </td>
                                </tr>

                            `;
                        });
                        maintenanceTableBody.innerHTML = rows;
                        attachRowEventListeners();

                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function attachRowEventListeners() {
                const editIcons = document.querySelectorAll('.edit-icon');
                const deleteIcons = document.querySelectorAll('.delete-icon');

                editIcons.forEach(icon => {
                    icon.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        window.location.href =
                            `/app/editfiche:${id}`;
                    });
                });

                deleteIcons.forEach(icon => {
                    icon.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        if (confirm('Vous êtes sur?')) {
                            fetch(`maintenance/deletefiche:${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // alert('Operation Reussit!');
                                        fetchData();
                                    } else {
                                        alert('Operation echoué!');
                                    }
                                })
                                .catch(error => console.error('Erreur:', error));
                        }
                    });
                });
            }

            dateduInput.addEventListener('change', fetchData);
        });
    </script>



@endsection
