<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title >{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet" />

        <meta name="description" content="Bienvenid@ a fuego bites">
        <meta name="theme-color" content="#D7033D">
        <meta name="twitter:card" content="summary">
        <meta name="theme-color" content="#D7033D">
        <meta name="twitter:card" content="summary">
        <meta property="og:type" content="website">
        <meta property="og:title" name="twitter:title" content="Fuego Bites - Date un gusto ">
        <meta property="og:description" name="twitter:description" content="Bienvenid@ a fuego bites">

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="description" content="">
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="bg-light">
        @inertia
    </body>
</html>
