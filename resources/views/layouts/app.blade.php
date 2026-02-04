<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Council of Homoeopathic Medicine, WB - Application</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />


    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="/assets/css/app.css" />

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LexgggsAAAAAHAhuGvkV6GcBjylkdef4gzCb9MU"></script>
    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen">
    @yield('content')

    @livewireScripts
</body>

</html>
