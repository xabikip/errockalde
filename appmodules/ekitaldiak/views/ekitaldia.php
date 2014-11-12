<?php

class EkitaldiaView {

    public function agregar($ekitaldimotak, $lekuak, $errores = array()) {
        Dict::set_dict_for_webform($ekitaldimotak, 'deitura', @$_POST['deitura']);

        $form = new WebFormPRO('/ekitaldiak/ekitaldia/guardar');
        $form->add_text('ekitaldi_izena', 'Ekitaldiaren Izena',@$_POST['ekitaldi_izena']);
        $form->add_select('ekitaldimota', 'Ekitaldi Mota', $ekitaldimotak);
        $form->add_textarea('deskribapena', 'deskribapena', @$_POST['deskribapena']);
        $form->add_text('data', 'data', @$_POST['data']);
        $form->add_text('ordua', 'ordua', @$_POST['ordua']) ;

        $form->add_text('izena', 'Lekuaren Izena', @$_POST['izena'], "list='lekuak'");
        $html_datalist = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/DataListLekua.html');
        $render = Template($html_datalist)->render_regex('lekuak', $lekuak);
        $form->fields[] = $render;

        $form->add_text('helbidea', 'helbidea', @$_POST['helbidea'], "list='helbideak'");
        $html_datalist = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/DataListHelbidea.html');
        $render = Template($html_datalist)->render_regex('helbideak', $lekuak);
        $form->fields[] = $render;

        $form->add_text('herria', 'herria', @$_POST['herria'], "list='herriak'");
        $html_datalist = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/DataListHerria.html');
        $render = Template($html_datalist)->render_regex('herriak', $lekuak);
        $form->fields[] = $render;

        $form->add_file('kartela', 'kartela', @$_POST['file']);
        $form->add_submit('Ekitaldia gehitu');
        $form->add_errorzone($errores);

        $js_datepicker = file_get_contents(CUSTOM_STATIC_DIR ."/js/datepickerCustom.js");
        $html = $js_datepicker . $form->get_form();
        render_final_back($html, "Ekitaldi berria");
    }

    public function editar($obj=array(), $ekitaldimotak, $lekuak, $errores = array()) {
        Dict::set_dict_for_webform($ekitaldimotak, 'deitura', $obj->ekitaldimota->ekitaldimota_id);

        $form = new WebFormPRO('/ekitaldiak/ekitaldia/guardar');
        $form->add_hidden('id', $obj->ekitaldia_id);
        $form->add_text('ekitaldi_izena', 'Ekitaldiaren Izena',$obj->izena);
        $form->add_select('ekitaldimota','Ekitaldi Mota', $ekitaldimotak);
        $form->add_textarea('deskribapena', 'deskribapena', $obj->deskribapena);
        $form->add_text('data', 'data', $obj->data);
        $form->add_text('ordua', 'ordua', $obj->ordua);

        $form->add_text('izena', 'Lekuaren Izena', $obj->lekua->izena, "list='lekuak'");
        $html_datalist = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/DataListLekua.html');
        $render = Template($html_datalist)->render_regex('lekuak', $lekuak);
        $form->fields[] = $render;

        $form->add_text('helbidea', 'helbidea', $obj->lekua->helbidea, "list='helbideak'");
        $html_datalist = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/DataListHelbidea.html');
        $render = Template($html_datalist)->render_regex('helbideak', $lekuak);
        $form->fields[] = $render;

        $form->add_text('herria', 'herria', $obj->lekua->herria, "list='herriak'");
        $html_datalist = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/DataListHerria.html');
        $render = Template($html_datalist)->render_regex('herriak', $lekuak);
        $form->fields[] = $render;

        $form->add_file('kartela', 'kartela');
        $html_irudia = file_get_contents( CUSTOM_STATIC_DIR . '/html/back/ekitaldiak/edit_ekitaldi_irudia.html');
        $render = Template($html_irudia)->render($obj);
        $form->fields[] = $render;

        $form->add_submit('Ekitaldia aldatu');
        $form->add_errorzone($errores);
        render_final_back($form->get_form(), "Ekitaldia aldatu");
    }

    public function listar($coleccion=array()) {
        foreach ($coleccion as $obj) {
            $obj->lekua = $obj->lekua->izena;
            $obj->ekitaldimota = $obj->ekitaldimota->deitura;
            unset($obj->deskribapena);
            unset($obj->slug);
        }

        $tabla = new CustomCollectorViewer($coleccion, 'ekitaldiak',  'ekitaldia',
            False, True, True);
        render_final_back($tabla->get_table(), "Ekitaldien zerrenda");
    }

    public function ekitaldiak($ekitaldiak=array()) {
        foreach ($ekitaldiak as $obj) {
            $obj->deitura = $obj->ekitaldimota->deitura;
            $obj->ordua = substr($obj->ordua, 0, 5);
            $obj->herria = $obj->lekua->herria;
            $obj->lekua = $obj->lekua->izena;

            $data_partes = explode("-", $obj->data);
            $obj->hilabetea = $this->hilabete_izena($data_partes['1']);
            $obj->eguna = $data_partes['2'];
        }

        //Render ekitaldiak
        $plantilla = file_get_contents(CUSTOM_STATIC_DIR . '/html/front/ekitaldiak/ekitaldiak.html');
        $render_ekitaldiak = Template($plantilla)->render_regex('EKITALDIAK', $ekitaldiak);

        //Mostrar
        print Template('Ekitaldiak', CUSTOM_PUBLIC_TEMPLATE)->show($render_ekitaldiak);
    }

    public function ekitaldia($ekitaldia=array()) {
        // print_r($ekitaldia);exit;

        //Modificopropiedades
        $ekitaldia->ordua = substr($ekitaldia->ordua, 0, 5);
        $ekitaldia->deitura = $ekitaldia->ekitaldimota->deitura;
        $ekitaldia->herria = $ekitaldia->lekua->herria;
        $ekitaldia->lekua = $ekitaldia->lekua->izena;

        //Render ekitaldiak
        $plantilla = file_get_contents(CUSTOM_STATIC_DIR . '/html/front/ekitaldiak/ekitaldia.html');
        $render_ekitaldia = Template($plantilla)->render($ekitaldia);

        //Render imagen
        $imagen = WRITABLE_DIR . EKITALDI_IRUDI_DIR . "/{$ekitaldia->ekitaldia_id}";
        if (!file_exists($imagen)){
            $render_ekitaldia = $this->eliminar_bloque("IRUDIA{$ekitaldia->ekitaldia_id}", $render_ekitaldia);
        }


        //Mostrar
        print Template('Ekitaldia', CUSTOM_PUBLIC_TEMPLATE)->show($render_ekitaldia);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function eliminar_bloque($identificador, $plantilla) {
        $identificador = $identificador;
        $bloque_eliminar = Template($plantilla)->get_substr($identificador);
        return $render_eliminado = str_replace($bloque_eliminar, "", $plantilla);
    }

    private function hilabete_izena($num) {
        switch ($num) {
            case '01':
                return "URT";
                break;
            case '02':
                return "OTS";
                break;
            case '03':
                return "MAR";
                break;
            case '04':
                return "API";
                break;
            case '05':
                return "MAI";
                break;
            case '06':
                return "EKA";
                break;
            case '07':
                return "UZT";
                break;
            case '08':
                return "ABU";
                break;
            case '09':
                return "IRA";
                break;
            case '10':
                return "URR";
                break;
            case '11':
                return "AZA";
                break;
            case '12':
                return "ABE";
                break;

        }

    }


}

?>
