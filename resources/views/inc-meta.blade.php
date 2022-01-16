<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/fd76a35a36.js" crossorigin="anonymous"></script>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

<!-- Description -->
<meta name="description" content="CODE PUZZLE | {{ $description ?? '' }}" />

<!-- Open Graph -->
<meta property="og:title" content="Code Puzzle" />
<meta property="og:type" content="website" />
<meta property="og:description" content="{{__('Générateur et gestionnaire de puzzles de Parsons')}} {{ $description_og ?? '' }}" />
<meta property="og:url" content="https://www.codepuzzle.io" />
<meta property="og:image" content="{{ asset('img/open-graph.png') }}" />
<meta property="og:image:alt" content="Code Puzzle" />
<meta property="og:image:type" content="image/png" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="">
<meta name="twitter:creator" content="">
<meta name="twitter:title" content="Code Puzzle">
<meta name="twitter:description" content="{{__('Générateur et gestionnaire de puzzles de Parsons')}} {{ $description_og ?? '' }}">
<meta name="twitter:image" content="{{ asset('img/open-graph.png') }}">
