@extends('base')

@section('title', 'Evaluations')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <div class="pagetitle">
        <h1>Evaluations</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Controle technique</li>
                <li class="breadcrumb-item active">Evaluations</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">Nouvelle</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Lue</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="stat-tab" data-bs-toggle="tab" data-bs-target="#bordered-stat" type="button"
                role="tab" aria-controls="stat" aria-selected="false" tabindex="-1">Statistiques</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="extraire-tab" data-bs-toggle="tab" data-bs-target="#bordered-extraire"
                type="button" role="tab" aria-controls="extraire" aria-selected="false"
                tabindex="-1">Extraire</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <h5 class="mt-2">Nouvelles Evaluations</h5>

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

            <table class="table datatable mt-1">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>الخدمة</th>
                        <th>المراقب</th>
                        <th>النظافة</th>
                        <th>التسيير</th>
                        <th>رسالة</th>
                        <th>رقم الهاتف</th>
                        <th>التاريخ</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $rating)
                        @if (!$rating->read)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td
                                    @if ($rating->service === 'mauvais') style="border-color: red;" @elseif ($rating->service === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->service === 'mauvais')
                                        سيئ
                                    @elseif ($rating->service === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td
                                    @if ($rating->controler === 'mauvais') style="border-color: red;" @elseif ($rating->controler === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->controler === 'mauvais')
                                        سيئ
                                    @elseif ($rating->controler === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td
                                    @if ($rating->clean === 'mauvais') style="border-color: red;" @elseif ($rating->clean === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->clean === 'mauvais')
                                        سيئ
                                    @elseif ($rating->clean === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td
                                    @if ($rating->order === 'mauvais') style="border-color: red;" @elseif ($rating->order === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->order === 'mauvais')
                                        سيئ
                                    @elseif ($rating->order === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td>{{ $rating->message }}</td>
                                <td>{{ $rating->phone }}</td>
                                <td>{{ $rating->created_at }}</td>
                                <td style="display: flex">
                                    <form action="{{ route('app.ctechnique.marquercommelue') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rating_id" value="{{ $rating->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-eye-fill" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('app.ctechnique.print_evaluation') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rating_id" value="{{ $rating->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-printer" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            <h5 class="mt-2">Selectionner la date :</h5>


            <table class="table datatable mt-1">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>الخدمة</th>
                        <th>المراقب</th>
                        <th>النظافة</th>
                        <th>التسيير</th>
                        <th>رسالة</th>
                        <th>رقم الهاتف</th>
                        <th data-type="date" data-format="YYYY/DD/MM">التاريخ</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $rating)
                        @if ($rating->read)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td
                                    @if ($rating->service === 'mauvais') style="border-color: red;" @elseif ($rating->service === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->service === 'mauvais')
                                        سيئ
                                    @elseif ($rating->service === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td
                                    @if ($rating->controler === 'mauvais') style="border-color: red;" @elseif ($rating->controler === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->controler === 'mauvais')
                                        سيئ
                                    @elseif ($rating->controler === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td
                                    @if ($rating->clean === 'mauvais') style="border-color: red;" @elseif ($rating->clean === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->clean === 'mauvais')
                                        سيئ
                                    @elseif ($rating->clean === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td
                                    @if ($rating->order === 'mauvais') style="border-color: red;" @elseif ($rating->order === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    @if ($rating->order === 'mauvais')
                                        سيئ
                                    @elseif ($rating->order === 'moyen')
                                        متوسط
                                    @else
                                        جبد
                                    @endif
                                </td>
                                <td>{{ $rating->message }}</td>
                                <td>{{ $rating->phone }}</td>
                                <td>{{ $rating->created_at }}</td>
                                <td>
                                    <form action="{{ route('app.ctechnique.print_evaluation') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rating_id" value="{{ $rating->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-printer" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="tab-pane fade" id="bordered-stat" role="tabpanel" aria-labelledby="stat-tab">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="form-floating">
                        <input id="dateduInput" name="datedu" type="date" required class="form-control">
                        <label for="datedu">Du</label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-floating">
                        <input id="dateauInput" name="dateau" type="date" required class="form-control">
                        <label for="dateau">Au</label>
                    </div>
                </div>
                <button id="downloadPDF" class="btn btn-outline-primary col-md-2">Télécharger le PDF</button>
            </div>
            <div class="container">
                <div class="row">
                    <!-- Carte 1 -->
                    <div class="col-md-6">
                        <div class="card-body pb-0">
                            <h5 class="card-title" style="text-align: center;">الخدمة</h5>
                            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                            <script></script>
                        </div>
                    </div>
                    <!-- Carte 2 -->
                    <div class="col-md-6">
                        <div class="card-body pb-0">
                            <h5 class="card-title" style="text-align: center;">المراقب</h5>
                            <div id="trafficChart2" style="min-height: 400px;" class="echart"></div>
                            <script></script>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body pb-0">
                            <h5 class="card-title" style="text-align: center;">النظافة</h5>
                            <div id="trafficChart3" style="min-height: 400px;" class="echart"></div>
                            <script></script>
                        </div>
                    </div>
                    <!-- Carte 4 -->
                    <div class="col-md-6">
                        <div class="card-body pb-0">
                            <h5 class="card-title" style="text-align: center;">التسيير</h5>
                            <div id="trafficChart4" style="min-height: 400px;" class="echart"></div>
                            <script></script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="bordered-extraire" role="tabpanel" aria-labelledby="extraire-tab">
            <div style="border-bottom: solid;border-block-width: 2px;padding-bottom: 10px;">
                <h5 class="mt-5">Sélectionner la date pour l'extraction des Evaluations:</h5>
                <form class="row g-3" action="{{ route('app.ctechnique.evaluations_pdf') }}" method="post">
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
                <h5 class="mt-5">Sélectionner la date pour l'extraction de l'état des Evaluations:</h5>
                <form class="row g-3" action="{{ route('app.ctechnique.etatevaluations_pdf') }}" method="post">
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
        document.addEventListener("DOMContentLoaded", () => {
            const dateduInput = document.getElementById("dateduInput");
            const dateauInput = document.getElementById("dateauInput");
            const refreshButton = document.getElementById("refreshButton");

            dateduInput.addEventListener('change', fetchData);
            dateauInput.addEventListener('change', fetchData);

            function fetchData() {
                const datedu = dateduInput.value;
                const dateau = dateauInput.value;
                if (!datedu || !dateau) return;

                fetch(`/app/evaluations/refreshcharts?datedu=${datedu}&dateau=${dateau}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateChart("trafficChart", data.serviceData);
                            updateChart("trafficChart2", data.controleurData);
                            updateChart("trafficChart3", data.propreteData);
                            updateChart("trafficChart4", data.geranceData);
                        } else {
                            alert("Erreur lors de la récupération des données.");
                        }
                    })
                    .catch(error => {
                        console.error("Erreur:", error);
                        alert("Une erreur est survenue lors de la mise à jour des graphiques.");
                    });
            }

            function updateChart(chartId, chartData) {
                const chart = echarts.getInstanceByDom(document.getElementById(chartId));
                if (chart) {
                    chart.setOption({
                        series: [{
                            data: chartData
                        }]
                    });
                }
            }
        });
        document.addEventListener("DOMContentLoaded", () => {
            echarts.init(document.querySelector("#trafficChart")).setOption({
                color: ['#6FCF97', '#EB5757', '#F2C94C'],
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [{
                    name: 'الخدمة',
                    type: 'pie',
                    radius: ['20%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: true,
                        position: 'inside',
                        formatter: '{b}: %{d}'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [{
                            value: <?php echo $sbien; ?>,
                            name: 'جيدة'
                        },
                        {
                            value: <?php echo $smauvais; ?>,
                            name: 'سيئة'
                        },
                        {
                            value: <?php echo $smoyen; ?>,
                            name: 'متوسطة'
                        }
                    ]
                }]
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            echarts.init(document.querySelector("#trafficChart2")).setOption({
                color: ['#6FCF97', '#EB5757', '#F2C94C'],
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [{
                    name: 'المراقب',
                    type: 'pie',
                    radius: ['20%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: true,
                        position: 'inside',
                        formatter: '{b}: %{d}'

                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [{
                            value: <?php echo $cbien; ?>,
                            name: 'جيدة'
                        },
                        {
                            value: <?php echo $cmauvais; ?>,
                            name: 'سيئة'
                        },
                        {
                            value: <?php echo $cmoyen; ?>,
                            name: 'متوسطة'
                        }
                    ]
                }]
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            echarts.init(document.querySelector("#trafficChart3")).setOption({
                color: ['#6FCF97', '#EB5757', '#F2C94C'],
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [{
                    name: 'النظافة',
                    type: 'pie',
                    radius: ['20%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: true,
                        position: 'inside',
                        formatter: '{b}: %{d}'

                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [{
                            value: <?php echo $clbien; ?>,
                            name: 'جيدة'
                        },
                        {
                            value: <?php echo $clmauvais; ?>,
                            name: 'سيئة'
                        },
                        {
                            value: <?php echo $clmoyen; ?>,
                            name: 'متوسطة'
                        }
                    ]
                }]
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            echarts.init(document.querySelector("#trafficChart4")).setOption({
                color: ['#6FCF97', '#EB5757', '#F2C94C'],
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [{
                    name: 'التسيير',
                    type: 'pie',
                    radius: ['20%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: true,
                        position: 'inside',
                        formatter: '{b}: %{d}'

                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [{
                            value: <?php echo $obien; ?>,
                            name: 'جيدة'
                        },
                        {
                            value: <?php echo $omauvais; ?>,
                            name: 'سيئة'
                        },
                        {
                            value: <?php echo $omoyen; ?>,
                            name: 'متوسطة'
                        }
                    ]
                }]
            });
        });
        document.getElementById("downloadPDF").addEventListener("click", generatePDF);


        // async function generatePDF() {
        //     const {
        //         jsPDF
        //     } = window.jspdf;
        //     const pdf = new jsPDF({
        //         orientation: "landscape",
        //         unit: "px",
        //         format: "a4",
        //     });
        //     pdf.setFont("arial", "normal");
        //     const pageWidth = pdf.internal.pageSize.getWidth();
        //     const pageHeight = pdf.internal.pageSize.getHeight();
        //     const margin = 10;
        //     const spacing = 20;
        //     const columnWidth = (pageWidth - margin * 2 - spacing) / 2;
        //     let yOffset = margin;
        //     let xOffset = margin;
        //     const chartIds = ["trafficChart", "trafficChart2", "trafficChart3", "trafficChart4"];
        //     const chartTitles = ["الخدمة", "المراقب", "النظافة", "التسيير"];
        //     for (let i = 0; i < chartIds.length; i++) {
        //         const chartId = chartIds[i];
        //         const chartTitle = chartTitles[i];
        //         const chartElement = document.getElementById(chartId);
        //         if (chartElement) {
        //             // pdf.setFont("helvetica", "bold");
        //             pdf.setFontSize(12);
        //             pdf.text(chartTitle, xOffset + 122, yOffset + 8);
        //             const canvas = await html2canvas(chartElement);
        //             const imgData = canvas.toDataURL("image/png");
        //             const chartHeight = (chartElement.offsetHeight / chartElement.offsetWidth) * columnWidth;
        //             if (yOffset + chartHeight + spacing > pageHeight - margin) {
        //                 pdf.addPage();
        //                 yOffset = margin;
        //             }
        //             pdf.addImage(imgData, "PNG", xOffset, yOffset + 16, columnWidth, chartHeight);
        //             if (xOffset + columnWidth + spacing > pageWidth - margin) {
        //                 xOffset = margin;
        //                 yOffset += chartHeight + spacing;
        //             } else {

        //                 xOffset += columnWidth + spacing;
        //             }
        //         }
        //     }

        //     // Télécharger le PDF
        //     pdf.save("charts_landscape.pdf");
        // }
        async function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF({
                orientation: "landscape",
                unit: "px",
                format: "a4",
            });

            // Load the custom font (Tajawal-Light.ttf) from your server
            try {
                const fontUrl = '{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}';
                const response = await fetch(fontUrl);
                const fontArrayBuffer = await response.arrayBuffer();
                const fontBase64 = arrayBufferToBase64(fontArrayBuffer);

                // Add the font to the PDF's virtual file system (VFS)
                pdf.addFileToVFS("Tajawal-Light.ttf", fontBase64);
                pdf.addFont("Tajawal-Light.ttf", "Tajawal", "normal");

                // Now set the custom font
                pdf.setFont("Tajawal");
            } catch (error) {
                console.error("Error loading font:", error);
            }

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const margin = 10;
            const spacing = 20;
            const columnWidth = (pageWidth - margin * 2 - spacing) / 2;
            let yOffset = margin;
            let xOffset = margin;
            const chartIds = ["trafficChart", "trafficChart2", "trafficChart3", "trafficChart4"];
            const chartTitles = ["خدمة", "مراقب", "نظافة", "تسيير"];

            for (let i = 0; i < chartIds.length; i++) {
                const chartId = chartIds[i];
                const chartTitle = chartTitles[i];
                const chartElement = document.getElementById(chartId);

                if (chartElement) {
                    // Using the custom font for the title
                    pdf.setFont("Tajawal", "normal");
                    pdf.setFontSize(12);
                    pdf.text(chartTitle, xOffset + 132, yOffset + 8);

                    const canvas = await html2canvas(chartElement);
                    const imgData = canvas.toDataURL("image/png");
                    const chartHeight = (chartElement.offsetHeight / chartElement.offsetWidth) * columnWidth;

                    if (yOffset + chartHeight + spacing > pageHeight - margin) {
                        pdf.addPage();
                        yOffset = margin;
                    }

                    pdf.addImage(imgData, "PNG", xOffset, yOffset + 16, columnWidth, chartHeight);

                    if (xOffset + columnWidth + spacing > pageWidth - margin) {
                        xOffset = margin;
                        yOffset += chartHeight + spacing;
                    } else {
                        xOffset += columnWidth + spacing;
                    }
                }
            }

            // Download the PDF
            pdf.save("charts_landscape.pdf");
        }

        // Helper function to convert ArrayBuffer to base64
        function arrayBufferToBase64(buffer) {
            const byteArray = new Uint8Array(buffer);
            let binary = '';
            for (let i = 0; i < byteArray.length; i++) {
                binary += String.fromCharCode(byteArray[i]);
            }
            return window.btoa(binary);
        }
    </script>
@endsection
