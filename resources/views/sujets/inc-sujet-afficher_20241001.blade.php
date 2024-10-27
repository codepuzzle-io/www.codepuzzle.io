<h2 id="sujet_titre" class="p-0 m-0 text-monospace pb-2">{{ $sujet->titre }}</h2>

@if ($sujet->type == 'exo')

    {{-- ============== --}}
    {{-- ==== EXO ===== --}}
    {{-- ============== --}}

    <!-- ÉNONCÉ --> 
    @if (isset($page_sujet_console) or isset($page_devoir_console)) <div class="mt-4 mb-1 text-monospace">{{strtoupper(__("ÉnoncÉ"))}}</div> @endif
    <div class="mb-3 p-3" style="background-color:white;border-radius:4px;">
        <div id="enonce">{{ $sujet_json->enonce }}</div>
    </div>
    <!-- /ÉNONCÉ --> 

    @if (isset($page_sujet_console) or isset($page_devoir_console))

        <!-- CODE ELEVE --> 
        <div class="mt-4 mb-1 text-monospace">{{strtoupper(__("code ÉlÈve"))}}</div>
        <div id="editor_code_eleve" style="border-radius:5px;">{{ $sujet_json->code_eleve }}</div>
        <!-- /CODE ELEVE -->

        <!-- CODE ENSEIGNANT --> 
        <div class="mt-4 text-monospace">{{strtoupper(__("code enseignant"))}}</div>
        <div id="editor_code_enseignant" style="border-radius:5px;">{{ $sujet_json->code_enseignant }}</div>
        <!-- /CODE ENSEIGNANT -->

        <!-- SOLUTION --> 
        <div class="mt-4 text-monospace">{{strtoupper(__('solution possible'))}}</div>
        <div id="editor_solution" style="border-radius:5px;">{{ $sujet_json->solution }}</div>
        <!-- /SOLUTION --> 	

    @endif

    {{-- ============== --}}
    {{-- ==== /EXO ==== --}}
    {{-- ============== --}}

@endif

@if ($sujet->type == 'pdf')

    {{-- ============== --}}
    {{-- ==== PDF ===== --}}
    {{-- ============== --}}
    
    <iframe id="sujet_pdf" src="{{Storage::url('SUJETS/sujet_'.$sujet->jeton.'.pdf')}}" width="100%" height="800" style="border: none;" class="rounded"></iframe>

    {{-- ============== --}}
    {{-- ==== /PDF ==== --}}
    {{-- ============== --}}

@endif 