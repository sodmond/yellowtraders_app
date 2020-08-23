<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Yellow Traders App</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Favicon -->
        <link href="{{ asset('images/logo.png') }}" rel="icon" type="image/png">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #1a2035;
                color: #fafafa;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 74px;
            }

            .links > a {
                color: #fafafa;
                padding: 0 25px;
                margin: 5px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: underline;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .apply a{
                    background: #EEB220;
                    color: #000;
                    padding: 15px;
                    margin: 10px;
                    border-radius: 10px;
                    text-decoration: none;
            }
            @media only screen and (max-width:808px){
                .apply a{ display: block; padding: 15px 100px; }
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links" style="text-decoration: underline;">
                    @auth
                        <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                    {{--
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                        --}}
                    @endauth

                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <div><img src="{{ asset('images/logo.png') }}" class="img-fluid" alt="Yellow Traders Logo"></div>
                </div>

                <div class="links apply">
                    <a target="_blank" href="{{ url('/apply/yellow_traders') }}">Yellow Traders</a>
                    <a target="_blank" href="{{ url('/apply/junior_traders') }}">Junior Traders</a>
                    <a target="_blank" href="{{ url('/apply/corporate_traders') }}">Corporate Traders</a>
                    <a target="_blank" href="{{ url('/apply/topup_rollover') }}">Topup / Rollover</a>
                </div>
            </div>
        </div>
    </body>
</html>
