<div style="padding:28px 15px 0 15px;">

    <div id="correction">
        @foreach($copie_cells AS $copie_cell)

            @if ($copie_cell->cell_type == 'markdown')
                <div id="markdown_{{ $loop->iteration }}" class="cellule_content cellule_marked mb-2">
                    <div id="markdown_content_{{ $loop->iteration }}">{{$copie_cell->source[0]}}</div>
                </div>
            @endif

            @if ($copie_cell->cell_type == 'code') 
                <div id="code_{{ $loop->iteration }}" > 
                    @if ($sujet->type == 'exo')      
                        @if(count((array) $sujet_json->code) > 1)
                            <div id="programme_titre_{{ $loop->iteration }}" class="font-weight-bold text-monospace">PROGRAMME {{ $loop->iteration }}</div>
                        @else
                            <div id="programme_titre_{{ $loop->iteration }}" class="font-weight-bold text-monospace">PROGRAMME</div>
                        @endif           
                    @endif 
                    <div class="cellule_content mb-2 p-3">   
                        <div class="text-monospace small text-muted"><kbd>1</kbd> Code élève <i class="text-muted small">en lecture seule</i></div>
                        <div id="code_editor_eleve_{{ $loop->iteration }}" class="mt-1 mb-2 code-editor"></div>
                        <div class="text-monospace small text-muted"><kbd>2</kbd> Code enseignant</div>
                        <div id="code_editor_enseignant_{{ $loop->iteration }}" class="mt-1 mb-2 code-editor"></div>
                        <div class="row no-gutters">
                            <div class="col-auto mr-2">
                                <div class="form-check d-block text-right pl-0">
                                    <span class="text-monospace small text-muted"><kbd>1</kbd></span>
                                    <input id="code_option_1_devoir-{{$loop->iteration}}" name="code_option_devoir-{{$loop->iteration}}" class="ml-1 align-middle" style="display:inline;cursor:pointer" type="radio" />
                                </div>
                                <div class="form-check d-block text-right pl-0">
                                    <span class="text-monospace small text-muted"><kbd>1</kbd><span class="ml-1 mr-1">+</span><kbd>2</kbd></span>
                                    <input id="code_option_2_devoir-{{$loop->iteration}}" name="code_option_devoir-{{$loop->iteration}}" class="ml-1 align-middle" style="display:inline;cursor:pointer" type="radio" checked />
                                </div>
                                <div class="form-check d-block text-right pl-0">
                                    <span class="text-monospace small text-muted"><kbd>2</kbd></span>
                                    <input id="code_option_3_devoir-{{$loop->iteration}}" name="code_option_devoir-{{$loop->iteration}}" class="ml-1 align-middle" style="display:inline;cursor:pointer" type="radio" />
                                </div>
                                <div class="mt-2 text-right">
                                    <button id="run_{{ $loop->iteration }}" data-pyodide="run" onclick="run('{{ $loop->iteration }}')" style="width:40px;" type="button" class="btn btn-primary text-center mb-1 btn-sm"><i class="fas fa-circle-notch fa-spin"></i></button>
                                </div>
                                <div id="restart_{{ $loop->iteration }}" style="display:none;">
                                    <button style="width:40px;" type="button" data-pyodide="restart" onclick="restart('{{ $loop->iteration }}')"  class="btn btn-dark btn-sm" style="padding-top:6px;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Si le bouton d\'arrêt ne permet pas d\'interrompre  l\'exécution du code, cliquer ici. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                                </div>
                            </div>
                            <div id="console_{{ $loop->iteration }}" class="col">
                                <div class="text-dark small text-monospace" style="float:right;padding:5px 12px 0px 0px">console</div>
                                <div id="output_{{ $loop->iteration }}" class="text-monospace p-3 text-white bg-secondary small" style="white-space: pre-wrap;border-radius:4px;min-height:100px;height:100%;">Prêt!</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
        @endforeach
    </div>

    <br />
    <br />

</div>
