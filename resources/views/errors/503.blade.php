<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Currently Under Maintenance</title>

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
                font-size: 64px;
            }

            p {
                color: #fafafa;
                padding: 0 25px;
                margin: 5px;
                font-size: 16px;
                font-weight: 600;

            }

            p > a:link{ color: #EEB220; }

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


            <div class="content">
                <div class="m-b-md">
                    <div><img src="https://yellowtraders.org/wp-content/uploads/2020/06/YELLOW200-e1597321367701.png" class="img-fluid" alt="Yellow Traders Logo"></div>
                    <h2 class="title">We will be back shortly</h2>
                    <p>Sorry for the inconvenience, we are currently performing some maintenance at the moment. Please visit back shortly,
                        you can click here to <a href="https://yellowtraders.org/contact/" target="_blank">contact</a> our customer service for urgent matters.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
