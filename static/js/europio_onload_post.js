<script>
window.onload = function(){
    europiocode = new EuropioCode();
    document.getElementsByTagName('form')[0].onsubmit = function(){
        europiocode.encode('parrafoa_3');
        europiocode.encode_preformat('edukia_4')
    };
};
</script>