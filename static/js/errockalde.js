<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script>
        tinymce.init({
          selector:'textarea.editme',
          language_url : '/static/js/eu.js',
          toolbar: "undo redo | styleselect | bold italic ",
          menubar: "insert edit"
        });
</script>
<script>
window.onload = function() {
    pagina = get_pagina_actual();
    switch(pagina) {
        case 'post': set_encode_post(); break;
        case 'talde': set_encode_talde(); break;
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
    return pagina;
}

function set_encode_post() {
    europiocode = new EuropioCode();

    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('parrafoa_3');
        europiocode.encode_preformat('edukia_4');
    };
}


function set_encode_talde() {
    europiocode = new EuropioCode();

    bandcamp_txt = document.getElementById('bandcamp_8').value;
    document.getElementById('bandcamp_8').innerHTML = europiocode.decode(bandcamp_txt);

    _youtube_txt = document.getElementById('_youtube_9').value;
    document.getElementById('_youtube_9').innerHTML = europiocode.decode(_youtube_txt);

    youtube_txt = document.getElementById('_youtube_9').value;
    document.getElementById('youtube_10').value = europiocode.decode(youtube_txt);

    document.getElementsByClassName('tr')[7].style.display = 'none';

    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('bandcamp_8');
        europiocode.encode('youtube_9');
    };
}

</script>