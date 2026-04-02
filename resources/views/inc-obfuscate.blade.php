<script>
function resizeinput(inp) {
    if (inp.value.length > 2){
        inp.setAttribute('size', inp.value.length);
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<script>
    function bravo() {
        var defaults = {
            spread: 360,
            ticks: 400,
            gravity: 1,
            decay: 0.94,
            startVelocity: 40,
            shapes: ['star'],
            colors: ['FFE400', 'FFBD00', 'E89400', 'FFCA6C', 'FDFFB8']
        };
        function shoot() {
            confetti({
                ...defaults,
                particleCount: 80,
                scalar: 2,
                shapes: ['star']
            });

            confetti({
                ...defaults,
                particleCount: 40,
                scalar: 5,
                shapes: ['circle']
            });
        }
        setTimeout(shoot, 0);
        setTimeout(shoot, 200);
        setTimeout(shoot, 400);
        setTimeout(shoot, 600);
        setTimeout(shoot, 800);
        setTimeout(shoot, 1000);
        setTimeout(shoot, 1200);
        setTimeout(shoot, 1400);
        setTimeout(shoot, 1500);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<?php
// CODE FOR ACE TEXTAREA WHEN PUZZLE SOLVED
$code_correct_array = explode(PHP_EOL, $code_correct);
$ace_code = "";
$n = 0;
foreach($code_correct_array as $line) {
    $n++;
    if (trim($line) !== "") {
        $prefixe = '"';
        $suffixe = ($n !== count($code_correct_array)) ? '\n"+'."\n" : '";';
        $ace_code .= $prefixe . rtrim(addslashes($line)) . $suffixe;
    }
}


// CODE FOR PUZZLE avec SELECT et INPUT
$cat_ccm_ids = "";
$code_puzzle = preg_replace_callback("/\[\?(.*?)\?\]/m", function($matches) use (&$cat_ccm_ids) {
    static $ccm_matchcount = 1;
    static $cat_matchcount = 1;
    if (strpos($matches[1], '?')){
        $items_array = explode('?', $matches[1]);
        $ccm_s = $items_array[0];
        shuffle($items_array);
        $ccm_count = $ccm_matchcount++;
        $select_block = '<select id="' . base64_encode("ccm_" . $ccm_count . "-". $ccm_s) . '" class="ccm ccm_incorrect"><option></option>';
        foreach ($items_array as $value) {
            $select_block .= '<option value="'.$value.'">'.$value.'</option>';
        }
        $select_block .= '</select>';
        $cat_ccm_ids .= "\"" . base64_encode("ccm_" . $ccm_count . "-". $ccm_s) . "\",";
        return $select_block;
    } else {
        $cat_count = $cat_matchcount++;
        $cat_ccm_ids .= "\"" . base64_encode("cat_" . $cat_count . "-". $matches[1]) . "\",";
        return '<input id="' . base64_encode("cat_" . $cat_count . "-". $matches[1]) . '" class="cat cat_incorrect" size="2" type="text" oninput="resizeinput(this)" autocomplete="off" />';
    }

}, $puzzle->code);

// Liste des id pour les cat et ccm
$cat_ccm_ids = "[" . trim($cat_ccm_ids, ",") . "]";


$code_array = array_filter(explode(PHP_EOL, $code_puzzle)); // array_filter enlève les lignes vides
$fakecode_array = array_filter(explode(PHP_EOL, $puzzle->fakecode)); // array_filter enlève les lignes vides
$fakecode_array = array_map(function($val){return trim($val) . ' #distractor';}, $fakecode_array);
$js_code_array = array_merge($code_array, $fakecode_array);

$js_code = "";
$n = 0;

foreach($js_code_array as $line) {
    $n++;
    if (trim($line) !== "") {
        $prefixe = '"';
        $suffixe = ($n !== count($js_code_array)) ? '\n"+'."\n" : '";';
        $js_code .= $prefixe . rtrim(addslashes($line)) . $suffixe;
    }
}
?>

<script>
var count;
var intervalRef = null;

var chrono = {
    start: function () {
        let start = new Date();
        intervalRef = setInterval(_ => {
            let current = new Date();
            count = +current - +start;
            let s = Math.floor((count /  1000)) % 60;
            let m = Math.floor((count / 60000)) % 60;
            if (s < 10) {
                s_display = '0' + s;
            } else {
                s_display = s;
            }
            if (m < 10) {
                m_display = '0' + m;
            } else {
                m_display = m;
            }
            $('#chrono').text(m_display + ":" + s_display);
        }, 1000);
    },
    stop: function () {
        clearInterval(intervalRef);
        delete intervalRef;
    },
}
chrono.start();


<?php // CREATION DU CODE JS CACHE
$js_source = '
(function(){
    var jsc = '.$js_code.'
    var ac = '.$ace_code.'
    var parsonsPuzzle = new ParsonsWidget({
        "sortableId": "sortable",
';

if ($puzzle->fakecode !== NULL OR $puzzle->with_dragdrop){
    $js_source .= '        "trashId": "sortableTrash",';
} 

$js_source .= '
        "max_wrong_lines": 10,
        "exec_limit": 2500,
        "can_indent": true,
        "x_indent": 40,
        "lang": "en",
    });
    parsonsPuzzle.init(jsc);
    parsonsPuzzle.shuffleLines(); 
    $("#reinitialiser").click(function(event){
        location.reload();
    });

    var nb_tentatives = 1;

    $("#feedbackLink").click(function(event){
        event.preventDefault();
        var feedback = parsonsPuzzle.getFeedback();

        $("#nb_tentatives").text(nb_tentatives++);

        var solved = true;
        const cat_ccm_ids = '. $cat_ccm_ids .';

        cat_ccm_ids.forEach(function(id) {
            id_data = atob(id);
            var r = id_data.split("-")[1];
            if (document.getElementById(id).value == r) {
                if (id_data.slice(0,3) == "cat") {
                    document.getElementById(id).classList.remove("cat_incorrect");
                    document.getElementById(id).classList.add("cat_correct");
                }
                if (id_data.slice(0,3) == "ccm") {
                    document.getElementById(id).classList.remove("ccm_incorrect");
                    document.getElementById(id).classList.add("ccm_correct");
                }
            } else {
                if (id_data.slice(0,3) == "cat") {
                    document.getElementById(id).classList.remove("cat_correct");
                    document.getElementById(id).classList.add("cat_incorrect");
                }
                if (id_data.slice(0,3) == "ccm") {
                    document.getElementById(id).classList.remove("ccm_correct");
                    document.getElementById(id).classList.add("ccm_incorrect");
                }
                solved = false;
            }
        });

        // correct / incorrect classes
        var classes = "";
        $("li.prettyprint").each(function(element) {
            classes = classes + " " + this.className;
        });
        if (classes.includes("incorrectPosition") || classes.includes("incorrectIndent") || !solved) {
            $("#ul-sortable").removeClass("correct");
            $("#ul-sortable").addClass("incorrect");
        } else {
            $("#ul-sortable").removeClass("incorrect");
            $("#ul-sortable").addClass("correct");
        }

        // freeze correct puzzle
        if (feedback.length == 0 && solved) {';

if(isset($jeton_eleve)) {
    $js_source .= 'classe_activite_enregistrer();';
}
$js_source .= '
            chrono.stop();
            bravo();
            $("ul").sortable("disable");
            $("#feedbackLink").css({display: "none"});
            $("#copyLink").css({display: "inline"});
            $("#basthon").css({display: "inline"});
            $("#kaggle").css({display: "inline"});
            document.getElementById("copyLink").innerHTML = "<button type=\'button\' class=\'btn btn-light btn-sm\'><i class=\'fas fa-clone\'></i></button>";
            document.getElementById("kaggle").innerHTML = "<a class=\'btn btn-light btn-sm\' href=\'https://kaggle.com/kernels/welcome?src=https://www.codepuzzle.io/code/' . $puzzle->uuid . '.ipynb\' role=\'button\' target=\'_blank\'><img src=\'/img/bouton_kaggle.png\' height=\'16\' /></a>";
            if (document.getElementById("basthon")) {
                document.getElementById("basthon").innerHTML = "<a class=\'btn btn-light btn-sm\' href=\'https://notebook.basthon.fr/?from=https://www.codepuzzle.io/code/' . $puzzle->uuid . '.ipynb\' role=\'button\' target=\'_blank\'><img src=\'/img/bouton_basthon.png\' height=\'16\' /></a>";
            }
        }

    });

    $("#bouton_basthon").click(function(event){
        window.open("https://notebook.basthon.fr/?from=https://www.codepuzzle.io/code/' . $puzzle->uuid . '.ipynb", "_blank");
    });

    $("#copyLink").click(function(event){
        $("#ul-sortable").css({display: "none"});
        $("#copyLink").css({display: "none"});
        $("#editor_codesource").text(ac.trim());
        $("#codesource").css({display: "block"});
        editor_code = "editor_codesource";
        var editor_code = ace.edit(editor_code, {
            theme: "ace/theme/puzzle_code",
            mode: "ace/mode/python",
            maxLines: 500,
            fontSize: 14,
            wrap: true,
            useWorker: false,
            highlightActiveLine: false,
            highlightGutterLine: false,
            showPrintMargin: false,
            displayIndentGuides: true,
            showLineNumbers: true,
            showGutter: true,
            showFoldWidgets: false,
            useSoftTabs: true,
            navigateWithinSoftTabs: false,
            tabSize: 4,
            readOnly: true
        });
        editor_code.container.style.lineHeight = 1.5;
    });

})();';
$packer = new Tholu\Packer\Packer($js_source, 'Normal', true, false, true);
$js_source_obfuscated = $packer->pack();
echo $js_source_obfuscated;
?></script>

<script>
interact("input").on('tap', function (event) {
	event.target.focus();
});
interact("select").on('down', function (event) {
	$("ul").sortable("disable");
});
interact("ul").on('tap', function (event) {
	$("ul").sortable("enable");
});
</script>
