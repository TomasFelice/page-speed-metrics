<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.app_name'))</title>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    @include('partials.styles')
</head>

<body class="bg-gray-100 text-gray-900">

    @include('partials.header')

    @include('partials.content')

    @include('partials.footer')

    @include('partials.scripts')
</body>

</html>
