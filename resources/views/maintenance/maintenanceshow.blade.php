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
                        let i=0;
                        data.data.forEach(item => {
                            i++;
                            rows += `
                                <tr>
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
