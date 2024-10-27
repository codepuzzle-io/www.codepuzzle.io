<?php
$devoir = App\Models\Devoir::where('jeton_secret', $jeton_secret)->first();
if (!$devoir) {
    echo "<pre>Ce devoir n'existe pas</pre>";
    exit();
}
$copies = App\Models\Copie::where('jeton_devoir', $devoir->jeton)->orderBy('pseudo')->get();
$sujet = App\Models\Sujet::find($devoir->sujet_id);
$sujet_json = json_decode($sujet->sujet);
$page_devoir_console = true;
?>
<!doctype html>
<html lang="fr">
<head>
    @include('inc-meta')
    <link href="{{ asset('css/highlight.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde.css') }}" rel="stylesheet">
	<link href="{{ asset('css/easymde-custom.css') }}" rel="stylesheet">
    <meta name="robots" content="noindex">

    <script src="https://cdn.jsdelivr.net/pyodide/v0.24.1/full/pyodide.js"></script>
    <title>DEVOIR | {{$devoir->jeton}} | CONSOLE</title>
</head>
<body class="no-mathjax">

    @include('inc-nav')

	<div class="container">
		<div class="row pt-3">

            <div class="col-md-2 text-right pb-5">
				@if(Auth::check())
				    <a class="btn btn-light btn-sm" href="/console/devoirs" role="button"><i class="fas fa-arrow-left"></i></a>
				@else
				    <a class="btn btn-light btn-sm" href="/" role="button"><i class="fas fa-arrow-left"></i></a>
				    <div class="mt-3 small text-monospace text-muted">Vous pouvez <a href="/creer-un-compte" target="_blank">créer un compte</a> pour regrouper, gérer et partager vos sujets.</div>
				@endif
			</div>

			<div class="col-md-10 pl-4 pr-4">
                <div class="row">
                    <div class="col-md-12 text-monospace p-2 pl-3 pr-3 mb-3" style="border:dashed 2px #e3342f;border-radius:8px;">
                 
                        @if(isset($_GET['i']))
                            <div class="text-monospace text-danger text-center font-weight-bold m-2">SAUVEGARDEZ LES INFORMATIONS CI-DESSOUS AVANT DE QUITTER CETTE PAGE</div>
                        @endif

                        <table class="table table-borderless text-monospace m-0" style="border-spacing:4px;border-collapse:separate;">
                            <tr>
                                @if($devoir->user_id == 0 OR !Auth::check()) <td class="text-center font-weight-bold p-0 small" style="width:33%">lien secret</td> @endif
                                <td class="text-center font-weight-bold p-0 small" style="width:33%">code secret</td>
                                <td class="text-center font-weight-bold p-0 small" style="width:33%">lien élèves</td>
                            </tr>
                            <tr>
                                @if ($devoir->user_id == 0 OR !Auth::check()) <td class="text-center text-break align-middle rounded bg-danger text-white"><a href="/devoir-console/{{strtoupper($devoir->jeton_secret)}}" target="_blank" class="text-white font-weight-bold">www.codepuzzle.io/devoir-console/{{strtoupper($devoir->jeton_secret)}}</a></td> @endif
                                <td class="text-center border border-danger text-break align-middle rounded text-danger font-weight-bold">
                                    {{$devoir->mot_secret}}
                                </td>
                                <td class="text-center text-white text-break align-middle rounded bg-secondary">
                                    <a href="/E{{strtoupper($devoir->jeton)}}" target="_blank" class="text-white">www.codepuzzle.io/E{{strtoupper($devoir->jeton)}}</a>
                                </td>
                            </tr>
                            <tr>
                                @if ($devoir->user_id == 0 OR !Auth::check()) <td class="small text-muted p-0"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce lien</span><br />Il permet d'accéder à la console du devoir (sujet, lien pour les élèves, correction...).</td> @endif
                                <td class="small text-muted p-0"><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Ne pas partager ce code</span><br />Il permet de déverrouiller la copie d'un élève.</td>
                                <td class="small text-muted p-0">Lien à fournir aux élèves.<!--<br />QR code: <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/E' . strtoupper($devoir->jeton))}}&amp;size=200x200" style="width:50px" alt="www.codepuzzle.io/E{{strtoupper($devoir->jeton)}}" data-toggle="tooltip" data-placement="bottom" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" />--></td>
                            </tr>
                        </table>
                        
                    </div>
                </div>


                <!-- MODIFIER - SUPERVISER -->
                <div class="row mt-3 mb-3">
                    <div class="col-md-12 text-center">
                        <a class="btn btn-dark btn-sm" href="/devoir-modifier/{{ Crypt::encryptString($devoir->id) }}" role="button"><i class="fa-solid fa-pen mr-2"></i> modifier</a>
                        <a class="btn btn-dark btn-sm ml-3 mr-3" href="/devoir-supervision/{{ Crypt::encryptString($devoir->id) }}" role="button"><i class="fa-solid fa-eye mr-2"></i> superviser</a>
                    </div>
                </div>
                <!-- /MODIFIER - SUPERVISER -->

                <div class="mt-2 mb-1 text-monospace font-weight-bold">{{strtoupper(__($devoir->titre_enseignant))}}</div>
                @if ($devoir->consignes_eleve != '')
                    <div style="padding:20px;border:solid 1px #DBE0E5;border-radius:4px;background-color:#f3f5f7;border-radius:4px;">
                        {{$devoir->consignes_eleve}}
                    </div>
                @endif

                <div class="mt-3 mb-1 text-monospace">{{strtoupper(__('copies'))}}</div>
                @if ($copies->isNotEmpty())
                    <div class="row mb-5">
                        <div class="col-md-12">

                            <ul class="list-group">

                                @foreach($copies as $copie)

                                    <?php
                                    $secondes = floor($copie->chrono/1000);
                                    $heures = gmdate("H", $secondes);
                                    $minutes = gmdate("i", $secondes);
                                    $secondes = gmdate("s", $secondes);
                                    $chrono = "{$heures}h {$minutes}m {$secondes}s";


                                    $commentaires ='';
                                    $note = '';
                                    if ($copie->correction_enseignant != null) {
                                        foreach (json_decode($copie->correction_enseignant)->cells AS $notebook_cell) {
                                            if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'commentaires') $commentaires = $notebook_cell->source[0];
                                            if (isset($notebook_cell->metadata->correction_name) AND $notebook_cell->metadata->correction_name == 'note') $note = $notebook_cell->source[0];
                                        }
                                    }
                                    ?>

                                    <li class="list-group-item">

                                        @if($copie->revised == 1)
                                            <a class="btn btn-sm btn-success" href="/devoir-corriger/{{ Crypt::encryptString($devoir->id) }}/{{ $loop->index }}" role="button" style="width:40px;" target="_blank"><i class="fas fa-check"></i></a>
                                        @else
                                            <a class="btn btn-sm btn-light" href="/devoir-corriger/{{ Crypt::encryptString($devoir->id) }}/{{ $loop->index }}" role="button" style="width:40px;" target="_blank"><i class="fas fa-question"></i></a>
                                        @endif                               
                                        
                                        <span class="pl-2 text-monospace">{{$copie->pseudo}}</span>

                                        <div style="float:right;right:0px">

                                            @if ($note != '')
                                                <span class=" text-muted text-monospace pr-3">{{$note}}</span>
                                            @endif
                                            @if ($commentaires != '')
                                                <span class=" text-muted" data-container="body" data-html="true" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ nl2br($commentaires) }}" style="cursor:help"><i class="far fa-comment-alt"></i></span>
                                            @endif

                                            <span class="small text-muted pl-3 pr-3"><i class="mr-1 fa-solid fa-stopwatch"></i>{{$chrono}}</span>

                                            @if($copie->submitted == 1)
                                                <span class="text-white mr-1" style="font-size:70%;background-color:#6c757d;padding:3px 8px 2px 8px;border-radius:3px;vertical-align:2px;">rendu</span>
                                            @endif

                                            <a tabindex='0' role="button" class="pl-2" style="cursor:pointer;outline:none;color:#e2e6ea;font-size:95%" data-toggle="collapse" data-target="#collapseSupprimer-{{$loop->iteration}}" aria-expanded="false" aria-controls="collapseSupprimer-{{$loop->iteration}}"><i class='fas fa-trash fa-sm'></i></a>
                                            <div class="collapse text-right" id="collapseSupprimer-{{$loop->iteration}}">
                                                <div class="mt-3">
                                                    <a href='/devoir-eleve-supprimer/{{ Crypt::encryptString($copie->id) }}' class='btn btn-danger btn-sm text-white' role='button'>{{__('supprimer')}}</a>                                            
                                                    <button type="button" class="btn btn-light btn-sm ml-1" data-toggle="collapse" data-target="#collapseSupprimer-{{$loop->iteration}}">annuler</button>
                                                </div>
                                            </div>
                                        </div>

                                    </li>

                                @endforeach

                            </ul>

                        </div>
                    </div>
                @endif

                @if ($copies->where('revised', 1)->count() != 0)
                    <div class="mt-2 mb-1 text-monospace">{{strtoupper(__("comptes-rendus"))}}</div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group text-monospace">

                                @foreach($copies as $copie)
                                    @if($copie->revised == 1)
                                        <li class="list-group-item">                           
                                            {{$copie->pseudo}}
                                            <div style="float:right;right:0px">
                                                <a href="/C{{ strtoupper($copie->jeton_copie) }}" target="_blank">www.copdepuzzle.io/C{{strtoupper($copie->jeton_copie)}}</a>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                            <div class="mt-1">
                                <a class="btn btn-dark btn-sm" href="/devoir-imprimer/{{ Crypt::encryptString($devoir->id) }}" role="button"><i class="fa-solid fa-print mr-2"></i> imprimer les comptes-rendus</a>
                                <span class="text-muted small">pour les annoter à la main si nécessaire et les distribuer aux élèves</span>
                            </div>
                        </div>                        
                    </div>
                @endif

                <div class="pt-5 mb-1 text-monospace">{{strtoupper(__("sujet"))}}</div>
                <!-- SUJET -->
                @include('sujets/inc-sujet-afficher')
                <!-- SUJET -->

            </div>
        </div>
	</div><!-- /container -->

    <br />

    @include('inc-bottom-js')
    <!-- @include('devoirs/devoir-console-js') -->
    @include('sujets/inc-sujet-afficher-js')

</body>
</html>
