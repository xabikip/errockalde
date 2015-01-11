<?php

class BazkideView {

    public function agregar($errores=array()) {

        $form = new WebFormPRO('/bazkideak/bazkide/guardar');
        $form->add_text('izena','izena', @$_POST['izena']);
        $form->add_text('abizena', 'abizena', @$_POST['abizena']);
        $form->add_text('goitizena', 'goitizena', @$_POST['goitizena']);
        $form->add_text('emaila', 'emaila', @$_POST['emaila']);
        $form->add_text('telefonoa', 'telefonoa', @$_POST['telefonoa']);
        $form->add_text('erabiltzailea', 'erabiltzailea', @$_POST['erabiltzailea']);
        $form->add_password('pasahitza', 'pasahitza', @$_POST['pasahitza']);
        $form->add_submit('Bazkidea gehitu');
        $form->add_errorzone($errores, "Kontuz!");

        //Mostrar form para agregar talde
        $js_europio = file_get_contents(CUSTOM_STATIC_DIR ."/js/astinddu.js");
        $html = $form->get_form() . $js_europio;
        render_final_back($html, "Bazkide berria");
    }

    public function editar($obj=array(), $errores=array()) {

        $form = new WebFormPRO('/bazkideak/bazkide/guardar');
        $form->add_hidden('id', $obj->bazkide_id);
        $form->add_text('izena','izena', $obj->izena, null);
        $form->add_text('abizena', 'abizena', $obj->abizena);
        $form->add_text('goitizena', 'goitizena', $obj->goitizena);
        $form->add_text('emaila', 'emaila', $obj->emaila);
        $form->add_text('telefonoa', 'telefonoa', $obj->telefonoa);
        $form->add_text('erabiltzailea', 'erabiltzailea', $obj->user->name);
        $form->add_submit('Aldaketak gorde');
        $form->add_errorzone($errores, "Kontuz!");
        render_final_back($form->get_form(), "Bazkidea editatu");
    }

    public function listar($coleccion=array()) {
        foreach ($coleccion as $obj) {
            $obj->erabiltzailea = $obj->user->name;
            unset($obj->user);
        }
        $str = new CustomCollectorViewer($coleccion, 'bazkideak', 'bazkide',
            False, True, True);

        render_final_back($str->get_table(), "Bazkide zerrenda");
    }

    public function berreskuratu($errores, $ok){
        $html = file_get_contents( CUSTOM_STATIC_DIR . "/html/back/berreskuratu.html");
        if(!$errores){
            $html = Template($html)->delete("ALERT");
        }
        if(!$ok){
            $html = Template($html)->delete("OK");
        }
        print $html;
    }

    public function ongiberreskuratu(){
        $html = file_get_contents( CUSTOM_STATIC_DIR . "/html/back/ongiBerreskuratua.html");
        print $html;
    }
}

?>
