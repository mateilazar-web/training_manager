<!DOCTYPE html>
<html>
<head>
    <title>Training Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/order_list.css') }}" rel="stylesheet" type="text/css" >

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


    <script src="https://cdn.tiny.cloud/1/wzzr6snggxr76v3gp02x7mvgi377u1f1e2g4wa1g5jqghzwo/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>
        tinymce.init({
        selector : "textarea.editme",
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | addcomment showcomments | spellcheckdialog | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Matei Lazar',
        mergetags_list: [
            { value: 'Matei.Lazar', title: 'First Name' },
            { value: 'matei.lazar@moduscreate.com', title: 'Email' },
        ]
        });
    </script>    

    @stack('head')

    

</head>
<body>

<div class="container">
    @include('nav-bar') 
    <br>
    
    @yield('content')
</div>

</body>
</html>