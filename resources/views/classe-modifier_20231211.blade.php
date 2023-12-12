<!doctype html>
<html lang="fr">
<head>
	@include('inc-meta')
    <title>{{ config('app.name') }} | {{ ucfirst(__('nouveau défi')) }}</title>
</head>
<body>

	@if(Auth::check())
		@include('inc-nav-console')
	@else
		@include('inc-nav')
	@endif

	<div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2 text-right">
				@if(Auth::check())
				<a class="btn btn-light btn-sm" href="/console/defis" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				<a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				@endif
			</div>

			<div class="col-md-6 pl-4 pr-4">

				<h1>{{__('nouvelle classe')}}</h1>

				<form method="POST" action="{{route('classe-creer-post')}}">

					@csrf
				
					<!-- NOM DE LA CLASSE -->
					<div class="text-monospace">{{strtoupper(__('nom de la classe'))}}<sup class="text-danger small">*</sup></div>
					<div style="padding-right:23px;">
						<input id="nom_classe" type="text" class="form-control @error('nom_classe') is-invalid @enderror" name="nom_classe" value="{{ old('nom_classe') }}" autofocus>
						@error('nom_classe')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<!-- /NOM DE LA CLASSE -->


					<!-- ELEVES -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('élèves'))}}</div>
					<a id="add_button" class="btn btn-light btn-sm mt-1" href="#eleves_anchor" role="button"><i class="fas fa-plus"></i></a>	
					<table id="eleves_table" style="width:100%">
						<tr>
							<td class=""><input type="text" class="form-control @error('eleves.0') is-invalid @enderror" style="width:100%" name="eleves[]" value="{{old('eleves.0')}}" autofocus></td>
							<td style="width:23px">&nbsp;</td>
						</tr>					
						@if (!empty(old('eleves')))
						@foreach(old('eleves') as $key => $eleves)
						@if ($key !== 0)
							<tr>
								<td class=""><input type="text" class="form-control" name="eleves[]" value="{{$eleves}}" autofocus></td>
								<td><a href="#eleves_anchor" onclick="suppressionEleve(this)"><i class="ml-2 fas fa-trash" aria-hidden="true"></i></a></td>
							</tr>
						@endif
						@endforeach
						@endif
					</table>
						
					<!-- /ELEVES -->

					<input id="lang" type="hidden" name="lang" value="{{app()->getLocale()}}" />
					<div>
						<button type="submit" class="btn btn-primary mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
					</div>
				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

	<script>
		const table = document.getElementById('eleves_table');
		function ajoutEleve() {
			removeButton = document.createElement('a');
			removeButton.href = "#eleves_anchor";
			removeButton.innerHTML = '<i class="ml-2 fas fa-trash"></i>';	
			newRow = table.insertRow();
			newCell1 = newRow.insertCell();
			newCell1.style.width = '100%';
			newCell2 = newRow.insertCell();
			newCell1.innerHTML = '<input type="text" class="form-control" name="eleves[]" />';
			newCell2.appendChild(removeButton);		
			removeButton.addEventListener('click', function() {
				this.parentNode.parentNode.remove();
			});
		}
		function suppressionEleve(tag) {
			tag.parentNode.parentNode.remove();
		}
		document.getElementById('add_button').addEventListener('click', ajoutEleve);
	</script>

</body>
</html>
