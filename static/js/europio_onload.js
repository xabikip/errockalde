<script>
window.onload = function(){
    europiocode = new EuropioCode();
    document.getElementByTagName('form').onsubmit = function() {
        europiocode.encode('bandcamp');
        europiocode.encode('youtube');
    };
};
</script>