<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>قيمني</title>
    <!-- Favicons -->
    {{-- <link href="assets/img/favicon.png" rel="icon"> --}}
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Tajwal';
            src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Scheherazad';
            src: url('{{ asset('theme/fonts/Scheherazade_New/ScheherazadeNew-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Urdu';
            src: url('{{ asset('theme/fonts/Noto_Nastaliq_Urdu/Noto_Nastaliq_Urdu/static/NotoNastaliqUrdu-Medium.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Tajwal', sans-serif;
            background-color: #f8f9fa;
            color: #012970;
        }

        main {
            width: 100%;
            padding: 16px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 16px;
            text-align: center;
            color: #212529;
        }

        p {
            font-size: 18px;
            margin: 16px 0 8px;
            text-align: center;
        }

        .stars {
            display: flex;
            justify-content: center;
            gap: 16px;
        }
        
        .stars i {
            font-size: 2.5rem;
            cursor: pointer;
            color: #98a6bf;
            /* Couleur par défaut des icônes */
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .stars p {
            cursor: pointer;
            color: #98a6bf;
            /* Couleur par défaut des icônes */
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .stars i:hover {
            transform: scale(1.1);
            /* Zoom au survol */
        }

        textarea,
        input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 16px;
            resize: none;
        }

        textarea:focus,
        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #007bff, #0056b3);

            color: #fff;
            font-size: 18px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #3399ff, #0069d9);
            transform: translateY(-2px);
        }

        input[type="submit"]:active {
            background: linear-gradient(135deg, #0056b3, #003f7f);
            transform: translateY(0);
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 20px;
            }

            p {
                font-size: 16px;
            }

            .stars i {
                font-size: 2rem;
            }

            input[type="submit"] {
                font-size: 16px;
                padding: 10px;
            }

            textarea,
            input[type="text"] {
                font-size: 14px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('/theme/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/simple-datatables/style.css') }}">

</head>

<body class="vh-100">
    <div class="d-flex" style="align-items: center;justify-content: center;flex-direction:column;">
        <h1 class="mb-1 pt-1" style="font-family: 'Tajwal', sans-serif;font-size:18px;text-align: center;">المؤسسة
            العمومية للنقل
            الحضري<br> والشبه الحضري سيدي بلعباس</span>
            <h1 class="mb-1 pt-1" style="font-family: 'Tajwal', sans-serif;font-size:18px;text-align: center;">وكالة
                المراقبة
                التقنية<br>سيدي بلعباس</span>
                <h1 class="mb-4 pt-4"
                    style="font-family: 'Urdu', sans-serif;cursor:default; font-size:30px;text-align: center;">
                    قيمني</span>
    </div>
    <main class="d-flex justify-content-center align-items-center" style="color: #012970;">
        <form action="{{ route('rate_ctechnique') }}" method="post">
            @csrf
            <div class="container text-center px-4">
                <p>كيف تقيم الخدمة بشكل عام</p>
                <div class="stars my-3">
                    <label>
                        <input type="radio" name="service_rating" value="bien" style="display: none;">
                        <i class="bi bi-emoji-smile mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>جيدة</p>
                    </label>
                    <label>
                        <input type="radio" name="service_rating" value="moyen" style="display: none;">
                        <i class="bi bi-emoji-neutral mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>متوسطة</p>
                    </label>
                    <label>
                        <input type="radio" name="service_rating" value="mauvais" style="display: none;">
                        <i class="bi bi-emoji-angry mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>سيئة</p>
                    </label>
                </div>
                <p>مدى رضاك عن تعامل المراقب</p>
                <div class="stars my-3">
                    <label>
                        <input type="radio" name="controler_rating" value="bien" style="display: none;">
                        <i class="bi bi-emoji-smile mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>جيدة</p>
                    </label>
                    <label>
                        <input type="radio" name="controler_rating" value="moyen" style="display: none;">
                        <i class="bi bi-emoji-neutral mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>متوسطة</p>
                    </label>
                    <label>
                        <input type="radio" name="controler_rating" value="mauvais" style="display: none;">
                        <i class="bi bi-emoji-angry mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>سيئة</p>
                    </label>
                </div>
                <p>مدى رضاك عن نظافة الوكالة</p>
                <div class="stars my-3">
                    <label>
                        <input type="radio" name="clean_rating" value="bien" style="display: none;">
                        <i class="bi bi-emoji-smile mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>جيدة</p>
                    </label>
                    <label>
                        <input type="radio" name="clean_rating" value="moyen" style="display: none;">
                        <i class="bi bi-emoji-neutral mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>متوسطة</p>
                    </label>
                    <label>
                        <input type="radio" name="clean_rating" value="mauvais" style="display: none;">
                        <i class="bi bi-emoji-angry mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>سيئة</p>
                    </label>
                </div>
                <p>مدى رضاك عن التنظيم</p>
                <div class="stars my-3">
                    <label>
                        <input type="radio" name="order_rating" value="bien" style="display: none;">
                        <i class="bi bi-emoji-smile mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>جيدة</p>
                    </label>
                    <label>
                        <input type="radio" name="order_rating" value="moyen" style="display: none;">
                        <i class="bi bi-emoji-neutral mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>متوسطة</p>
                    </label>
                    <label>
                        <input type="radio" name="order_rating" value="mauvais" style="display: none;">
                        <i class="bi bi-emoji-angry mx-4" style="font-size: 3.5rem; cursor: pointer;"></i>
                        <p>سيئة</p>
                    </label>
                </div>
                <p class="mt-4">هل تريد إخبارنا بأي شيء؟</p>
                <textarea class="form-control my-3" rows="3" name="message" placeholder="اكتب رسالتك هنا - إختياري"></textarea>

                <p class="mt-4">رقم الهاتف</p>
                <input type="text" class="form-control my-3" name="phone"
                    placeholder=" أدخل رقم الهاتف - إختياري">

                <input type="submit" value="تأكيد">
        </form>
        </div>
    </main>
    <script>
        document.querySelectorAll('.stars input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', (e) => {
                const parent = e.target.closest('.stars');
                parent.querySelectorAll('i').forEach(icon => icon.style.color = '#98a6bf');
                parent.querySelectorAll('p').forEach(icon => icon.style.color = '#98a6bf');
                e.target.nextElementSibling.style.color = '#007bff'; 
                e.target.nextElementSibling.nextElementSibling.style.color = '#007bff'; 
            });
        });
    </script>

    <script src="/theme/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/theme/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="/theme/assets/vendor/echarts/echarts.min.js"></script>
    <script src="/theme/assets/vendor/quill/quill.js"></script>
    <script src="/theme/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/theme/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="/theme/assets/vendor/php-email-form/validate.js"></script>
    <script src="/theme/assets/js/main.js"></script>

</body>

</html>
