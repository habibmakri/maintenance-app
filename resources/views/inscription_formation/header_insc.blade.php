<style>
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
</style>

<header id="header" class="header d-flex justify-content-center align-items-center flex-wrap text-center"
    style="padding: 10px 20px; gap: 20px;">
    <p class="header-title">
        مركز تكوين المؤسسة العمومية للنقل الحضري والشبه الحضري سيدي بلعباس
    </p>
    <img src="/LOGO ETUS.png" alt="Logo" class="header-logo">
</header>
