@extends('base')

@section('title', 'Saisie')

@section('content')
    <div class="pagetitle">
        <h1>Remplissage des données de maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">Direction</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Remplire</li>
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
        <h4>Kilométrage:</h4>
        <div class="col-md-7">
            <div class="form-floating">
                <input name="date" type="date" required class="form-control">
                <label for="date">Date</label>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-floating">
                <select class="form-select" required name="brigade" id="brigade"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>selectionner brigade</option>
                    <option value="matin">Matin</option>
                    <option value="soir">Soir</option>
                </select>
                <label for="brigade">Brigade</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select" name="bus" id="bus" required
                    aria-label="Floating label select example">
                    {{-- @foreach ($buses as $bus)
                        <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                    @endforeach --}}
                </select>
                <label for="bus">BUS</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select" name="partit" id="partit" required
                    aria-label="Floating label select example">
                    <option value="oui">Oui</option>
                    <option value="non">Non</option>
                </select>
                <label for="partit">Partit?</label>
            </div>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="form-floating">
                <select class="form-select" required name="destination" id="destination"
                    aria-label="Floating label select example">
                    <option value="/" disabled selected>Séléctionner</option>
                </select>
                <label for="destination" id="distlabel">Destination</label>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-floating">
                <input type="time" required class="form-control" name="hdepart" id="hdepart">
                <label for="hdepart">Heure de départ</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="time" required class="form-control" name="harrive" id="harrive">
                <label for="harrive">Heure d'arrivée</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="number" required class="form-control" name="gasoile" id="gasoile">
                <label for="gasoile">Consomation Gasoile(L)</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="number" required class="form-control" name="kmdepart" id="kmdepart">
                <label for="kmdepart">Kilométrage (Départ)</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="number" required class="form-control" name="kmarive" id="kmarive">
                <label for="kmarive">Kilométrage (Arrivée)</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" disabled class="form-control" name="kmglobale" id="kmglobale">
                <label for="kmglobale">Kilométrage (Globale)</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input display="none" class="d-none" name="kmhlp" id="kmhlp">
                <input type="number" disabled class="form-control" name="kmhlpdisp" id="kmhlpdisp">
                <label for="kmhlpdisp">Kilométrage (HLP)</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" disabled class="form-control" name="kmcommerciale" id="kmcommerciale">
                <label for="kmcommerciale">Kilométrage (Commerciale)</label>
            </div>
        </div>
        <h4>Pannes:</h4>
        <div class="col-md-4">
            <div class="form-check form-switch" style="padding-left: 0em;">
                <label class="form-check-label" for="togglePanneMecanique">Pannes mécanique:</label>
                <input class="form-check-input" type="checkbox" name="pannemecaniquecheck" id="togglePanneMecanique"
                    style="float: none; margin-left: 0.5em" onchange="toggleSelect('panneMecanique')">
                <select class="form-select" disabled name="pannemecanique[]" id="panneMecanique" multiple
                    aria-label="autorisations" style="height: 100px;">
                    @foreach ($pannes->where('type', 'mecanique') as $panne)
                        <option value="{{ $panne->id }}">{{ $panne->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch" style="padding-left: 0em;">
                <label class="form-check-label" for="togglePanneElectrique">Pannes électrique:</label>
                <input class="form-check-input" type="checkbox" name="panneelectriquecheck" id="togglePanneElectrique"
                    style="float: none; margin-left: 0.5em" onchange="toggleSelect('panneElectrique')">
                <select class="form-select" disabled name="panneelectrique[]" id="panneElectrique" multiple
                    aria-label="autorisations" style="height: 100px;">
                    @foreach ($pannes->where('type', 'electrique') as $panne)
                        <option value="{{ $panne->id }}">{{ $panne->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch" style="padding-left: 0em;">
                <label class="form-check-label" for="togglePanneTolle">Pannes de Tolles:</label>
                <input class="form-check-input" type="checkbox" name="pannetollecheck" id="togglePanneTolle"
                    style="float: none; margin-left: 0.5em" onchange="toggleSelect('panneTolle')">
                <select class="form-select" disabled name="pannetolle[]" id="panneTolle" multiple
                    aria-label="autorisations" style="height: 100px;">
                    @foreach ($pannes->where('type', 'tolle') as $panne)
                        <option value="{{ $panne->id }}">{{ $panne->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <h4>Vidanges:</h4>

        <div class="col-md-3 me-5 ms-5 progress" style="padding-left: 0em;padding-right: 0em;height: 2em;">
            <div class="progress-bar" role="progressbar" id="vidangemoteurbar" style="width: 0%;overflow: visible;" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100">Vidange Moteur
            </div>
        </div>
        <div class="col-md-3 me-5 ms-5 progress" style="padding-left: 0em;padding-right: 0em;height: 2em;">
            <div class="progress-bar" role="progressbar" id="vidangeboitebar" style="width: 0%;overflow: visible;" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100">Vidange Boite</div>
        </div>
        <div class="col-md-3 me-5 ms-5 progress" style="padding-left: 0em;padding-right: 0em;height: 2em;">
            <div class="progress-bar" role="progressbar" id="vidangepondbar" style="width: 0%;overflow: visible;" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100">Vidange Pond</div>
        </div>



        <div class="text-end">
            <button type="submit" class="btn btn-primary">Valider</button>
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
                kmdepart.value = '';
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
                                option.dataset.kmactuelle = bus.kmactuelle;
                                option.dataset.kmderniervidange = bus.kmderniervidange;
                                option.dataset.kmderniervidangeboite = bus.kmderniervidangeboite;
                                option.dataset.kmderniervidangepond = bus.kmderniervidangepond;
                                option.textContent = bus.name;
                                busSelect.appendChild(option);
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching bus data:', error));
            }
            brigadeSelect.addEventListener('change', clearAndFetchBuses);
            dateInput.addEventListener('change', function() {
                kmdepart.value = '';
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
        const busSelect = document.getElementById('bus');


        busSelect.addEventListener('change', function() {

            const selectedOption = this.options[this.selectedIndex];
            const selectedBusKmactuelle = selectedOption.dataset.kmactuelle;
            const selectedBuskmderniervidange = selectedOption.dataset.kmderniervidange;
            const selectedBuskmderniervidangeboite = selectedOption.dataset.kmderniervidangeboite;
            const selectedBuskmderniervidangepond = selectedOption.dataset.kmderniervidangepond;
            const kmdepart = document.getElementById('kmdepart');
            const vidangemoteurbar = document.getElementById('vidangemoteurbar');
            const vidangeboitebar = document.getElementById('vidangeboitebar');
            const vidangepondbar = document.getElementById('vidangepondbar');
            if (!selectedBusKmactuelle) return;
            kmdepart.value = selectedBusKmactuelle;
            // console.log([Math.min((selectedBusKmactuelle - selectedBuskmderniervidange/ 10000) * 100, 100)+'%',Math.min((selectedBusKmactuelle - selectedBuskmderniervidangeboite/ 10000) * 100, 100)+'%',Math.min((selectedBusKmactuelle - selectedBuskmderniervidangepond/ 10000) * 100, 100)+'%'])
            console.log([Math.min(((selectedBusKmactuelle - selectedBuskmderniervidange)/10000)*100, 100),Math.min(((selectedBusKmactuelle - selectedBuskmderniervidangeboite)/10000)*100, 100),Math.min(((selectedBusKmactuelle - selectedBuskmderniervidangepond)/10000)*100, 100)])
            vidangemoteurbar.style.width= Math.min(((parseInt(selectedBusKmactuelle) - parseInt(selectedBuskmderniervidange))/10000)*100, 100)+'%';
            vidangeboitebar.style.width= Math.min(((parseInt(selectedBusKmactuelle) - parseInt(selectedBuskmderniervidangeboite))/10000)*100, 100)+'%';
            vidangepondbar.style.width= Math.min(((parseInt(selectedBusKmactuelle) - parseInt(selectedBuskmderniervidangepond))/10000)*100, 100)+'%';
            vidangemoteurbar.innerHTML= '';
            vidangeboitebar.innerHTML= '';
            vidangepondbar.innerHTML= '';
            vidangemoteurbar.innerHTML = 'Moteur: ' + (10000-(parseInt(selectedBusKmactuelle) - parseInt(selectedBuskmderniervidange)));
            vidangeboitebar.innerHTML= 'Boite:'+ (10000-(parseInt(selectedBusKmactuelle) - parseInt(selectedBuskmderniervidangeboite)));
            vidangepondbar.innerHTML= 'Pond:'+ (10000-(parseInt(selectedBusKmactuelle) - parseInt(selectedBuskmderniervidangepond)));
        });

        function toggleSelect(selectId) {
            const selectElement = document.getElementById(selectId);
            selectElement.disabled = !selectElement.disabled;
            selectElement.required = !selectElement.required;
        }
    </script>
@endsection
