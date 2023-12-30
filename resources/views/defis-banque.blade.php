<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $description = 'Banque de défis';
        $description_og = 'Banque de défis';
    @endphp
    @include('inc-meta')
    <title>Banque de défis</title>
</head>
<body class="no-mathjax">

    @include('inc-nav')

    <?php
    $EP23 = [
        '01.1' => 'DCDG3',
        '01.2' => 'DBZF2',
        '02.1' => 'D83WJ',
        '02.2' => 'DHBUS',
        '03.1' => 'DXC9G',
        '04.1' => 'DW5F2',
        '05.1' => 'D7WXY',
        '05.2' => 'D2LKW',
        '06.1' => 'DL4SC',
        '06.2' => 'DT3GD',
        '07.1' => 'DJMQH',
        '07.2' => 'D5QNZ',
        '08.1' => 'DQMSK',
        '08.2' => 'DPVN9',
        '09.1' => 'DEHSD',
        '09.2' => 'DMTC9',
        '10.1' => 'DL92R',
        '10.2' => 'DAWZM',
        '11.1' => 'DSPBU',
        '11.2' => 'DRG7N',
        '12.2' => 'DJ9FX',
        '13.1' => 'DXKF8',
        '13.2' => 'DRG6S',
        '14.1' => 'DUTZL',
        '14.2' => 'DWPEL',
        '15.1' => 'DCHQV',
        '15.2' => 'DF6TL',
        '16.1' => 'DBQ7X',
        '17.1' => 'DTSW2',
        '18.1' => 'DXSR8',
        '18.2' => 'DPZ6V',
        '19.1' => 'DWMX2',
        '19.2' => 'DDXSV',
        '20.1' => 'DLMC5',
        '21.1' => 'DA5GP',
        '22.1' => 'DBAJY',
        '22.2' => 'DG8ZR',
        '23.1' => 'DWPRZ',
        '23.2' => 'D9HM6',
        '24.2' => 'DK764',
        '25.1' => 'DAB8Z',
        '27.1' => 'DV6CM',
        '27.2' => 'DWG82',
        '28.1' => 'D9VE2',
        '28.2' => 'DDBSL',
        '30.1' => 'DAZW3',
        '31.1' => 'DHBUV',
        '32.1' => 'DSJZB',
        '33.2' => 'DVW5A',
        '34.1' => 'DALHQ',
        '34.2' => 'D8SZH',
        '36.1' => 'DSU4B',
        '37.1' => 'DYTAM',
        '37.2' => 'DB5DS',
        '38.1' => 'DBW2D',
        '38.2' => 'DZN7L',
        '39.1' => 'D4VAY',
        '39.2' => 'DX35D',
        '40.1' => 'D8PNJ',
        '41.1' => 'DQKV9',
        '41.2' => 'DEZ64',
        '42.1' => 'DVGJ5',
        '43.1' => 'DGEXL',
        '43.2' => 'DQXNP',
        '44.1' => 'DMZQY',
        '44.2' => 'DECSJ',
        '45.1' => 'DZLGR',
    ];

    $autres = [
        //'AOC2023 Day1Part1' => 'DEB7Q',
        //'AOC2023 Day1Part2' => 'DMN79',
    ];

    $defis = array_merge($EP23, $autres);

    // JETONS
    $jetons_EP23 = "";
    foreach ($EP23 AS $titre => $jeton) {
        $jetons_EP23 .= $jeton . ',';
    }
    $jetons_EP23 = rtrim($jetons_EP23, ',');

    $lang ='/';
    ?>

	<div class="container mt-5 mb-5">

		<div class="row pt-3">

			<div class="col-md-2">

                <div class="text-right"><a class="btn btn-light btn-sm mb-4" href="{{$lang}}" role="button"><i class="fas fa-arrow-left"></i></a></div>

                <a class="btn btn-success btn-sm mb-4 text-monospace" href="{{route('defi-creer-get')}}" role="button" style="width:100%;">{{__('créer un nouveau défi')}}</a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/discussions" target="_blank" role="button" class="mt-2 btn btn-light btn-sm text-left text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-comment-alt" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('discussions')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('annonces')}}</span>
                </a>

                <a href="https://github.com/codepuzzle-io/www.codepuzzle.io/issues/new/choose" target="_blank" role="button"  class="mt-1 btn btn-light text-left btn-sm text-muted" style="width:100%;opacity:0.8;">
                	<span style="font-size:80%"><i class="fas fa-bug" style="float:left;margin:4px 8px 5px 0px;"></i> {{__('signalement de bogue')}} <span style="opacity:0.6;font-size:90%;">&</span> {{__('questions techniques')}}</span>
                </a>

                <div class="mt-3 text-muted text-monospace pl-1 mb-5" style="font-size:70%;opacity:0.8;">
                	<span><i class="fa fa-envelope"></i> contact@codepuzzle.io</span>
                </div>

            </div>

			<div class="col-md-10 pl-3 pr-3">

                <h1>Banque de défis</h1>

                <div class="text-monospace mb-4"style="border:solid 1px silver;padding:10px;border-radius:4px;">
                    <b>ÉPREUVE PRATIQUE 2023</b>
                    <div class="mb-1">
                    Liste des sujets de l'épreuve pratique de 2023 à intégrer dans une <a href="/classe-creer" target="_blank">classe</a> pour proposer des entraînements aux élèves et <a href="/#classe" target="_blank">suivre leur progression</a>. Cocher ou décocher  les sujets afin de générer une liste à ajouter dans une classe. Si un sujet est déjà présent dans la classe, il n'est pas ajouté une deuxième fois.
                    </div>
                    <div class="mb-2 text-danger">Remarque: tous les sujets ne sont pas encore présents</div>
                    <div class="small">
                        @foreach($EP23 as $titre => $jeton)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="{{ $titre }}" data-code="{{ $jeton }}" checked>
                                <label class="form-check-label" for="{{ $titre }}">{{ $titre }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="ml-1 mt-3">Codes sélectionnés:</div>
                    <textarea id="codes" class="form-control" rows="4">{{$jetons_EP23}}</textarea>
                </div>

                <div class="card-columns mb-5">
                    @foreach($defis as $titre => $jeton)
                        <?php
                            $defi = App\Models\Defi::where('jeton', substr($jeton, 1))->first();
                            $url_twitter = 'https://twitter.com/intent/tweet?text='.rawurlencode("🧩 Défi ".$jeton."\n\n➡️ https://www.codepuzzle.io/".$jeton."\n\n#Python #NSI #SNT");
                            $url_mastodon = '/share?text='.rawurlencode("🧩 Défi ".$jeton."\n\n➡️ https://www.codepuzzle.io/".$jeton."\n\n#Python #NSI #SNT");
                        ?>
                        @if ($defi)
                            
                                <div class="card" style="padding:20px 20px 0px 20px;">
                                    <div class="card-body p-0">
                                    
                                        <div class="text-monospace text-muted small font-weight-bold mb-1">
                                            <div style="float:right;">
                                                <a href='#'
                                                    class="mastodon_button"
                                                    data-toggle="popover"
                                                    data-container="body"
                                                    data-placement="left"
                                                    data-content="
                                                        <div class='form-group text-monospace'>
                                                            <span>Instance Mastodon</span>
                                                            <input id='instance_{{$loop->iteration}}' type='text' name='instance' class='form-control form-control-sm' placeholder='mastodon.social 'autocomplete='on' />
                                                            <input id='url_{{$loop->iteration}}' type='hidden' class='form-control form-control-sm' value='{{$url_mastodon}}' />
                                                        </div>
                                                        <button class='btn btn-secondary btn-sm' type='button' onclick='mastodon({{$loop->iteration}})'><i class='fas fa-paper-plane'></i></button>
                                                        ">
                                                    <i class="fa-brands fa-mastodon fa-lg"></i>
                                                </a>
                                                <a href='{{$url_twitter}}' target="_blank" rel='noopener noreferrer'><i class="fa-brands fa-square-twitter fa-lg"></i></a>
                                            </div> 
                                            {{$defi->titre_enseignant}}
                                        </div>

                                        <div class="text-monospace text-muted">
                                            <i class="fas fa-share-alt ml-1 mr-1 align-middle"></i> <a href="/{{ strtoupper($jeton) }}" target="_blank">www.codepuzzle.io/{{ strtoupper($jeton) }}</a>
                                        </div>

                                        <div class="text-monospace text-muted consignes mathjax text-justify mt-2 small" style="height:136px;overflow-y: auto;border:solid 1px silver;border-radius:4px;padding:10px;">
                                            <?php
                                            $Parsedown = new Parsedown();
                                            echo $Parsedown->text($defi->consignes_eleve);
                                            ?>
                                        </div>

                                    </div>

                                    <div class="card-footer">

                                        <div class="mt-1 mb-2 text-center">
                                            <a class='text-muted' data-toggle="collapse" href="#collapse-{{$loop->iteration}}" role='button' aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}" ><i class="fas fa-bars ml-1 mr-1 align-middle" data-toggle="tooltip" data-placement="top" title="{{__('déplier plier')}}"></i>                                      
                                        
                                            </a>
                                        </div>

                                        <div class="collapse" id="collapse-{{$loop->iteration}}">

                                            <div class="text-monospace text-muted mb-3 small">
                                                <i class="fas fa-share-alt ml-1 mr-1"></i> {{__('Code à insérer dans un site web')}}
                                                <div class="mt-1" style="margin-left:22px;">
                                                    <input class="form-control form-control-sm" type="text" value='<iframe src="https://www.codepuzzle.io/I{{ strtoupper($jeton) }}" width="100%" height="600" frameborder="0"></iframe>' disabled readonly />
                                                </div>
                                                <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>{{__('Remarque : ajuster la valeur de "height" en fonction de la taille du défi')}}</p>
                                            </div>
                                            <div class="text-monospace text-muted mb-4 small">
                                                <i class="fas fa-share-alt ml-1 mr-1"></i> {{__('Code à insérer dans une cellule code d un notebook Jupyter')}}
                                                <div class="mt-1" style="margin-left:22px;">
<textarea class="form-control form-control-sm" rows="2" disabled readonly>from IPython.display import IFrame
IFrame('https://www.codepuzzle.io/I{{ strtoupper($jeton) }}', width='100%', height=600)</textarea>
                                                </div>
                                                <p class="text-monospace mt-1" style="margin-left:22px;font-size:90%";color:silver>{{__('Remarque : ajuster la valeur de "height" en fonction de la taille du défi')}}</p>
                                            </div>
                                            <!--
                                            <div class="text-monospace text-muted small pb-3">
                                                <i class="fas fa-share-alt ml-1 mr-1"></i> QR code : <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/' . strtoupper($jeton))}}&amp;size=100x100" style="width:100px" alt="wwww.codepuzzle.io/{{strtoupper($jeton)}}" data-toggle="tooltip" data-placement="right" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" />
                                            </div>    
                                            -->                                        
                                        </div> 
                                    

                                    </div>    
                                </div>    
                              
                        @endif        
                    @endforeach
                </div>

            </div>
        </div>
	</div><!-- /container -->

	@include('inc-bottom-js')

    <script>
        $(".mastodon_button").popover({
            html: true,
            sanitize: false
        });

        jQuery(function ($) {
            $("[data-toggle='popover']").popover({trigger: "click"}).click(function (event) {
                event.stopPropagation();

            }).on('inserted.bs.popover', function () {
                $(".popover").click(function (event) {
                event.stopPropagation();
                })
            })

            $(document).click(function () {
                $("[data-toggle='popover']").popover('hide')
            })
        })

        function mastodon(item) {
            url = 'https://'+document.getElementById('instance_'+item).value+document.getElementById('url_'+item).value;
            window.open(url, "_blank");
        }
    </script>

    <script>
        MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']]
        },
		options: {
			ignoreHtmlClass: "no-mathjax",
			processHtmlClass: "mathjax"
		},
        svg: {
            fontCache: 'global'
        }
        };
    </script>
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-code]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateCodes();
                });
            });
        });

        function updateCodes() {
            var selectedCodes = [];
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-code]:checked');
            checkboxes.forEach(function(checkbox) {
                selectedCodes.push(checkbox.getAttribute('data-code'));
            });
            document.getElementById('codes').textContent = selectedCodes.join(',');
        }
    </script>

</body>
</html>
