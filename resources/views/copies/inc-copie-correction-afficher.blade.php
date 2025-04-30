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

                        <!-- Code élève -->
                        <div class="text-monospace small text-muted"><kbd>1</kbd> Code élève <i class="text-muted small">en lecture seule</i></div>
                        <div id="code_editor_eleve_{{ $loop->iteration }}" class="mt-1 mb-2 code-editor"></div>

                        <!-- Code enseignant -->
                        <div class="text-monospace small text-muted"><kbd>2</kbd> Code enseignant</div>
                        <div id="code_editor_enseignant_{{ $loop->iteration }}" class="mt-1 mb-2 code-editor"></div>

                        <!-- Console -->
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
                                    <!-- BOUTON RUN -->
                                    <button id="run_{{ $loop->iteration }}" data-pyodide="run" onclick="run('{{ $loop->iteration }}')" type="button" class="btn btn-primary text-center mb-1" style="width:40px;"><i class="fas fa-circle-notch fa-spin"></i></button>
                               
                                    <!-- BOUTON RESTART -->
                                    <button id="restart_{{ $loop->iteration }}" data-pyodide="restart" onclick="restart('{{ $loop->iteration }}')" type="button" class="btn btn-dark text-center mb-1" style="width:40px;display:none;" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Cliquer ici pour interrompre  l\'exécution du code. Python redémarrera complètement mais votre code sera conservé dans l\'éditeur. Le redémarrage peut prendre quelques secondes.')}}"><i class="fas fa-stop"></i></button>
                                </div>
                                
<!-- copier prompt dans presse papier -->
<?php
if ($sujet->type == 'exo'){

$prompt = "
Vous êtes un assistant pédagogique expert en Python.  
Je vais vous fournir :  
1. L'énoncé complet de l'exercice.  
2. Le code que l'élève a proposé.  

Merci de me rendre un rapport structuré comportant deux parties.

**PARTIE 1 - Présentation synthétique**
Dire si code est correct. Et si il ne l'est pas, faire la liste des erreurs en faisant référence, si possible, aux numéros de ligne du code.

**PARTIE 2 - Présentation détaillée**
1. **Résumé de l'exercice** (en vos propres mots) - pour vérifier la bonne compréhension du sujet.  
2. **Analyse de la correction fonctionnelle**  
- Le code répond-il à tous les points de l'énoncé ?  
- Cas limites ou d'erreurs non gérés.  
3. **Points techniques et logiques**  
- Erreurs à corriger (avec référence aux numéros de ligne).  
- Améliorations possibles de l'algorithme (complexité, clarté).  
4. **Qualité du code**  
- Respect des conventions Python (PEP 8, noms de variables, docstrings, etc.).  
- Lisibilité et structuration (fonctions, modularité).  
5. **Propositions de tests**  
- Jeux de données pour tester chaque situation (valeurs normales, extrêmes, cas particuliers).  
- Version avec des `assert` prêts à être copiés-collés
6. **Suggestions pédagogiques**  
- Explications à donner à l'élève pour chaque erreur.  
- Exercices complémentaires ou variantes pour approfondir.  

---

**ÉNONCÉ DE L'EXERCICE :**  
{$sujet_json->enonce}

**CODE DE L'ÉLÈVE :**  
```python
{$copie_cell->source[0]}
```
Merci de structurer votre réponse en sections numérotées et de citer les lignes de code concernées lorsque vous signalez un problème.";

}else {

$prompt = "
Vous êtes un assistant pédagogique expert en Python.  
Je vais vous fournir le code que l'élève a proposé.  

Merci de me rendre un rapport structuré comportant deux parties.

**PARTIE 1 - Présentation synthétique**
Dire si code est correct. Et si il ne l'est pas, faire la liste des erreurs en faisant référence, si possible, aux numéros de ligne du code.

**PARTIE 2 - Présentation détaillée**
1. **Points techniques et logiques**  
- Erreurs à corriger (avec référence aux numéros de ligne).  
- Améliorations possibles de l'algorithme (complexité, clarté).  
2. **Qualité du code**  
- Respect des conventions Python (PEP 8, noms de variables, docstrings, etc.).  
- Lisibilité et structuration (fonctions, modularité).  
3. **Propositions de tests**  
- Jeux de données pour tester chaque situation (valeurs normales, extrêmes, cas particuliers).  
- Version avec des `assert` prêts à être copiés-collés
4. **Suggestions pédagogiques**  
- Explications à donner à l'élève pour chaque erreur.  
- Exercices complémentaires ou variantes pour approfondir.  

---

**CODE DE L'ÉLÈVE :**  
```python
{$copie_cell->source[0]}
```
Merci de structurer votre réponse en sections numérotées et de citer les lignes de code concernées lorsque vous signalez un problème.";

}
?>
                                <!-- BOUTON IA -->
                                <div class="mt-1 text-right">
                                    <button type="button" onclick='copyAndNotify(this, {!! json_encode($prompt, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS) !!})' class="btn btn-ia btn-xs" data-toggle="tooltip" data-placement="right"  data-trigger="hover" title="{{__('Copier une requête à coller dans votre modèle d\'IA préféré afin d\'analyser le code de l\'élève.')}}"><i class="fa-solid fa-wand-magic-sparkles" style="padding-top:5px;padding-bottom:3px;"></i></button>
                                </div>

                            </div>
                            <div id="console_{{ $loop->iteration }}" class="col">
                                <div class="text-dark text-monospace" style="float:right;font-size:70%;padding:5px 12px 0px 0px">console</div>
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

<script>
  function copyAndNotify(button, text) {
    const onSuccess = () => {
      animateOpacity(button);
      showCopiedMessage(button);
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(onSuccess, () => {
        fallbackCopy(text, button);
        showCopiedMessage(button);
      });
    } else {
      fallbackCopy(text, button);
      showCopiedMessage(button);
    }
  }

  function fallbackCopy(text, button) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.top = '-9999px';
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); }
    catch (err) { console.error('Copy failed', err); }
    document.body.removeChild(ta);
  }

  function animateOpacity(button) {
    button.style.transition = 'none';
    button.style.opacity    = '0.1';
    void button.offsetWidth;
    button.style.transition = 'opacity 2s ease-in-out';
    button.style.opacity    = '1';
  }

  function showCopiedMessage(button) {
    // 1) Calculer la position du bouton dans la page
    const rect = button.getBoundingClientRect();
    const msg  = document.createElement('span');
    msg.innerText = 'copié !';

    // 2) Styles inline pour que rien d'autre ne bouge
    Object.assign(msg.style, {
      position:      'absolute',
      left:          (rect.left + rect.width/2 + window.pageXOffset) + 'px',
      top:           (rect.bottom + window.pageYOffset + 4)  + 'px',
      transform:     'translateX(-50%)',
      whiteSpace:    'nowrap',
      pointerEvents: 'none',
      opacity:       '1',
      zIndex:        '9999',
      fontSize:      '0.9em',
      transition:    'transform 2s ease-out, opacity 2s ease-out'
    });

    document.body.appendChild(msg);

    // 3) Déclencher l’animation de descente + disparition
    void msg.offsetWidth;
    msg.style.transform = 'translateX(-50%) translateY(20px)';
    msg.style.opacity   = '0';

    // 4) Nettoyage après animation
    msg.addEventListener('transitionend', () => msg.remove());
  }
</script>
