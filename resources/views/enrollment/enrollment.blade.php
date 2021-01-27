<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- <link rel="icon" href="<%= BASE_URL %>favicon.ico"> -->
        <link rel="icon" href="images/favicon.ico">
        <title>MNHS Enrollment System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('stylesheet/style.css') }}">

        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

        <script src="{{ asset('jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    </head>
<body>
    <div id="app">
    <enrollment-form></enrollment-form>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>