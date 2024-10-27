@if ($sujet->type == 'exo')

    {{-- ============== --}}
    {{-- ==== EXO ===== --}}
    {{-- ============== --}}

    @if (isset($page_sujet_copie))
        <div style="padding:60px 15px 0 15px;">
    @else
        <div style="padding:18px 15px 0 15px;">
    @endif

        @if (isset($page_sujet_copie))
        <div id="boutons" class="mb-4" style="position:absolute;top:0;right:25px;padding:10px 10px 10px 40px;width:100%;z-index:1000;background-color:#F8FAFC;">

            <div style="float:right">
                <div id="boutons">
                    <a onclick="download_copie_text(this)" class="btn btn-outline-secondary btn-sm text-monospace" role="button" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="télécharger la copie au format texte (.txt)"><i class="fas fa-file-download"></i> texte</a>
                    <a onclick="download_copie_ipynb(this)" class="btn btn-outline-secondary btn-sm text-monospace" role="button" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="télécharger la copie au format notebook (.ipynb)"><i class="fas fa-file-download"></i> notebook</a>
                    <button type="button" style="float:right;" class="ml-5 btn btn-sm btn-danger" onclick="delete_localstorage()" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="réinitialiser la copie"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>

        </div>
        @endif

        <div id="mainContainer">
            @foreach($sujet_json->code AS $code)
                @if(count((array) $sujet_json->code) > 1)
                    <div class="font-weight-bold text-monospace">PROGRAMME {{ $loop->iteration }}</div>
                @else
                    <div class="font-weight-bold text-monospace">PROGRAMME</div>
                @endif
                <div id="code_editor_{{ $loop->iteration }}" class="mb-2 code-editor"></div>
                <div class="row no-gutters mb-5">
                    <div class="col-auto mr-2">
                        <div>
                            <button id="run_{{ $loop->iteration }}" onclick="run('{{ $loop->iteration }}')" style="width:40px;" type="button" class="btn btn-primary text-center mb-1 btn-sm"><i class="fas fa-circle-notch fa-spin"></i></button>
                        </div>
                        <div id="restart_{{ $loop->iteration }}" style="display:none;">
                            <button style="width:40px;" type="button" onclick="restart('{{ $loop->iteration }}')"  class="btn btn-dark btn-sm" style="padding-top:6px;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                        </div>
                    </div>
                    <div id="console_{{ $loop->iteration }}" class="col">
                        <div class="text-dark small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
                        <div id="output_{{ $loop->iteration }}" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;">Prêt!</div>
                    </div>
                </div>
            @endforeach
        </div>

        <br />
        <br />

    </div>

    {{-- ============== --}}
    {{-- ==== /EXO ==== --}}
    {{-- ============== --}}

@endif

@if ($sujet->type == 'pdf')

    {{-- ============== --}}
    {{-- ==== PDF ===== --}}
    {{-- ============== --}}

    <div style="padding:60px 15px 0 15px;">

        <div class="mb-4" style="position:absolute;top:0;right:25px;padding:10px 10px 10px 40px;width:100%;z-index:1000;background-color:#F8FAFC;">

            <button type="button" class="btn btn-dark btn-sm text-monospace" onclick="ajouterDiv(null, 'bas', 'text')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 16" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#ffffff" stroke="#ffffff" stroke-width="0.7"></path></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#ffffff"></path></svg>
                texte
            </button>
            <button type="button" class="ml-2 btn btn-dark btn-sm text-monospace" onclick="ajouterDiv(null, 'bas', 'code')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><g xmlns="http://www.w3.org/2000/svg"><path d="M9.25 10.0693L7.375 10.0693L7.375 8.19434C7.375 7.98809 7.20625 7.81934 7 7.81934C6.79375 7.81934 6.625 7.98809 6.625 8.19434L6.625 10.0693L4.75 10.0693C4.54375 10.0693 4.375 10.2381 4.375 10.4443C4.375 10.6506 4.54375 10.8193 4.75 10.8193L6.625 10.8193L6.625 12.6943C6.625 12.9006 6.79375 13.0693 7 13.0693C7.20625 13.0693 7.375 12.9006 7.375 12.6943L7.375 10.8193L9.25 10.8193C9.45625 10.8193 9.625 10.6506 9.625 10.4443C9.625 10.2381 9.45625 10.0693 9.25 10.0693Z" fill="#ffffff" stroke="#ffffff" stroke-width="0.7"></path></g><path d="M2.5 5.5L2.5 3.5L11.5 3.5L11.5 5.5L2.5 5.5ZM2 7C1.44772 7 1 6.55228 1 6L1 3C1 2.44772 1.44772 2 2 2L12 2C12.5523 2 13 2.44772 13 3L13 6C13 6.55229 12.5523 7 12 7L2 7Z" fill="#ffffff"></path></svg>
                code
            </button>

            @if (isset($page_sujet_copie))
            <div style="float:right">
                <div id="boutons">
                    <a onclick="download_copie_text(this)" class="btn btn-outline-secondary btn-sm text-monospace" role="button" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="télécharger la copie au format texte (.txt)"><i class="fas fa-file-download"></i> texte</a>
                    <a onclick="download_copie_ipynb(this)" class="btn btn-outline-secondary btn-sm text-monospace" role="button" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="télécharger la copie au format notebook (.ipynb)"><i class="fas fa-file-download"></i> notebook</a>
                    <button type="button" style="float:right;" class="ml-5 btn btn-sm btn-danger" onclick="delete_localstorage()" data-container="#boutons" data-toggle="tooltip" data-placement="auto" title="réinitialiser la copie"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
            @endif

        </div>

        <div id="mainContainer"></div>

    </div>

    {{-- ============== --}}
    {{-- ==== /PDF ==== --}}
    {{-- ============== --}}

@endif