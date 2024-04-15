<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}}</title>
    @vite(['resource/assets/js/app.js', 'resource/assets/css/app.scss'])
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css"
    >
    <script src="https://kit.fontawesome.com/d1901f5db9.js" crossorigin="anonymous"></script>
</head>
<body>

{{ $slot }}
</body>
</html>
