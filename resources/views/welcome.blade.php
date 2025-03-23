<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name', 'Laravel') }} - Tu aplicación web">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @if(app()->environment('development'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp
        <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/css/app.css']['file']) }}">
        <script type="module" src="{{ asset('build/'.$manifest['resources/js/app.js']['file']) }}" defer></script>
        @if(isset($manifest['resources/css/app.css']['css']))
            @foreach($manifest['resources/css/app.css']['css'] as $css)
                <link rel="stylesheet" href="{{ asset('build/'.$css) }}">
            @endforeach
        @endif
        @if(isset($manifest['resources/js/app.js']['css']))
            @foreach($manifest['resources/js/app.js']['css'] as $css)
                <link rel="stylesheet" href="{{ asset('build/'.$css) }}">
            @endforeach
        @endif
    @endif
</head>
<body class="min-h-screen bg-gray-100">
    <div id="app"></div>
</body>
</html>
