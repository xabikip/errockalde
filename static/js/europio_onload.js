<script>
window.onload = function(){
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
};
</script>