<!DOCTYPE html>
<html>

<head>
    <title>Training Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/order_list.css') }}" rel="stylesheet" type="text/css">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>

    <div class="container box">
        <div class="box-container">
            <div class="row header">
                @include('nav-bar')
            </div>
            <div class="row content">
                <style>
                    html, body {
                            margin: 0;
                            padding: 0;
                            height: auto;
                            overflow-y: auto; 
                        }

                        .h_iframe {
                            width: 100%;
                            height: auto; 
                            margin-bottom: 20px; 
                        }

                        .h_iframe iframe {
                            width: 100%;
                            height: 100%;
                            border: none;
                        }
                </style>
                <div class="container-fluid">
                    <section class="h_iframe">
                        <iframe src="{{ route('dashboard') }}" frameborder="0" ></iframe>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>

</html>