<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpg') }}">

    <!-- RSS Feed -->
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} RSS Feed" href="{{ url('feed.xml') }}">

    <script src="https://kit.fontawesome.com/d1901f5db9.js" crossorigin="anonymous"></script>

</head>
<body>

{{ $slot }}
</body>
</html>
