<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية - التسجيل لدفتر المقاعد</title>
    <link href="{{ asset('/theme/tomselect/tom-select.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/theme/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/simple-datatables/style.css') }}">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f9f9f9;
            text-align: center;
        }

        /* header {
            background-color: #afd4fc;
            color: white;
            padding: 30px 0;
        } */

        .welcome {
            margin-top: 60px;
            margin-bottom: 40px;
        }

        .btn-start {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px 30px;
            font-size: 20px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            background: linear-gradient(135deg, #3399ff, #0069d9);
            transform: translateY(-2px);
        }

        footer {
            margin-top: 60px;
            padding: 20px;
            background-color: #f1f1f1;
            font-size: 14px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            height: fit-content;
            padding: 10px 20px;
        }

        .header-title {
            font-family: 'Tajwal', sans-serif;
            font-size: 30px;
            font-weight: 700;
            color: #012970;
            margin: 0;
            text-align: center;
        }

        .header-logo {
            max-height: 70px;
            height: auto;
        }



        @media (max-width: 768px) {
            .header-title {
                font-size: 22px;
            }

            .header-logo {
                max-height: 50px;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 18px;
            }

            .header {
                flex-direction: column;
                text-align: center;
            }
        }

        @font-face {
            font-family: 'Tajwal';
            src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>

<body>

    <header id="header" class="header d-flex justify-content-center align-items-center flex-wrap text-center"
        style="padding: 10px 20px; gap: 20px;">
        <p class="header-title">
            مركز تكوين المؤسسة العمومية للنقل الحضري والشبه الحضري سيدي بلعباس
        </p>
        <img src="/LOGO ETUS.png" alt="Logo" class="header-logo">
    </header>

    <div class="welcome container" style="font-family: 'Tajwal', sans-serif;">
        <h2 class="mb-4" style="font-family: 'Tajwal', sans-serif;font-weight: bold;">أهلاً بكم في منصة التسجيل
            الإلكتروني</h2>
        <p class="lead mb-4">
            تمكنكم هذه المنصة من تقديم طلب الحصول على دفتر المقاعد<br>
            لمزاولة نشاط سائق سيارة الأجرة لسنة <strong>2025</strong>.
        </p>
        <style>
            .custom-tab-btn {
                font-family: 'Tajwal', sans-serif;
                font-weight: 600;
                font-size: 18px;
                color: #012970;
                border: 2px solid #012970;
                border-radius: 10px;
                background-color: transparent;
                padding: 10px 25px;
                transition: all 0.3s ease-in-out;
            }

            .custom-tab-btn:hover,
            .custom-tab-btn:focus {
                background-color: #012970;
                color: white;
            }

            .custom-tab-btn.active {
                background-color: #012970;
                color: white;
            }

            .tab-card {
                direction: rtl;
                font-family: 'Tajwal', sans-serif;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-bottom: 20px;
                transition: transform 0.3s ease;
            }

            .tab-card:hover {
                transform: translateY(-5px);
            }

            .tab-card h1 {
                font-size: 24px;
                color: #012970;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .tab-card p {
                font-size: 16px;
                color: #444;
                margin-bottom: 20px;
            }

            .tab-card a {
                text-decoration: none;
                color: white;
                background-color: #012970;
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 16px;
                transition: background-color 0.3s;
                display: inline-block;
            }

            .tab-card a:hover {
                background-color: #001f4d;
            }
        </style>

        <div class="d-flex gap-2 justify-content-center flex-wrap" role="tablist">
            <a class="custom-tab-btn active" data-bs-toggle="tab" data-bs-target="#bordered-home" type="button"
                role="tab" id="home-tab" aria-controls="bordered-home" aria-selected="true">
                الخواص
            </a>

            <a class="custom-tab-btn" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" id="profile-tab" aria-controls="bordered-profile" aria-selected="false">
                المؤسسات
            </a>
        </div>

        <div class="tab-content pt-2" id="borderedTabContent">
            <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container mt-4">
                    <div class="row g-4 justify-content-center">

                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="tab-card h-100 w-100">
                                <h1>طلب دفتر المقاعد 2025</h1>
                                <p>
                                    يمكنك من خلال هذا القسم تقديم طلب دفتر المقاعد المخصص لسائقي سيارات الأجرة الفرديين
                                    لسنة 2025.
                                </p>
                                <a href="#">ابدأ الطلب</a>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="tab-card h-100 w-100">
                                <h1>طلب دفتر المقاعد</h1>
                                <p>
                                    يمكنك من خلال هذا القسم تقديم طلب دفتر المقاعد المخصص لسائقي سيارات الأجرة الفرديين.
                                </p>
                                <a href="#">ابدأ الطلب</a>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="tab-card h-100 w-100">
                                <h1> شهادة الكفاءة المهنية سائقي مركبات نقل الأشخاص</h1>
                                <p>
                                    يمكنك من خلال هذا القسم تقديم طلب التكوين للحصول على شهادة الكفاءة المهنية سائقي
                                    مركبات نقل الأشخاص. </p>
                                </p>
                                <a href="#">ابدأ الطلب</a>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="tab-card h-100 w-100">
                                <h1> شهادة الكفاءة المهنية سائقي مركبات نقل البضائع</h1>
                                <p>
                                    يمكنك من خلال هذا القسم تقديم طلب التكوين للحصول على شهادة الكفاءة المهنية سائقي
                                    مركبات نقل البضائع. </p>
                                </p>
                                <a href="#">ابدأ الطلب</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 d-flex">
                            <div class="tab-card h-100 w-100">
                                <h1> تعليم سياقة مركبات ذات محرك</h1>
                                <p>
                                    يمكنك من خلال هذا القسم تقديم طلب التكوين للحصول على شهادة الكفاءةالمهنية و البيداغوجية لتعليم سياقة مركبات ذات محرك بمختلف الأصناف (A, B, C, D) </p>
                                </p>
                                <a href="#">ابدأ الطلب</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="container mt-4">
                    <div class="tab-card">
                        <h1>طلب دفتر المقاعد - الخواص</h1>
                        <p>
                            يمكنك من خلال هذا القسم تقديم طلب دفتر المقاعد المخصص لسائقي سيارات الأجرة الفرديين لسنة
                            2025.
                        </p>
                        <a href="#">ابدأ الطلب</a>
                    </div>

                    <div class="tab-card">
                        <h1>طلب دفتر المقاعد - المؤسسات</h1>
                        <p>
                            هذا القسم مخصص للمؤسسات العاملة في مجال النقل الحضري التي ترغب في تقديم طلب دفتر المقاعد
                            للسائقين التابعين لها.
                        </p>
                        <a href="#">ابدأ الطلب</a>
                    </div>
                </div>
            </div>
        </div>
        {{-- 
    <footer>
        &copy; 2025 - جميع الحقوق محفوظة. ETUS - سيدي بلعباس
    </footer> --}}

</body>
<script src="/theme/tomselect/tom-select.complete.min.js"></script>
<script src="/theme/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/theme/assets/vendor/chart.js/chart.umd.js"></script>
<script src="/theme/assets/vendor/echarts/echarts.min.js"></script>
<script src="/theme/assets/vendor/quill/quill.js"></script>
<script src="/theme/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/theme/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/theme/assets/vendor/php-email-form/validate.js"></script>
<script src="/theme/assets/js/main.js"></script>

</html>
