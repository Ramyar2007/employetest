<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Employee Payout Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @routes
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>
