<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script>
        tinymce.init({
          plugins: "code",
          selector:'textarea.editme',
          language_url : '/site_media/astinddu/js/eu.js',
          toolbar: "undo redo | styleselect | bold italic ",
          menubar: "insert edit"
        });
</script>
<script>

/*
* Limpia propiedades style y class en tinymce
* Cambia entidad html de acentos y eñes a alafanumerico
*/
function limpiar_cadena() {
    textarea = document.getElementById('edukia_4').value;

    tabla = {
        '&aacute;': 'á', '&Aacute;': 'Á',
        '&eacute;': 'é', '&Eacute;': 'É',
        '&iacute;': 'í', '&Iacute;': 'Í',
        '&oacute;': 'ó', '&Oacute;': 'Ó',
        '&uacute;': 'ú', '&Uacute;': 'Ú',
        '&ntilde;': 'ñ', '&Ntilde;': 'Ñ',
        '&acute;': '´', '&nbsp;': ' ',
        "<br\ \/>": "<br>",
        "\ class=\"(.){1,}\"": "",
        "\ style=\"(.){1,}\"": "",
        "\ dir=\"(.){1,}\"": ""
    };

    resultado = textarea;

    for(key in tabla) {
        regex_html = new RegExp(key, 'g');
        resultado = resultado.replace(regex_html, tabla[key]);
    }

    document.getElementById('edukia_4').value = resultado;
}

window.onload = function() {
    pagina = get_pagina_actual();
    encode_password();
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
        parrafoa = europiocode.encode('parrafoa_3');
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
        europiocode.encode_preformat('abestiak_5');
    };
}

function encode_password() {
    europiocode = new EuropioCode();

    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('pasahitza_7');
    };
}

</script>