<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel with Vue</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css"
    />

    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        <create-deal-to-account></create-deal-to-account>
    </div>

</body>
</html>
