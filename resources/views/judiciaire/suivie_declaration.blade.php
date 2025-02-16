@extends('base')

@section('title', 'Pannes')

@section('content')
    <style>
        @font-face {
            font-family: 'lateef';
            src: url('{{ asset('theme/fonts/lateef/Lateef-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
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
    <div class="tab-content pt-2" id="borderedTabContent" style = "font-family: 'Tajwal';">
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
                        <th style="text-align: right;">التاريخ</th>
                        <th style="text-align: right;">السائق</th>
                        <th style="text-align: right;">الحافلة</th>
                        <th style="text-align: right;">CAAT</th>
                        <th style="text-align: right;">مصاريف</th>
                        {{-- <th>اللجنة</th> --}}
                        <th style="text-align: left;">عمليات</th>
                    </tr>
                </thead>
                <tbody dir="rtl">
                    @foreach ($declarations as $declaration)
                        <tr
                            @if ($declaration->caat) style="border-color: green;" @else style="border-color: red;" @endif>
                            <td>{{ date('Y', strtotime($declaration->date_fiche)) }}/{{ $declaration->number }}</td>
                            <td>{{ $declaration->time_day }}</td>
                            <td>{{ $declaration->chauffeur->name }}</td>
                            <td>{{ $declaration->bus->name }}</td>
                            <td>
                                @if ($declaration->caat == true)
                                    مصرح
                                @else
                                    غير مصرح
                                @endif
                            </td>
                            <td>
                                @if ($declaration->paye == true)
                                    مدفوع
                                @else
                                    غير مدفوع
                                @endif
                            </td>
                            <td style="text-align:left ;">
                                <button type="button"
                                    @if ($declaration->caat == true) class="btn btn-success" disabled @else class="btn btn-danger" @endif
                                    data-bs-toggle="modal" onclick="handlecaatclick({{ $declaration->id }})">CAAT</button>
                                <button type="button"
                                    @if ($declaration->paye == true) class="btn btn-success" disabled @else class="btn btn-danger" @endif
                                    data-bs-toggle="modal" onclick="handlepayeclick({{ $declaration->id }})">أموال</button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal1"
                                    onclick="handleresoudreclick({{ $declaration }},{{ $declaration->ligne }})">تقرير</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="modal fade" id="ExtralargeModal1" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header" dir="ltr">
                            <h5 class="modal-title" id="modal_title"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" >
                            <h5 style="font-family: 'Tajwal'">سيدي بلعباس يوم: <span style="font-weight: bold;" id="declaration_date"></span></h5>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">الحافلة: <span style="font-weight: bold;" id="bus"></span></h5>
                                <h5 style="font-family: 'Tajwal'">السائق: <span style="font-weight: bold;" id="chauffeur"></span></h5>
                                <h5 style="font-family: 'Tajwal'">الخط: <span style="font-weight: bold;" id="ligne"></span></h5>
                            </div>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">الوقت: <span style="font-weight: bold;" id="time"></span></h5>
                                <h5 style="font-family: 'Tajwal'">اليوم: <span style="font-weight: bold;" id="day"></span></h5>
                                <h5 style="font-family: 'Tajwal'">المكان: <span style="font-weight: bold;" id="place"></span></h5>
                            </div>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">تصريح لدى CAAT: <span style="font-weight: bold;" id="caat"></span></h5>
                                <h5 style="font-family: 'Tajwal'">مصاريف: <span style="font-weight: bold;" id="paye"></span></h5>
                            </div>

                            <h5 style="font-family: 'Tajwal'">الوصف: <br><span style="font-weight: bold;" id="description"></span></h5>
                            <h5 style="font-family: 'Tajwal'">الخسائر: <br><span style="font-weight: bold;" id="pertes"></span></h5>
                            <div id="photos" style="font-family: 'Tajwal';font-weight: bold;">

                            </div>


                            <form class="row g-3" action="" method="post">
                                @csrf
                                <input type="hidden" name="fichedeclaration_id" id="fichedeclaration_id">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Imprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">


        </div>
        <div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
            <h5 class="mt-2">Selectionner la date:</h5>


        </div>

    </div>


    <script>
        function handlecaatclick(id) {
            if (confirm('هل ثم تصريح بالحادث عند CAAT؟')) {
                let date = prompt("تاريخ التصريح (YYYY-MM-DD) :");

                if (date) {
                    fetch(`judiciaire/handle_caat:${id},${date}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                date: date
                            }) // Envoi de la date au contrôleur
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
                } else {
                    alert("Date invalide ou annulée !");
                }
            }
        }

        function handlepayeclick(id) {
            if (confirm('هل ثم تلقي الأموال؟')) {
                let date = prompt("تاريخ التصريح (YYYY-MM-DD) :");

                if (date) {
                    fetch(`judiciaire/handle_paye:${id},${date}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                date: date
                            }) 
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
                } else {
                    alert("Date invalide ou annulée !");
                }
            }
        }

        function handleresoudreclick(declaration, ligneval) {
            const modal_title = document.getElementById('modal_title');
            const declarationIdInput = document.getElementById('fichedeclaration_id');
            const declarationdate = document.getElementById('declaration_date');
            const chauffeur = document.getElementById('chauffeur');
            const ligne = document.getElementById('ligne');
            const time = document.getElementById('time');
            const day = document.getElementById('day');
            const place = document.getElementById('place');
            const description = document.getElementById('description');
            const pertes = document.getElementById('pertes');
            const photos = document.getElementById('photos');
            const caat = document.getElementById('caat');
            const paye = document.getElementById('paye');
            const dateObj = new Date(declaration.date_fiche);
            const formattedDate =
                `${String(dateObj.getDate()).padStart(2, '0')}-${String(dateObj.getMonth() + 1).padStart(2, '0')}-${dateObj.getFullYear()}`;
            const bus = document.getElementById('bus');
            modal_title.innerHTML = '';
            declarationdate.innerHTML ='';
            bus.innerHTML ='';
            chauffeur.innerHTML ='';
            ligne.innerHTML ='';
            time.innerHTML ='';
            day.innerHTML ='';
            place.innerHTML ='';
            caat.innerHTML ='';
            paye.innerHTML ='';
            description.innerHTML ='';
            pertes.innerHTML ='';
            photos.innerHTML ='';
            declarationphotos = ''; 
            modal_title.innerHTML = declaration.bus.name + ' - ' + declaration.chauffeur.fr_name + ' le ' + declaration
                .time_day + ' signaler le ' +
                declaration.date_fiche;
            declarationIdInput.value = declaration.id;
            declarationdate.innerHTML = formattedDate;
            bus.innerHTML = declaration.bus.name;
            chauffeur.innerHTML = declaration.chauffeur.name;
            ligne.innerHTML = ligneval.name;
            time.innerHTML = declaration.time_day.split(" ")[1];
            day.innerHTML = declaration.time_day;
            place.innerHTML = declaration.place;
            description.innerHTML = declaration.description;
            pertes.innerHTML = declaration.pertes;
            photos.innerHTML = ``;
            declarationphotos = JSON.parse(declaration.photos);
            if(declaration.caat){
                caat.innerHTML = `ثم التصريح في ${declaration.date_caat}`
            }else{
                caat.innerHTML = `لم يتم التصريح بعد`
            }
            if(declaration.paye){
                paye.innerHTML = `ثم تلقي المصاريف في ${declaration.date_paye}`
            }else{
                paye.innerHTML = `لم يتم تلقي المصاريف بعد`
            }
            
            if (declarationphotos && Array.isArray(declarationphotos) && declarationphotos.length > 0) {
                let indicators = '';
                let slides = '';

                declarationphotos.forEach((photo, index) => {
                    indicators += `
            <button type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}"
            aria-label="Slide ${index + 1}"></button>
        `;

                    slides += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="https://direction.etus22.dz/${photo}" class="d-block w-100" alt="Image ${index + 1}">
            </div>
        `;
                });

                photos.innerHTML = `
        <h5 style="font-family: 'Tajwal';font-weight: bold;">الصور: <br><span id="pertes"></span></h5>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="width: 65%; justify-self: center;">
            <div class="carousel-indicators">
                ${indicators}
            </div>
            <div class="carousel-inner">
                ${slides}
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    `;
            }

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
    </script>
@endsection
