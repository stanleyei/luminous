<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="description" content="各式飾品拍賣">
        <meta name="author" content="Luminous">
        @if (env('GOOGLE_REBOT_BAN', false))
          <meta name="robots" content="noindex, nofollow">
          <meta name="googlebot" content="noindex">
        @endif
        <title inertia>{{ config('app.name', 'Luminous') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;700&display=swap" rel="stylesheet">
        <link href="https://db.onlinewebfonts.com/c/84cb021d5f9af287ffff84b61beef6dc?family=clockicons" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-main-yellow/30">
        @inertia

        <noscript>
          <p>Luminous</p>
          <p>您的瀏覽器不支援 JavaScript 或 JavaScript已停用，請先至您的瀏覽器設定中開啟 JavaScript</p>
        </noscript>
        <noscript>Your browser does not support JavaScript. If the webpage function is not working properly, please open the browser JavaScript status.</noscript>
    </body>
</html>
