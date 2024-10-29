<div class="p-3" style="border:1px solid #ced4da;border-radius:4px;background-color:white;">
<div class="pb-2 text-uppercase font-weight-bold" style="color:black;font-size:20px;font-family: 'Latin Modern Roman', serif;">{{ $sujet->titre }}</div>

@if ($sujet->type == 'exo')

    {{-- ============== --}}
    {{-- ==== EXO ===== --}}
    {{-- ============== --}}

    <!-- ÉNONCÉ --> 
    <div class="markdown_content">{{ $sujet_json->enonce }}</div>
    <!-- /ÉNONCÉ --> 

    @if (isset($page_sujet_console) or isset($page_devoir_console) or isset($page_devoir_creer) or isset($page_sujet))
        @foreach($sujet_json->code AS $code)
            @if(count((array) $sujet_json->code) > 1)
                <div class="text-monospace mt-3 small">PROGRAMME {{ $loop->iteration }}</div>
            @else
                <div class="text-monospace mt-3 small">PROGRAMME</div>
            @endif
            <div class="p-3" style="border:solid #ced4da 1px;border-radius:4px;background-color:#F3F5F7;">
                <!-- CODE ELEVE --> 
                <div class="mb-1 text-monospace small">{{strtoupper(__("code ÉlÈve"))}}</div>
                <div id="code_editor_eleve_{{ $loop->iteration }}" style="border-radius:5px;">{{ $code->code_eleve }}</div>
                <!-- /CODE ELEVE -->

                <!-- CODE ENSEIGNANT --> 
                <div class="mt-4 text-monospace small">{{strtoupper(__("code enseignant"))}}</div>
                <div id="code_editor_enseignant_{{ $loop->iteration }}" style="border-radius:5px;">{{ $code->code_enseignant }}</div>
                <!-- /CODE ENSEIGNANT -->

                <!-- SOLUTION --> 
                <div class="mt-4 text-monospace small">{{strtoupper(__('solution possible'))}}</div>
                <div id="code_editor_solution_{{ $loop->iteration }}" style="border-radius:5px;">{{ $code->code_solution }}</div>
                <!-- /SOLUTION --> 	
            </div>
        @endforeach
    @endif

    {{-- ============== --}}
    {{-- ==== /EXO ==== --}}
    {{-- ============== --}}

@endif

@if ($sujet->type == 'pdf')

    {{-- ============== --}}
    {{-- ==== PDF ===== --}}
    {{-- ============== --}}
    
    <!-- ÉNONCÉ --> 
    <div class="markdown_content">{{ $sujet_json->enonce }}</div>
    <!-- /ÉNONCÉ --> 
    <iframe id="sujet_pdf" src="{{Storage::url('SUJETS/sujet_'.$sujet->jeton.'.pdf')}}" width="100%" height="800" style="border: none;" class="mt-3 rounded"></iframe>

    {{-- ============== --}}
    {{-- ==== /PDF ==== --}}
    {{-- ============== --}}

@endif 

@if ($sujet->type == 'md')

    {{-- ============== --}}
    {{-- ==== MD ====== --}}
    {{-- ============== --}}
    
    <!-- ÉNONCÉ --> 
    <div class="markdown_content">{{ $sujet_json->enonce }}</div>
    <!-- /ÉNONCÉ --> 

    {{-- ============== --}}
    {{-- ==== /MD ===== --}}
    {{-- ============== --}}

@endif 

</div>