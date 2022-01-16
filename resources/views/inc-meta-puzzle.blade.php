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

<!-- PARSON CSS -->
<link href="{{ asset('css/parsons.css') }}" rel="stylesheet">
<link href="{{ asset('css/prettify.css') }}" rel="stylesheet">

<!-- PARSON JS -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/underscore-min.js') }}"></script>
<script src="{{ asset('js/lis.js') }}"></script>
<script src="{{ asset('js/prettify.js') }}"></script>
<script src="{{ asset('js/parsons.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">

<!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

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
