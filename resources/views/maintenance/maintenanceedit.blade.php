@extends('base')

@section('title', 'Saisie')

@section('content')
    <div class="pagetitle">
        <h1>Modification du Bus {{ $record->bus->name }} du {{ $record->brigade }} du
            {{ \Carbon\Carbon::parse($record->date_fiche)->format('d/m/Y') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">Direction</a></li>
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
                    {{-- value="@if (!$record->ligne)non @endif"> --}}
                    @if ($record->ligne)
                        <option value="oui"  selected>Oui</option>
                        <option value="non">Non</option>
                    @else
                    <option value="oui">Oui</option>
                        <option value="non"  selected>Non</option>
                    @endif
                </select>
                <label for="partit">Partit?</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="ligne" id="ligne"
                    aria-label="Floating label select example">
                    <option id="/" name="/" disabled selected> </option>
                    @foreach ($lines as $line)
                        <option value="{{ $line->id }}" data-station="{{ $line->station_id ?? '' }}"
                            data-terminus="{{ $line->terminus }}">{{ $line->name }}
                        </option>
                    @endforeach
                </select>
                <label for="ligne">LIGNE</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="destination" id="destination"
                    aria-label="Floating label select example">
                    <option value="/" disabled selected>Séléctionner</option>
                </select>
                <label for="destination">Destination</label>
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
        <div class="col-md-4">
            <div class="form-floating">
                <input display="none" class="d-none" name="kmhlp" id="kmhlp" value ="{{ $record->kmhlp }}">
                <input type="number" disabled class="form-control" name="kmhlpdisp" id="kmhlpdisp"
                    value ="{{ $record->kmhlp }}">
                <label for="kmhlpdisp">Kilométrage (HLP)</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" disabled class="form-control" name="kmglobale" id="kmglobale"
                    value ="{{ $record->kmgobale }}">
                <label for="kmglobale">Kilométrage (Globale)</label>
            </div>
        </div>
        <div class="col-md-4">
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
                ' #ligne,#destination , #hdepart, #harrive, #gasoile, #kmhlp, #kmdepart, #kmarive'
            ));

            const defaultValues = {
                ligne: '/',
                destination: '/',
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
        const stations = @json($stations);

        document.getElementById('ligne').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selected_terminus = selectedOption.getAttribute('data-terminus');
            const selected_station = selectedOption.getAttribute('data-station');
            const destinationDropdown = document.getElementById('destination');
            destinationDropdown.innerHTML = '';

            const defaultOption = new Option('Séléctionner', '', true, true);
            defaultOption.disabled = true;
            destinationDropdown.add(defaultOption);

            stations.forEach(station => {
                if (station.id == selected_station) {
                    const option = new Option(station.name, station.distance);
                    destinationDropdown.add(option);
                }
            });
            const option = new Option("Terminus", selected_terminus);
            destinationDropdown.add(option);
        });

        document.getElementById('brigade').addEventListener('change', function() {
            const brigade_val = this.value;
            const dist_label = document.getElementById('distlabel');
            if (brigade_val === "soir") {
                dist_label.innerHTML = ''
                dist_label.innerHTML = 'Arrivée'
            } else {
                dist_label.innerHTML = ''
                dist_label.innerHTML = "Destination"
            }
        });
        document.getElementById('destination').addEventListener('change', function() {
            const hlpfield = document.getElementById('kmhlp');
            const hlpfielddisp = document.getElementById('kmhlpdisp');
            hlpfielddisp.value = this.value;
            hlpfield.value = this.value;

        });
        document.addEventListener('DOMContentLoaded', function() {
            const kmdepart = document.getElementById('kmdepart');
            const kmarive = document.getElementById('kmarive');
            const kmhlp = document.getElementById('kmhlp');
            const destination = document.getElementById('destination');
            const kmglobale = document.getElementById('kmglobale');
            const kmcommerciale = document.getElementById('kmcommerciale');

            function fetchData() {
                if (!kmdepart.value || !kmarive.value || !kmhlp.value) return;
                kmglobale.value = kmarive.value - kmdepart.value;
                kmcommerciale.value = kmglobale.value - kmhlp.value;
            }
            kmdepart.addEventListener('change', fetchData);
            kmarive.addEventListener('change', fetchData);
            destination.addEventListener('change', fetchData);
        });
    </script>
@endsection
