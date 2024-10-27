<div class="mb-5" style="padding:20px;border:solid 1px #DBE0E5;border-radius:4px;background-color:#f3f5f7;border-radius:4px;">

    <h2 class="p-0 m-0 mb-4">{{ $sujet->titre }}</h2>
    <iframe id="sujet_pdf" src="{{Storage::url('SUJETS/sujet_'.$sujet->jeton.'.pdf')}}" width="100%" height="800" style="border: none;" class="rounded"></iframe>

</div>
    
@include('inc-bottom-js')