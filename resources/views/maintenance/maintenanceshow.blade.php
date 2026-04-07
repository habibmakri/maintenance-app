@extends('base')

@section('title', 'Extraction ')

@section('content')

    <div class="pagetitle">
        <h1>Extraction des données de maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">Direction</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Extraire</li>
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
                        <th>chauffeur</th>
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
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-2">Selectionner la date statistique maintenance:</h5>
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
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="form-floating">
                            <select class="form-select" required name="languepdf" id="brigade"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner la langue</option>
                                <option value="fr">Francais</option>
                                <option value="ar">Arabe</option>
                            </select>
                            <label for="launguepdf">Langue</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télecharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le mois pour l'etat du gasoile mensuelle:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.gasoilepdf') }}" method="post">
                    @csrf
                    <div class="col-md-5">
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>

            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Selectionner la date gasoile au 100 KM :</h5>
                <form class="row g-3" action="{{ route('app.maintenance.km100pdf') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="datedupdf" type="date" required class="form-control">
                            <label for="datedupdf">Du</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="dateaupdf" type="date" required class="form-control">
                            <label for="dateaupdf">Au</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <select class="form-select" required name="languepdf" id="brigade"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner la langue</option>
                                <option value="fr">Francais</option>
                                <option value="ar">Arabe</option>
                            </select>
                            <label for="launguepdf">Langue</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télecharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Selectionner la date pour les grand traveaux :</h5>
                <form class="row g-3" action="{{ route('app.maintenance.grandtraveaux_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input name="datedu" type="date" required class="form-control">
                            <label for="datedu">Du</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input name="dateau" type="date" required class="form-control">
                            <label for="dateau">Au</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télecharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le mois et l'année pour l'extraction de l'état nombre de reparation résolue
                    mensuelle :</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etatnreparatiopdf') }}" method="post">
                    @csrf
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
                    <div class="col-md-2">
                        <div class="form-floating">
                            <select class="form-select" required name="languepdf" id="brigade"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner la langue</option>
                                <option value="fr">Francais</option>
                                <option value="ar">Arabe</option>
                            </select>
                            <label for="launguepdf">Langue</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le type pour l'extraction des travaux non résolue:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.panneencours_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-10">
                        <div class="form-floating">
                            <select class="form-select" required name="typepanne" id="typepanne"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner type</option>
                                <option value="tous">Tous types</option>
                                <option value="mecanique">Panne mecanique</option>
                                <option value="electrique">Panne éléctrique</option>
                                <option value="tolle">Panne tolle</option>
                            </select>
                            <label for="typepanne">Type</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le mois pour l'extraction de l'état Fiche de suivi Journaliere des travaux
                    reparés:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.suivijournaliere_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-5">
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner la date pour l'extraction de l'état Fiche de suivi Journaliere des travaux
                    reparés:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.suivijournaliere_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input name="datedu" type="date" required class="form-control">
                            <label for="datedu">Du</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input name="dateau" type="date" required class="form-control">
                            <label for="dateau">Au</label>
                        </div>
                    </div>
                  
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le bus et le mois pour l'extraction de l'état Fiche de suivi mensuelle des
                    travaux reparés:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.suivibus_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-2">
                        <div class="form-floating">
                            <select class="form-select" required name="buspdf" id="brigade"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner le Bus</option>
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                                    @endforeach
                                    <option value="0">Tous Les Bus</option>
                            </select>
                            <label for="launguepdf">Bus</label>
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le type pour l'extraction de l'état de vidange:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_vidange_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-10">
                        <div class="form-floating">
                            <select class="form-select" required name="type" id="type"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la type</option>
                                <option value="moteur" >Vidange Moteur</option>
                                <option value="boite" >Vidange Boite</option>
                                <option value="pont" >Vidange Pont</option>
                            </select>
                            <label for="type">Type  Vidange</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner la date pour l'extraction de l'état Fiche de suivi des Vidange:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.fiche_suivie_vidange_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input name="datedu" type="date" required class="form-control">
                            <label for="datedu">Du</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input name="dateau" type="date" required class="form-control">
                            <label for="dateau">Au</label>
                        </div>
                    </div>
                  
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le mois pour l'extraction de l'état des date des panne déclarer:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.suividatepanne_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-5">
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner le mois et la piece pour l'extraction de l'état:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="piece" id="piece"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                @foreach ($pieces as $piece)
                                <option value="{{$piece->id}}" >{{$piece->name}}</option>
                                @endforeach
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner Huile ou Eau pour l'extraction de l'état consomation sans vidange Par jour:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_sansvidange_jour_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="piece" id="piece"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                <option value="2" >Huile 15w40</option>
                                <option value="9" >GLACIOLE</option>
                                <option value="8" >Huile G3</option>
                                <option value="7" >Huile W90</option>
                                <option value="6" >Huile W10</option>
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner Huile ou Eau pour l'extraction de l'état consomation sans vidange:</h5>
                <form class="row g-3" action="{{ route('app.maintenance.etat_piece_sansvidange_pdf') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="piece" id="piece"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>Sélectionner la piece</option>
                                <option value="2" >Huile 15w40</option>
                                <option value="9" >GLACIOLE</option>
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
                    <button type="submit" class="btn btn-outline-primary col-md-2">Télécharger</button>
                </form>
            </div>
            
        </div>
        <div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
            <h5 class="mt-2">Selectionner la date:</h5>
            <form class="row g-3" action="{{ route('app.maintenance.excel') }}" method="post">
                @csrf
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="dateduexcel" type="date" required class="form-control">
                        <label for="dateduexcel">Du</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input name="dateauexcel" type="date" required class="form-control">
                        <label for="dateauexcel">Au</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" required name="brigadeexcel" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled selected>selectionner brigade</option>
                            <option value="jour">Jour</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigadeexcel">Brigade</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success col-md-2">Télecharger</button>
            </form>
            <h5 class="mt-5">Sélectionner le mois et l'année pour l'extraction de l'état de Kilométrage :</h5>
            <form class="row g-3" action="{{ route('app.maintenance.etatkilometrage') }}" method="post">
                @csrf
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
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" required name="brigadeexceleta" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled selected>selectionner brigade</option>
                            <option value="jour">Jour</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigadeexcel">Brigade</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-success col-md-2">Télécharger</button>
            </form>
            <h5 class="mt-5">Sélectionner le mois pour l'etat du gasoile mensuelle:</h5>
            <form class="row g-3" action="{{ route('app.maintenance.gasoileexcel') }}" method="post">
                @csrf
                <div class="col-md-5">
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
                <button type="submit" class="btn btn-outline-success col-md-2">Télécharger</button>
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
                                    <td>${item.chauffeur}</td>
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
                                        <td>${item.chauffeur}</td>
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

            dateduInput.addEventListener('change', fetchData);
            dateauInput.addEventListener('change', fetchData);
            brigadeSelect.addEventListener('change', fetchData);
        });
    </script>



@endsection
