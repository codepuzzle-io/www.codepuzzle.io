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
	$EP24 = [
        'EP24-01.1' => 'DH83G',
        'EP24-01.2' => 'DF8PT',
        'EP24-02.1' => 'DSXPQ',
        'EP24-02.2' => 'DYZWR',
        'EP24-03.1' => 'D9ANW',
        'EP24-03.2' => 'D34QT',
        'EP24-04.1' => 'DY5P8',
        'EP24-04.2' => 'DYASQ',
        'EP24-05.1' => 'DGZME',
        'EP24-05.2' => 'DZU97',
        'EP24-06.1' => 'DPMQ9',
        'EP24-06.2' => 'DRD28',
        'EP24-07.1' => 'D9Y2P',
        'EP24-07.2' => 'DPJYV',
        'EP24-08.1' => 'D4KLJ',
        'EP24-08.2' => 'DZWGA',
        'EP24-09.1' => 'D5K72',
        'EP24-09.2' => 'DQH64',
        'EP24-10.1' => 'DMP4J',
        'EP24-10.2' => 'D2JR6',
        'EP24-11.1' => 'D32G9',
        'EP24-11.2' => 'D8QDH',
        'EP24-12.1' => 'DBS54',

        'EP24-13.1' => 'DYWZS',
        'EP24-13.2' => 'DG8VH',
        'EP24-14.1' => 'DJ3NG',
        'EP24-14.2' => 'DU79Y',
        'EP24-15.1' => 'DUHKS',
        'EP24-15.2' => 'DU9MH',
        'EP24-16.1' => 'DVWK8',
        'EP24-16.2' => 'DRYCA',
        'EP24-17.1' => 'DCPZG',
        'EP24-17.2' => 'DSJN8',
        'EP24-18.1' => 'DYM9Z',
        'EP24-18.2' => 'DVJB2',
        'EP24-19.1' => 'D62YJ',
        'EP24-19.2' => 'D9TJV',
        'EP24-20.1' => 'DY96J',
        'EP24-20.2' => 'DNGKQ',
        'EP24-21.1' => 'D69FY',		
        'EP24-21.2' => 'DKTWL',		
        'EP24-22.1' => 'DAWS9',		
        'EP24-22.2' => 'DUERW',		
        'EP24-23.1' => 'D9KSW',		
        'EP24-23.2' => 'DJARN',		
        'EP24-24.1' => 'DSFEK',		
        'EP24-24.2' => 'D29HK',		
        'EP24-25.1' => 'DXECS',		
        'EP24-25.2' => 'DV3WE',		
        'EP24-26.1' => 'DUZ26',		
		
        'EP24-27.1' => 'DJBN4',		
        'EP24-27.2' => 'DDHQ3',		
        'EP24-28.1' => 'D2AFM',		
        'EP24-28.2' => 'D7ZPR',		
        'EP24-29.1' => 'D2MKJ',		
        'EP24-29.2' => 'DJV2Q',		
        'EP24-30.1' => 'DS56N',		
        'EP24-30.2' => 'D3SZJ',	
        'EP24-31.1' => 'DNXS5',		
        'EP24-31.2' => 'DBTF9',		
        'EP24-32.1' => 'D3G6W',		
        'EP24-32.2' => 'D98ZL',		
        'EP24-33.1' => 'DRBJV',		
        'EP24-33.2' => 'DKM6R',		
        'EP24-34.1' => 'DXATB',		
        'EP24-34.2' => 'DN9TZ',		
        'EP24-35.1' => 'DXESM',		
        'EP24-35.2' => 'D7GRL',		
        'EP24-36.1' => 'DE2WF',		
        'EP24-36.2' => 'D3BZ4',		
        'EP24-37.1' => 'DFAKE',		
        'EP24-37.2' => 'DDQKZ',		
        'EP24-38.1' => 'DPEJU',		
        'EP24-38.2' => 'D5ACH',		
        'EP24-39.1' => 'D8ARM',		
        'EP24-39.2' => 'DZWL7',		
        'EP24-40.1' => 'DEKUR',		
        'EP24-40.2' => 'DZQD4',		
        'EP24-41.1' => 'DWXMF',		
        'EP24-41.2' => 'DBTP6',		
        'EP24-42.1' => 'DBGMF',		
        'EP24-42.2' => 'DC57D',		
        'EP24-43.1' => 'D5ZQ9',		
        'EP24-43.2' => 'DJQ8X',		
        'EP24-44.1' => 'DN7YB',		
        'EP24-44.2' => 'DYTNH',		
        'EP24-45.1' => 'DBE67',		
        'EP24-45.2' => 'DELCR',		
        'EP24-46.1' => 'D8XZK',		
        'EP24-46.2' => 'DPS72',		
        'EP24-47.1' => 'DRHZ4',		
        'EP24-47.2' => 'D6M8Z',		
        'EP24-48.1' => 'D7K5T',		
        'EP24-48.2' => 'DC2EL',		
	];
	
    $EP23 = [
        'EP23-01.1' => 'DCDG3',
        'EP23-01.2' => 'DBZF2',
        'EP23-02.1' => 'D83WJ',
        'EP23-02.2' => 'DHBUS',
        'EP23-03.1' => 'DXC9G',
        'EP23-04.1' => 'DW5F2',
        'EP23-04.2' => 'DDYE7',
        'EP23-05.1' => 'D7WXY',
        'EP23-05.2' => 'D2LKW',
        'EP23-06.1' => 'DL4SC',
        'EP23-06.2' => 'DT3GD',
        'EP23-07.1' => 'DJMQH',
        'EP23-07.2' => 'D5QNZ',
        'EP23-08.1' => 'DQMSK',
        'EP23-08.2' => 'DPVN9',
        'EP23-09.1' => 'DEHSD',
        'EP23-09.2' => 'DMTC9',
        'EP23-10.1' => 'DL92R',
        'EP23-10.2' => 'DAWZM',
        'EP23-11.1' => 'DSPBU',
        'EP23-11.2' => 'DRG7N',
        'EP23-12.2' => 'DJ9FX',
        'EP23-13.1' => 'DXKF8',
        'EP23-13.2' => 'DRG6S',
        'EP23-14.1' => 'DUTZL',
        'EP23-14.2' => 'DWPEL',
        'EP23-15.1' => 'DCHQV',
        'EP23-15.2' => 'DF6TL',
        'EP23-16.1' => 'DBQ7X',
        'EP23-16.2' => 'D73AJ',
        'EP23-17.1' => 'DTSW2',
        'EP23-17.2' => 'DW87B',
        'EP23-18.1' => 'DXSR8',
        'EP23-18.2' => 'DPZ6V',
        'EP23-19.1' => 'DWMX2',
        'EP23-19.2' => 'DDXSV',
        'EP23-20.1' => 'DLMC5',
        'EP23-21.1' => 'DA5GP',
        'EP23-21.2' => 'DMN8Q',
        'EP23-22.1' => 'DBAJY',
        'EP23-22.2' => 'DG8ZR',
        'EP23-23.1' => 'DWPRZ',
        'EP23-23.2' => 'D9HM6',
        'EP23-24.1' => 'DNRPY',
        'EP23-24.2' => 'DK764',
        'EP23-25.1' => 'DAB8Z',
        'EP23-26.1' => 'D35DN',
        'EP23-27.1' => 'DV6CM',
        'EP23-27.2' => 'DWG82',
        'EP23-28.1' => 'D9VE2',
        'EP23-28.2' => 'DDBSL',
        'EP23-29.2' => 'DBA79',
        'EP23-30.1' => 'DAZW3',
        'EP23-30.2' => 'D7HNC',
        'EP23-31.1' => 'DHBUV',
        'EP23-31.2' => 'DVYHA',
        'EP23-32.1' => 'DSJZB',
        'EP23-33.1' => 'D2LMB',
        'EP23-33.2' => 'DVW5A',
        'EP23-34.1' => 'DALHQ',
        'EP23-34.2' => 'D8SZH',
        'EP23-35.1' => 'DQN5S',
        'EP23-36.1' => 'DSU4B',
        'EP23-37.1' => 'DYTAM',
        'EP23-37.2' => 'DB5DS',
        'EP23-38.1' => 'DBW2D',
        'EP23-38.2' => 'DZN7L',
        'EP23-39.1' => 'D4VAY',
        'EP23-39.2' => 'DX35D',
        'EP23-40.1' => 'D8PNJ',
        'EP23-41.1' => 'DQKV9',
        'EP23-41.2' => 'DEZ64',
        'EP23-42.1' => 'DVGJ5',
        'EP23-43.1' => 'DGEXL',
        'EP23-43.2' => 'DQXNP',
        'EP23-44.1' => 'DMZQY',
        'EP23-44.2' => 'DECSJ',
        'EP23-45.1' => 'DZLGR',
        'EP23-45.2' => 'DGXMY',
        'EP23-45.2' => 'DGXMY',
    ];

    $autres = [
        //'AOC2023 Day1Part1' => 'DEB7Q',
        //'AOC2023 Day1Part2' => 'DMN79',
    ];

    $defis = array_merge($EP24, $EP23, $autres);

    // JETONS
	// EP24
    $jetons_EP24 = "";
    foreach ($EP24 AS $titre => $jeton) {
        $jetons_EP24 .= $jeton . ',';
    }
    $jetons_EP24 = rtrim($jetons_EP24, ',');	
	
	// EP23
    $jetons_EP23 = "";
    foreach ($EP23 AS $titre => $jeton) {
        $jetons_EP23 .= $jeton . ',';
    }
    $jetons_EP23 = rtrim($jetons_EP23, ',');
	
    $lang ='/';
    ?>

    <div class="container mt-4 mb-5">

		<div class="row">

			<div class="col-md-2">
                <div class="text-right"><a class="btn btn-light btn-sm mb-4" href="{{$lang}}" role="button"><i class="fas fa-arrow-left"></i></a></div>
                <a class="btn btn-success btn-sm mb-4 text-monospace" href="{{route('defi-creer-get')}}" role="button" style="width:100%;">{{__('créer un défi')}}</a>
            </div>

			<div class="col-md-10 pl-3 pr-3">

                <h1>Banque de défis</h1>

                <div class="text-monospace mb-4"style="border:solid 1px silver;padding:10px;border-radius:4px;">
                    <b>ÉPREUVE PRATIQUE 2024</b>
                    <div class="mb-1">
                    Liste des sujets de l'épreuve pratique de 2024 à intégrer dans une <a href="/classe-creer" target="_blank">classe</a> pour proposer des entraînements aux élèves et <a href="/#classe" target="_blank">suivre leur progression</a>. Cocher ou décocher  les sujets afin de générer une liste à ajouter dans une classe. Si un sujet est déjà présent dans la classe, il n'est pas ajouté une deuxième fois.
                    </div>
                    <div class="mb-2 text-danger">Remarque: il manque le 12.2 et le 26.2</div>
                    <div class="small">
                        @foreach($EP24 as $titre => $jeton)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="{{ $titre }}" data-ep24="{{ $jeton }}" checked>
                                <label class="form-check-label" for="{{ $titre }}">{{ substr($titre, -4) }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="ml-1 mt-3">Codes sélectionnés:</div>
                    <textarea id="codes_ep24" class="form-control mb-2" rows="6">{{$jetons_EP24}}</textarea>

					@foreach($EP24 as $titre => $jeton)
						<div class="small pl-2 pr-3" style="float:left;">{{$titre}}: <a href="https://www.codepuzzle.io/{{$jeton}}" target="_blank">www.codepuzzle.io/{{$jeton}}</a></div>
					@endforeach
					<br  style="clear:both;"/>
					
                </div>

                <div class="text-monospace mb-4"style="border:solid 1px silver;padding:10px;border-radius:4px;">
                    <b>ÉPREUVE PRATIQUE 2023</b>
                    <div class="mb-1">
                    Liste des sujets de l'épreuve pratique de 2023 à intégrer dans une <a href="/classe-creer" target="_blank">classe</a> pour proposer des entraînements aux élèves et <a href="/#classe" target="_blank">suivre leur progression</a>. Cocher ou décocher  les sujets afin de générer une liste à ajouter dans une classe. Si un sujet est déjà présent dans la classe, il n'est pas ajouté une deuxième fois.
                    </div>
                    <div class="mb-2 text-danger">Remarque: tous les sujets ne sont pas encore présents</div>
                    <div class="small">
                        @foreach($EP23 as $titre => $jeton)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="{{ $titre }}" data-ep23="{{ $jeton }}" checked>
                                <label class="form-check-label" for="{{ $titre }}">{{ substr($titre, -4) }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="ml-1 mt-3">Codes sélectionnés:</div>
                    <textarea id="codes_ep23" class="form-control" rows="6">{{$jetons_EP23}}</textarea>
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
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-ep24]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateCodesEP24();
                });
            });
        });

        function updateCodesEP24() {
            var selectedCodes = [];
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-ep24]:checked');
            checkboxes.forEach(function(checkbox) {
                selectedCodes.push(checkbox.getAttribute('data-ep24'));
            });
            document.getElementById('codes_ep24').textContent = selectedCodes.join(',');
        }
		
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-ep23]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateCodesEP23();
                });
            });
        });

        function updateCodesEP23() {
            var selectedCodes = [];
            var checkboxes = document.querySelectorAll('input[type="checkbox"][data-ep23]:checked');
            checkboxes.forEach(function(checkbox) {
                selectedCodes.push(checkbox.getAttribute('data-ep23'));
            });
            document.getElementById('codes_ep23').textContent = selectedCodes.join(',');
        }
    </script>

</body>
</html>
