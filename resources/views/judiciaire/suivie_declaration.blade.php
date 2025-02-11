@extends('base')

@section('title', 'Pannes')

@section('content')
    <div class="pagetitle">
        <h1>Suivie des déclaration</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Judiciaire</li>
                <li class="breadcrumb-item active">Suivie</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist" dir="rtl">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">هذا الشهر</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">الكل</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab" dir="rtl">

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

            <table class="table datatable mt-1" dir="rtl" style="text-align: right;">
                <thead dir="rtl">
                    <tr>
                        <th style="text-align: right;">الرقم</th>
                        <th style="text-align: right;" >التاريخ</th>
                        <th style="text-align: right;">السائق</th>
                        <th style="text-align: right;">الحافلة</th>
                        <th style="text-align: right;">CAAT</th>
                        <th style="text-align: right;">مصاريف</th>
                        {{-- <th>اللجنة</th> --}}
                        <th style="text-align: right;">عمليات</th>
                    </tr>
                </thead>
                <tbody dir="rtl">
                    @foreach ($declarations as $daclaration)
                        <tr
                            @if ($daclaration->caat) style="border-color: green;" @else style="border-color: red;" @endif>
                            <td>{{date('Y', strtotime($daclaration->date_fiche))}}/{{$daclaration->number }}</td>
                            <td>{{$daclaration->time_day}}</td>
                            <td>{{$daclaration->chauffeur->name }}</td>
                            <td>{{$daclaration->bus->name }}</td>
                            <td>{{$daclaration->caat }}</td>
                            <td>{{$daclaration->paye }}</td>
                            <td style="text-align:left ;">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1"
                                onclick="handleresoudreclick({{ $daclaration }})">CAAT</button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1"
                                onclick="handleresoudreclick({{ $daclaration }})">أموال</button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal1"
                                    onclick="handleresoudreclick({{ $daclaration }})">تقرير</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="modal fade" id="ExtralargeModal1" tabindex="-1" style="display: none;" aria-hidden="true">
                
                
            </div>
        </div>

        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            
            
        </div>
        <div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
            <h5 class="mt-2">Selectionner la date:</h5>


        </div>

    </div>


    <script>
        function handleresoudreclick(panne) {
            const modal_title = document.getElementById('modal_title');
            modal_title.innerHTML = panne.pannename.name + ' du ' + panne.fichemaintenance.bus.name + ' signaler le ' +
                panne.fichemaintenance.date_fiche + ' - ' + panne.fichemaintenance.brigade;
            const panneIdInput = document.getElementById('fichepanne_id');
            panneIdInput.value = panne.id;
        }
        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`maintenance/deletefichepanne:${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Opération réussie!');
                            location.reload(); 
                        } else {
                            alert('Opération échouée!');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        }

        function handlerapportclick(panne, used_pieces, chauffeur) {
            const modal_title = document.getElementById('modal_title2');
            const raport_bus = document.getElementById('raport_bus');
            const raport_panne = document.getElementById('raport_panne');
            const raport_declarepar = document.getElementById('raport_declarepar');
            const raport_datedeclaration = document.getElementById('raport_datedeclaration');
            const raport_dateresolution = document.getElementById('raport_dateresolution');
            const raport_brigadedeclaration = document.getElementById('raport_brigadedeclaration');
            const raport_brigaderesolution = document.getElementById('raport_brigaderesolution');
            const raport_equipe = document.getElementById('raport_equipe');
            const raport_description = document.getElementById('raport_description');
            const raport_pieces = document.getElementById('raport_pieces');
            const id_raport_bus = document.getElementById('id_raport_bus');
            const raport_panne_type = document.getElementById('raport_panne_type');
            modal_title.innerHTML = '';
            raport_bus.innerHTML = '';
            raport_panne.innerHTML = '';
            raport_panne_type.innerHTML = '';
            raport_declarepar.innerHTML = '';
            raport_datedeclaration.innerHTML = '';
            raport_dateresolution.innerHTML = '';
            raport_equipe.innerHTML = '';
            raport_description.innerHTML = '';
            raport_pieces.innerHTML = '';
            id_raport_bus.value = '';
            modal_title.innerHTML = 'Rapport du Panne: ' + panne.pannename.name + ' du ' + panne.fichemaintenance.bus.name +
                ' signaler le ' +
                panne.fichemaintenance.date_fiche + ' - ' + panne.fichemaintenance.brigade;
            raport_bus.innerHTML = 'Bus: ' + panne.fichemaintenance.bus.name;
            raport_panne.innerHTML = 'Panne: ' + panne.pannename.name;
            raport_panne_type.innerHTML = 'Type: ' + panne.pannename.type;
            if (chauffeur && chauffeur.fr_name) {
                raport_declarepar.innerHTML = 'Déclaré par: ' + chauffeur.fr_name;
            } else {
                raport_declarepar.innerHTML = 'Déclaré par: Équipe Maintenance';
            }
            raport_datedeclaration.innerHTML = 'Déclaré le: ' + panne.fichemaintenance.date_fiche + '-' + panne
                .fichemaintenance.brigade;
            raport_dateresolution.innerHTML = 'Résolue le: ' + panne.date_resoudre + '-' + panne.brigade;
            raport_equipe.innerHTML = 'Équipe intervenue:<br> ' + JSON.parse(panne.equipe).join(', ');
            raport_description.innerHTML = 'Description:<br> ' + panne.description;
            const piecesDetails = used_pieces.map(piece => `${piece.name} => Quantié: ${piece.quantity}`).join('  ||  ');
            raport_pieces.innerHTML = 'Pièces utilisées:<br>' + piecesDetails;
            id_raport_bus.value = panne.id;
            console.log(panne.id);
        }
        
        
        

        document.addEventListener('DOMContentLoaded', function() {
            const selectIds = ['equipe'];
            selectIds.forEach((id) => {
                new TomSelect(`#${id}`, {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null,
                    placeholder: 'Equipe',
                    searchField: ['text'],
                });
            });
        });

        function toggleSelect(selectId) {
            const selectElement = document.getElementById(selectId); // Corrected to use `getElementById`
            const partitSelect = document.getElementById('partit');
            const id_chauffer = document.getElementById('id_chauffeur');
            if (selectElement.tomselect) {
                if (selectElement.disabled) {
                    selectElement.tomselect.enable(); // Enable Tom Select
                } else {
                    selectElement.tomselect.disable(); // Disable Tom Select
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tom Select for each <select> element
            const selectIds = ['panneMecanique', 'panneElectrique', 'panneTolle'];
            selectIds.forEach((id) => {
                new TomSelect(`#${id}`, {
                    plugins: ['remove_button'], // Enables the remove button for selected items
                    create: false, // Prevents users from adding custom options
                    maxItems: null, // Allows multiple selection
                    placeholder: 'Selectionner', // Placeholder text
                    searchField: ['text'], // Enables searching in the options
                });
            });
        });
    </script>
@endsection
