<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ $cliente->Cliente }}</title>


    <style>

        body {

            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

            background-image: url('{{ asset('images/fondo-portal.jpg') }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            background-attachment: fixed;

            min-height: 100vh;

        }


        /*
        |--------------------------------------------------------------------------
        | ENCABEZADO
        |--------------------------------------------------------------------------
        */

        .header-navbar {

            position: relative;

            height: 80px;

            background-color: #ffffff;

            padding: 15px 30px;

            box-shadow: 0 2px 4px rgba(0,0,0,0.1);

        }


        /*
        |--------------------------------------------------------------------------
        | LOGO AICO
        |--------------------------------------------------------------------------
        */

        .logo-aico {

            position: absolute;

            left: 30px;

            top: 15px;

        }


        /*
        |--------------------------------------------------------------------------
        | LOGO SAICO
        |--------------------------------------------------------------------------
        */

        .logo-centro {

            position: absolute;

            left: 50%;

            top: 15px;

            transform: translateX(-50%);

        }


        /*
        |--------------------------------------------------------------------------
        | IMÁGENES DEL ENCABEZADO
        |--------------------------------------------------------------------------
        */

        .header-navbar img {

            max-height: 50px;

            width: auto;

        }


        /*
        |--------------------------------------------------------------------------
        | CONTENIDO
        |--------------------------------------------------------------------------
        */

        .content {

            padding: 30px 20px;

            text-align: center;

        }


        /*
        |--------------------------------------------------------------------------
        | LOGO DEL CLIENTE
        |--------------------------------------------------------------------------
        */

        .logo-cliente {

            margin-bottom: 20px;

        }


        .logo-cliente img {

            max-width: 300px;

            max-height: 120px;

            width: auto;

            height: auto;

            object-fit: contain;

        }


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DEL CLIENTE
        |--------------------------------------------------------------------------
        */

        .content h1 {

            margin-top: 10px;

            margin-bottom: 10px;

        }


    </style>

</head>


<body>


    <!--
    |--------------------------------------------------------------------------
    | ENCABEZADO
    |--------------------------------------------------------------------------
    -->

    <header class="header-navbar">


        <!-- LOGO AICO - IZQUIERDA -->

        <div class="logo-aico">

            <img src="{{ asset('images/Logo_AICO_R.jpg') }}"
                alt="Logo AICO">

        </div>


        <!-- SAICO - CENTRO -->

        <div class="logo-centro">

            <img src="{{ asset('images/saico3.png') }}"
                alt="Logo SAICO">

        </div>


    </header>



    <!--
    |--------------------------------------------------------------------------
    | CONTENIDO
    |--------------------------------------------------------------------------
    -->

    <main class="content">


        <!-- LOGO DEL CLIENTE -->

        <div class="logo-cliente">

            @if($cliente->logo)

                <img src="{{ asset('storage/' . $cliente->logo) }}"
                    alt="{{ $cliente->Cliente }}">

            @else

                <h2>
                    {{ $cliente->Cliente }}
                </h2>

            @endif

        </div>


        <!-- BIENVENIDA -->

        <h1>
            Bienvenido, {{ $cliente->Cliente }}
        </h1>


        <p>
            Este es el portal de {{ $cliente->Cliente }}.
        </p>


    </main>


</body>

</html>