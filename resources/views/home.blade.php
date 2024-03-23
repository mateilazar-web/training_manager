<!DOCTYPE html>
<html>

<head>
    <title>Training Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/order_list.css') }}" rel="stylesheet" type="text/css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>

    <div class="container box">
        <div class="box-container">
            <div class="row header">
                @include('nav-bar')
            </div>
            <div class="row content">
                <style>
                    html,
                    body {
                        height: 100%;
                        margin-top: 0;
                        margin-bottom: 0;
                    }

                    .h_iframe iframe {
                        width: 100%;
                        height: 100%;
                    }

                    .h_iframe {
                        height: 100%;
                    }
                </style>
                <div class="container-fluid">
                    <section class="h_iframe">
                        <iframe src="{{ route('dashboard') }}" frameborder="0" allowfullscreen></iframe>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
