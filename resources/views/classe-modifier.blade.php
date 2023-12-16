<?php
$classe = App\Models\Classe::where('jeton_secret', $jeton_secret)->first();
if (!$classe){
    echo "<pre>Cet classe n'existe pas</pre>";
    exit();
}
$eleves = App\Models\Classes_eleve::where('id_classe', $classe->id)->orderby('eleve')->get();
?>
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
				<a class="btn btn-light btn-sm" href="/classe-console/{{$jeton_secret}}" role="button"><i class="fas fa-arrow-left"></i></a>
			</div>

			<div class="col-md-8 pl-4 pr-2">

				<form method="POST" action="{{route('classe-modifier-post')}}">

					@csrf
				
					<!-- NOM DE LA CLASSE -->
					<div class="text-monospace">{{strtoupper(__('nom de la classe'))}}<sup class="text-danger small">*</sup></div>
					<input id="nom_classe" type="text" class="form-control @error('nom_classe') is-invalid @enderror" name="nom_classe" value="{{ old('nom_classe') ?? $classe->nom_classe }}" autofocus>
					@error('nom_classe')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /NOM DE LA CLASSE -->

					<!-- IDENTIFIANTS -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('IDENTIFIANTS'))}}</div>
					@if($errors->has('maj_eleves.*'))
						<div class="text-danger small">
							<strong>{{ $errors->first('maj_eleves.*') }}</strong>
						</div>
					@endif
					@if (sizeof($eleves) != 0)
						<table>
							@foreach($eleves AS $eleve)
								<tr>
									<td style="width:100%">
										<input id="input_{{$eleve->id}}" class="form-control @error('maj_eleves.'.$eleve->id) is-invalid @enderror" type="text" name="maj_eleves[{{$eleve->id}}]" value="{{ old('maj_eleves.'.$eleve->id) ?? $eleve->eleve ?? '' }}" />
									</td>
									<td class="text-monospace pl-3 pr-3"><div id="code_{{$eleve->id}}">{{strtoupper($eleve->jeton_eleve)}}</div></td>
									<td><div id="icon_{{$eleve->id}}" class="text-muted" style="cursor:pointer;" onclick="supprimer_eleve({{ $eleve->id }}, '{{$eleve->eleve}}')"><i class="fas fa-trash"></i></div></td>
								</tr>
							@endforeach
						</table>
					@else
						<div class='text-muted text-monospace small'>Pas d'élève dans cette classe.</div>
					@endif
					<!-- /IDENTIFIANTS -->


					<!-- ACTIVITES -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('ACTIVITÉS'))}}</div>
					<div class="pt-2 text-monospace">
						<?php
						if (!empty(array_filter(unserialize($classe->activites)))) {
							echo '<table class="text-muted">';
							foreach(unserialize($classe->activites) AS $code) {
								if (substr($code, 0, 1) == 'D') {
									$activite_info = App\Models\Defi::where('jeton', substr($code, 1))->first();
								}
								?>
								<tr>
								<td style="width:100%"><div id="activite_titre_{{ $code }}">{{ $activite_info->titre_enseignant }}</div></td>
								<td class="pl-3 pr-3"><a id="activite_lien_{{ $code }}" href="/{{ $code }}" target="_blank">www.codepuzzle.io/{{ $code }}</a></td>
								<td>
									<div id="activite_icon_{{ $code}}" class="text-muted" style="cursor:pointer;" onclick="supprimer_activite('{{ $code }}')"><i class="fas fa-trash"></i></div>
									<input id="activite_input_{{ $code}}" type="hidden" name="maj_activites[]" value="{{ $code }}" />
								</td>
								</tr>
								<?php
							}
							echo '</table>';

						} else {
							echo "<div class='text-muted small'>Pas d'activités proposées. Cliquer sur \"modifier\" pour ajouter des activités à proposer aux élèves.</div>";
						}
						?>
					</div>
					<!-- /ACTIVITES -->


					<!-- AJOUTER ELEVES -->
					<a id="eleves_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('AJOUTER DES ÉLÈVES'))}}</div>
					<div class="text-monospace small text-danger">Ne pas utiliser le nom complet des élèves</div>
					<div class="text-monospace small text-muted">Un identifiant (prénom, initiales, pseudo...) par ligne</div>
					<textarea id="ajout_eleves" name="ajout_eleves" class="form-control @error('ajout_eleves') is-invalid @enderror"" rows="5">{{ old('ajout_eleves') }}</textarea>
					@error('ajout_eleves')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /AJOUTER ELEVES -->


					<!-- AJOUTER ACTIVITES -->
					<a id="activites_anchor"></a>
					<div class="mt-4 text-monospace">{{strtoupper(__('AJOUTER DES ACTIVITÉS'))}}</div>
					<div class="text-monospace small text-muted pb-1">Les activités indiquées ci-dessous apparaîtront dans la console des élèves. Une activité peut être un puzzle ou un défi. Saisir les codes des activités séparés par des virgules. Exemple: DQMSK,DXSR8,DWMX2,DEHSD,DL92R</div>
					<textarea id="ajout_activites" name="ajout_activites" class="form-control @error('ajout_activites') is-invalid @enderror" rows="2">{{ old('ajout_activites') }}</textarea>
					@error('ajout_activites')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
					<!-- /AJOUTER ACTIVITES -->

					<input type="hidden" name="jeton_secret" value="{{$jeton_secret}}" />
					<input type="hidden" name="id_classe" value="{{$classe->id}}" />
					<div>
						<button type="submit" class="btn btn-success mt-4 mb-5 pl-4 pr-4"><i class="fas fa-check"></i></button>
					</div>
				</form>

			</div>

		</div><!-- row -->

	</div><!-- container -->

	@include('inc-bottom-js')

	<script>
		function supprimer_eleve(eleve_id, eleve_nom) {			
			if (document.getElementById("icon_"+eleve_id).innerHTML == '<i class="fas fa-trash"></i>') {
				document.getElementById("input_"+eleve_id).readOnly = true;
				document.getElementById("input_"+eleve_id).value = "";
				document.getElementById("input_"+eleve_id).placeholder = eleve_nom;
				document.getElementById("input_"+eleve_id).style.textDecoration = "line-through";
				document.getElementById("code_"+eleve_id).style.textDecoration = "line-through";
				document.getElementById("icon_"+eleve_id).innerHTML = '<i class="fas fa-trash-restore"></i>';
			} else {
				document.getElementById("input_"+eleve_id).readOnly = false;
				document.getElementById("input_"+eleve_id).value = eleve_nom;
				document.getElementById("input_"+eleve_id).placeholder = "";
				document.getElementById("input_"+eleve_id).style.textDecoration = "none";
				document.getElementById("code_"+eleve_id).style.textDecoration = "none";
				document.getElementById("icon_"+eleve_id).innerHTML = '<i class="fas fa-trash"></i>';
			}
		}

		function supprimer_activite(code) {			
			if (document.getElementById("activite_icon_"+code).innerHTML == '<i class="fas fa-trash"></i>') {
				document.getElementById("activite_titre_"+code).style.textDecoration = "line-through";
				document.getElementById("activite_lien_"+code).style.textDecoration = "line-through";
				document.getElementById("activite_input_"+code).value = "";
				//document.getElementById("input_"+eleve_id).placeholder = eleve_nom;
				//document.getElementById("input_"+eleve_id).style.textDecoration = "line-through";
				//document.getElementById("code_"+eleve_id).style.textDecoration = "line-through";
				document.getElementById("activite_icon_"+code).innerHTML = '<i class="fas fa-trash-restore"></i>';
			} else {
				document.getElementById("activite_titre_"+code).style.textDecoration = "none";
				document.getElementById("activite_lien_"+code).style.textDecoration = "none";
				document.getElementById("activite_input_"+code).value = code;
				//document.getElementById("input_"+eleve_id).placeholder = "";
				//document.getElementById("input_"+eleve_id).style.textDecoration = "none";
				//document.getElementById("code_"+eleve_id).style.textDecoration = "none";
				document.getElementById("activite_icon_"+code).innerHTML = '<i class="fas fa-trash"></i>';
			}
		}		
	</script>
</body>
</html>
