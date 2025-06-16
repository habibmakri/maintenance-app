@extends('inscription_formation.base_insc')
@section('title', 'نجاح التسجيل')
@section('content')

    <main id="main" class="main" style="justify-items: center;">
        <div class="d-flex" style="align-items: center;justify-content: center;flex-direction:column;">
            <h1 class="mb-5 mt-5 pt-1"
                style="font-family: 'Tajwal', sans-serif;font-size:32px;text-align: center;font-weight:bold;">
                @if ($type_insc == 'taxi')
                التسجيل الالكتروني للحصول على دفتر المقاعد <br>
                @endif
            </h1>
        </div>
        <style>
            label {
                inset-inline-end: auto !important;
            }

            input[type="submit"] {
                display: block;
                width: 60%;
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
        </style>

        <div style="text-align: center; margin-top: 50px;">
            <h2 style="color: green;">✔ ثم التسجيل بنجاح</h2>
            <p>يرجى الانتظار قليلاً، سيتم تحميل الوصل تلقائيًا.</p>
            <p>
                <a href="{{ route('inscription.download', [$type_insc, $taxi->id]) }}" target="_blank"
                    style="color: red; font-weight: bold;">
                    إضغط هنا إذا لم يتم تحميل الوصل تلقائيًا
                </a>
            </p>
        </div>

        <script>
            window.onload = function() {
                window.open("{{ route('inscription.download', [$type_insc,$taxi->id]) }}", "_blank");
            };
        </script>
    </main>
@endsection
