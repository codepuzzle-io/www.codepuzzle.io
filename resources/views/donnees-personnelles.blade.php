<?php
include('github-import.php');
$github_document = github_import('codepuzzle-io/www.codepuzzle.io/contents/donnees-personnelles.md');
?>
@include('inc-top')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | Politique de protection des données</title>
</head>
<body data-spy="scroll" data-target="#navbar-scrollspy" data-offset="20">

	<div class="container mb-5">

		<div class="row">
			<div class="col-md-3">
				<div class="sticky-top mb-5 pt-2">
					@include('inc-nav')
					<?php echo $github_document['menu'] ?>
				</div>
			</div>
			<div class="col-md-9 text-justify">
				<?php echo $github_document['content'] ?>
			</div>
		</div>

	</div><!-- /container -->
	@include('inc-bottom-js')
</body>
</html>
