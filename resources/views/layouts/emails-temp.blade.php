<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="x-apple-disable-message-reformatting">

        <title></title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #222;
                font-family: 'Poppins', sans-serif;
                font-weight: 200;
                height: 100% !important;
                width: 90% !important;
                margin: 0 auto !important;
            }
            a{ color: #3490DC;}
            #container{
                text-align: center;

            }
            .header, .footer{
                background-color: #666;
                color: #eee;
                padding: 10px;
                font-size: 12px;
            }
            .content{
                font-size: 16px;
                padding: 10px;
                background-color: #fff;
            }
            table{
                border:0;
                width:100%;
            }
            table tbody tr td{ padding: 0px 10px;}
            strong{
                font-weight: 500;
            }
            .footer a{
                color:#fafafa;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div class="header">
                <img src="{{ asset('images/logo.png') }}" alt="Yellow Traders" width="120">
            </div>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                <p>© {{ date('Y') }} Yellow Traders. All rights reserved.</p>
                <p>No. 17 Iyalla Street, off Kafi Street, beside Shoprite. Ikeja, Lagos, Nigeria</p>
                <p><strong>Email:</strong> <a href="mailto:yellowcare@yellowtraders.org">yellowcare@yellowtraders.org</a> |
                <a href="mailto:info@yellowtraders.org">info@yellowtraders.org</a></p>
                <p><strong>Phone:</strong> +2349044777007, +2349044777077, +2349044777017, +2349044777117</p>
            </div>
        </div>
    </body>
</html>
