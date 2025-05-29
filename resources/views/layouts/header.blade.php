<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/img/LogoPertamina.png"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>@yield('title', 'Home')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body>
    <main>
         <div class="container">
        @yield('content')
    </div>
    </main>
</body>
</html>