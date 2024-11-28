@extends('base')

@section('title', 'Saisie')

@section('content')
    <div class="pagetitle">
        <h1>Modification du Bus {{ $record->bus->name }} du {{ $record->brigade }} du
            {{ \Carbon\Carbon::parse($record->date_fiche)->format('d/m/Y') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item">Modifier</li>
                <li class="breadcrumb-item active">editer</li>
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

    <form class="row g-3" action="" method="post">
        @csrf
        <div class="col-md-7">
            <div class="form-floating">
                <input name="date" type="date" required class="form-control" value="{{ $record->date_fiche }}"
                    disabled>
                <label for="date">Date</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-floating">
                <select class="form-select" required name="brigade" id="brigade"
                    aria-label="Floating label select example" value="{{ $record->brigade }}" disabled>
                    <option value=""> {{ $record->brigade }}</option>
                </select>
                <label for="brigade">Brigade</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" name="bus" id="bus" required
                    aria-label="Floating label select example" value="{{ $record->id_bus }}" disabled>
                    <option value="{{ $record->id_bus }}">{{ $record->bus->name }}</option>

                </select>
                <label for="bus">BUS</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" name="partit" id="partit" required
                    aria-label="Floating label select example">
                    @if ($record->ligne)
                        <option value="oui"  disabled selected>Oui</option>
                    @else
                        <option value="non"  disabled selected>Non</option>
                    @endif
                    <option value="oui">Oui</option>
                    <option value="non">Non</option>
                </select>
                <label for="partit">Partit?</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="ligne" id="ligne"
                    aria-label="Floating label select example">
                    @if ($record->ligne)
                        <option id="{{ $record->id_ligne }}" name="/" disabled selected>{{ $record->ligne->name }}
                        </option>
                    @else
                        <option disabled selected>/</option>
                    @endif
                    @foreach ($lines as $line)
                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                    @endforeach
                </select>
                <label for="ligne">LIGNE</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="time" required class="form-control" name="hdepart" id="hdepart"
                    value="{{ $record->heur_depart }}">
                <label for="hdepart">Heure de départ</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="time" required class="form-control" name="harrive" id="harrive"
                    value="{{ $record->heur_arrive }}">
                <label for="harrive">Heure d'arrivée</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="number" required class="form-control" name="gasoile" id="gasoile"
                    value ="{{ $record->gasoile }}">
                <label for="gasoile">Consomation Gasoile(L)</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="number" required class="form-control" name="kmhlp" id="kmhlp"
                    value ="{{ $record->kmhlp }}">
                <label for="kmhlp">Kilométrage (HLP)</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="number" required class="form-control" name="kmdepart" id="kmdepart"
                    value ="{{ $record->kmdepart }}">
                <label for="kmdepart">Kilométrage (Départ)</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="number" required class="form-control" name="kmarive" id="kmarive"
                    value ="{{ $record->kmarrive }}">
                <label for="kmarive">Kilométrage (Arrivée)</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" disabled class="form-control" name="kmglobale" id="kmglobale"
                    value ="{{ $record->kmgobale }}">
                <label for="kmglobale">Kilométrage (Globale)</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" disabled class="form-control" name="kmcommerciale" id="kmcommerciale"
                    value ="{{ $record->kmcommerciale }}">
                <label for="kmcommerciale">Kilométrage (Commerciale)</label>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Valider</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
        <div id="bus-form-container" class="row"></div>
    </form>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[name="date"]');
            const brigadeSelect = document.getElementById('brigade');
            const busSelect = document.getElementById('bus');

            function fetchAndFilterBuses() {
                const selectedDate = dateInput.value;
                const selectedBrigade = brigadeSelect.value;

                if (!selectedDate || !selectedBrigade) return; 
                fetch(`/app/maintenance/check-buses?date=${selectedDate}&brigade=${selectedBrigade}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        
                       
                        const allOptions = Array.from(busSelect.options);
                        busSelect.innerHTML = ''; 

                        
                        allOptions.forEach(option => {
                            const busId = parseInt(option.value, 10); 
                            const isFilled = data.some(bus => bus.id === busId && bus.filled);

                            if (!isFilled) {
                                busSelect.appendChild(option);
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching bus data:', error));
            }
            // dateInput.addEventListener('change', fetchAndFilterBuses);
            brigadeSelect.addEventListener('change', fetchAndFilterBuses);
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[name="date"]');
            const brigadeSelect = document.getElementById('brigade');
            const busSelect = document.getElementById('bus');

            function clearAndFetchBuses() {
                const selectedDate = dateInput.value;
                const selectedBrigade = brigadeSelect.value;

                busSelect.innerHTML = '<option value="" disabled selected>Selectionner un bus</option>';

                if (!selectedDate || !selectedBrigade) return;

                fetch(`/app/maintenance/check-buses?date=${selectedDate}&brigade=${selectedBrigade}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // console.log(data); 

                        data.forEach(bus => {
                            if (!bus.filled) {
                                const option = document.createElement('option');
                                option.value = bus.id;
                                option.textContent = bus.name;
                                busSelect.appendChild(option);
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching bus data:', error));
            }
            brigadeSelect.addEventListener('change', clearAndFetchBuses);
            dateInput.addEventListener('change', function() {
                busSelect.innerHTML = '<option value="" disabled selected>Selectionner un bus</option>';
                brigadeSelect.innerHTML = `
                                    <option value="" disabled selected>selectionner brigade</option>
                                    <option value="matin">Matin</option>
                                    <option value="soir">Soir</option>
                `;
            })
        });
        document.addEventListener('DOMContentLoaded', function() {
            const partitSelect = document.getElementById('partit');
            const inputsToControl = Array.from(document.querySelectorAll(
                ' #ligne, #hdepart, #harrive, #gasoile, #kmhlp, #kmdepart, #kmarive'
            ));

            const defaultValues = {
                ligne: '/',
                hdepart: '00:00',
                harrive: '00:00',
                gasoile: '0',
                kmhlp: '0',
                kmdepart: '0',
                kmarive: '0',
                kmglobale: '0',
                kmcommerciale: '0'
            };

            function updateInputsBasedOnPartit() {
                const isNonSelected = partitSelect.value === 'non';
                inputsToControl.forEach(input => {
                    if (isNonSelected) {
                        input.readOnly = true;
                        input.disabled = defaultValues[input.name] || '';
                    } else {
                        input.readOnly = false;
                        input.disabled = '';
                    }
                });
            }
            partitSelect.addEventListener('change', updateInputsBasedOnPartit);
            updateInputsBasedOnPartit();
        });
    </script>
@endsection
