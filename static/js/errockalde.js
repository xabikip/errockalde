<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script>
        tinymce.init({
          // plugins: "code",
          selector:'textarea.editme',
          language_url : '/static/js/eu.js',
          toolbar: "undo redo | styleselect | bold italic ",
          menubar: "insert edit"
        });
</script>
<script>

//funcion para limpiar propiedad style en tinymce
function limpiar_cadena() {
    regex = new RegExp("\ style=\"(.){0,}\"", 'g');
    textarea = document.getElementById('edukia_4').value;
    resultado = textarea.replace(regex, "");
    document.getElementById('edukia_4').value = resultado;


    textarea = document.getElementById('edukia_4').value;
    resultado = textarea.replace(/&ntilde;/g, 'ñ');
    resultado = resultado.replace(/&nbsp;/g, ' ');
    document.getElementById('edukia_4').value = resultado;
}

window.onload = function() {
    pagina = get_pagina_actual();
    switch(pagina) {
        case 'post': set_encode_post(); break;
        case 'talde': set_encode_talde(); break;
        case 'diskoa': set_encode_diskoa(); break;
    }
};

function get_pagina_actual() {
    var pagina = 'other';
    uri = location.href;
    if(uri.indexOf('post') > 0) {
         pagina = 'post';
    }
    if(uri.indexOf('talde') > 0) {
         pagina = 'talde';
    }
    if(uri.indexOf('diskoa') > 0) {
         pagina = 'diskoa';
    }
    return pagina;
}

function set_encode_post() {
    europiocode = new EuropioCode();

    document.getElementsByTagName('form')[0].onsubmit = function(){
        limpiar_cadena();
        europiocode.encode('parrafoa_3');
        europiocode.encode_preformat('edukia_4');
    };
}

function set_encode_talde() {
    europiocode = new EuropioCode();

    _youtube_txt = document.getElementById('_youtube_8').value;
    document.getElementById('_youtube_8').innerHTML = europiocode.decode(_youtube_txt);

    youtube_txt = document.getElementById('_youtube_8').value;
    document.getElementById('youtube_9').value = europiocode.decode(youtube_txt);

    document.getElementsByClassName('tr')[6].style.display = 'none';

    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('youtube_8');
    };
}

function set_encode_diskoa() {
    europiocode = new EuropioCode();

    bandcamp_txt = document.getElementById('bandcamp_6').value;
    document.getElementById('bandcamp_6').innerHTML = europiocode.decode(bandcamp_txt);

    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('bandcamp_6');
    };
}

</script>