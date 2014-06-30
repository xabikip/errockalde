<script>
window.onload = function(){
    europiocode = new EuropioCode();
    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('bandcamp_8');
        europiocode.encode('youtube_9');
    };
};
</script>