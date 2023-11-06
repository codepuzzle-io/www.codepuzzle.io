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
    $jetons = [
        'DT4VZ6',
        'DBEAKN',
        'DRLXQ',
        'DQHXM',
        'DSZ5G',
        'D8T5D',
        'DBXWT',
        'DQ4YS',
        'DY2EZ',
        'DCDG3', // [EP23] - 1.1
        'D83WJ', // [EP23] - 2.1
        'DXC9G', // [EP23] - 3.1
        'DW5F2', // [EP23] - 4.1
        'DL4SC', // [EP23] - 6.1
        'DJMQH', // [EP23] - 7.1
        'DQMSK', // [EP23] - 8.1
        'DEHSD', // [EP23] - 9.1
    ];
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

                <div class="card-columns mb-5">
                    @foreach($jetons as $jeton)
                        <?php
                            $defi = App\Models\Defi::where('jeton', substr($jeton, 1))->first();
                            $url_twitter = 'https://twitter.com/intent/tweet?text='.rawurlencode("🧩 Défi ".$jeton."\n\n➡️ https://www.codepuzzle.io/".$jeton."\n\n#Python #NSI #SNT");
                            $url_mastodon = '/share?text='.rawurlencode("🧩 Défi ".$jeton."\n\n➡️ https://www.codepuzzle.io/".$jeton."\n\n#Python #NSI #SNT");
                        ?>
                        @if ($defi)
                            
                                <div class="card" style="padding:20px 20px 0px 20px;">
                                    <div class="card-body p-0">
                                    
                                        <div class="text-monospace text-muted small font-weight-bold">
                                            <div style="float:right;">
                                                <a href='#'
                                                    class="mastodon_button"
                                                    data-toggle="popover"
                                                    data-container="body"
                                                    data-placement="left"
                                                    data-content="
                                                        <div class='form-group text-monospace'>
                                                            <label for='intance'>Instance Mastodon</label>
                                                            <input id='instance_{{$loop->iteration}}' type='text' class='form-control form-control-sm' placeholder='mastodon.social'>
                                                            <input id='url_{{$loop->iteration}}' type='hidden' class='form-control form-control-sm' value='{{$url_mastodon}}'>
                                                        </div>
                                                        <button class='btn btn-secondary btn-sm' type='button' onclick='mastodon({{$loop->iteration}})'><i class='fas fa-paper-plane'></i></button>
                                                        ">
                                                    <i class="fa-brands fa-mastodon"></i>
                                                </a>
                                                <a href='{{$url_twitter}}' target="_blank" rel='noopener noreferrer'><i class="fa-brands fa-square-twitter fa-lg"></i></a>
                                            </div> 
                                            {{$defi->titre_enseignant}}
                                        </div>

                                        <div class="text-monospace text-muted small consignes mathjax text-justify">
                                            <?php
                                            $Parsedown = new Parsedown();
                                            echo $Parsedown->text($defi->consignes_eleve);
                                            ?>
                                        </div>

                                        <div class="text-monospace  text-muted pt-3 pb-2">
                                            <i class="fas fa-share-alt ml-1 mr-1 align-middle"></i> <a href="/{{ strtoupper($jeton) }}" target="_blank">www.codepuzzle.io/{{ strtoupper($jeton) }}</a>
                                        </div>

                                    </div>

                                    <div class="card-footer">

                                        <div class="text-center mt-2 mb-2">
                                            <a class='text-muted' data-toggle="collapse" href="#collapse-{{$loop->iteration}}" role='button' aria-expanded="false" aria-controls="collapse-{{$loop->iteration}}" ><i class="fas fa-bars" style="margin-top:0px;" data-toggle="tooltip" data-placement="top" title="{{__('déplier plier')}}"></i></a>
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
                                            <div class="text-monospace text-muted small pb-3">
                                                <i class="fas fa-share-alt ml-1 mr-1"></i> QR code : <img src="https://api.qrserver.com/v1/create-qr-code/?data={{urlencode('https://www.codepuzzle.io/' . strtoupper($jeton))}}&amp;size=100x100" style="width:100px" alt="wwww.codepuzzle.io/{{strtoupper($jeton)}}" data-toggle="tooltip" data-placement="right" title="{{__('clic droit + Enregistrer l image sous... pour sauvegarder l image')}}" />
                                            </div>                                            
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
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js">
    </script>    

</body>
</html>