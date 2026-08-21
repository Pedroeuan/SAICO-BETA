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

 /* ==============================
           CONTRATOS
        ============================== */

        .contratos-container {
            max-width: 1100px;

            margin: 0 auto;

            background: rgba(255,255,255,0.96);

            padding: 30px;

            border-radius: 15px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .contratos-container h2 {
            margin-top: 0;

            margin-bottom: 30px;

            color: #333;
        }

        .contratos-grid {

            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(280px, 1fr));

            gap: 20px;

        }

        .contrato-card {

            background: white;

            border-radius: 12px;

            padding: 25px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.12);

            transition: 0.2s;

            text-align: left;

            border: 1px solid #e5e5e5;
        }

        .contrato-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 6px 18px rgba(0,0,0,0.18);
        }

        .contrato-card h3 {

            margin-top: 0;

            color: #1f4e79;

            font-size: 22px;
        }

        .contrato-card p {

            color: #666;

            margin: 8px 0;
        }

        .btn-contrato {

            display: inline-block;

            margin-top: 15px;

            padding: 10px 18px;

            background-color: #1f4e79;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            transition: 0.2s;
        }

        .btn-contrato:hover {

            background-color: #163a5c;

            color: white;
        }

        /* ==============================
           SIN CONTRATOS
        ============================== */

        .sin-contratos {

            padding: 30px;

            color: #777;

            text-align: center;
        }

        /* ==============================
            RESPONSIVE
        ============================== */

        @media(max-width: 768px) {

            .header-navbar {

                padding: 10px 15px;

            }

            .header-navbar img {

                max-height: 45px;

            }

            .bienvenida h1 {

                font-size: 25px;

            }

            .contratos-container {

                padding: 20px;

            }

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

        <!-- ==================================
            CONTRATOS
        =================================== -->

        <div class="contratos-container">

            <h2>
                Mis contratos
            </h2>


            @if($contratos->count() > 0)

                <div class="contratos-grid">

                    @foreach($contratos as $nombreContrato => $proyectos)

                        <div class="contrato-card">
                            <p>
                                <strong>Contrato:</strong>
                            </p>
                            <h3>
                                {{ $nombreContrato }}
                            </h3>

                            <p>
                                <strong>Proyecto / Actividad:</strong>
                            </p>

                            <ul>
                                @foreach($proyectos as $proyecto)

                                    <li>
                                        {{ $proyecto->Proyecto_actividad }}
                                    </li>

                                @endforeach
                            </ul>

                            <a
                                href="{{ route(
                                    'portal.contrato',
                                    [
                                        'token' => request()->route('token'),
                                        'contrato' => $nombreContrato
                                    ]
                                ) }}"
                                class="btn-contrato">

                                Reportes- por realizaar

                            </a>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="sin-contratos">

                    <h3>
                        No hay contratos registrados
                    </h3>

                    <p>
                        Actualmente no existen contratos asociados a este cliente.
                    </p>

                </div>

            @endif

        </div>
    </main>


</body>

</html>