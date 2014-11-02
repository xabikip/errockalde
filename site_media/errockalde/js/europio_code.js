/*
* EuropioCode JS
*
* Codificador y decodificador de código.
*
* Codifica cadenas de texto convirtiendo caracteres no alfanuméricos en pseudo
* codigo, sanitizando así, cualquier campo de formulario previo a su
* envío. Luego, decodifica el pseudocódigo convirtiéndolo en entidades
* hexadecimales de HTML.
* Utilizado de forma conjunta con ModSecurity y las reglas de OWASP,
* lograrán formularios invulnerables con aplicaciones 100% funcionales, gracias
* a su deodificador que interpretará el código de forma tal, que sean evitados
* los falsos positivos de ModSecurity.
*
* EuropioCode is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* EuropioCode is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    EuropioCode
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
* @version    beta 1
*/


function EuropioCode() {

    // tabla de números HTML equivalentes en entidades hexadecimales
    this.tabla = {
      '!': 33, '"': 34, '#': 35, '$': 36, '%': 37, '&': 38, "'": 39,
      '(': 40, ')': 41, '*': 42, '+': 43, ',': 44, '.': 46, '/': 47,
      ':': 58, '<': 60, '=': 61, '>': 62, '?': 63, '@': 64, '[': 91,
      '\\': 92, ']': 93, '^': 94, '_': 95, '`': 96, '{': 123, '|': 124,
      '}': 125, '~': 126, '€': 128, ' ': 160, '¡': 161, '£': 163, '«': 171,
      '´': 180, '·': 183, '»': 187, '¿': 191, 'Ç': 199, 'ç': 231,
      'Á': 193, 'É': 201, 'Í': 205, 'Ó': 211, 'Ú': 218, 'Ü': 220, 'Ñ': 209,
      'á': 225, 'é': 233, 'í': 237, 'ó': 243, 'ú': 250, 'ü': 252, 'ñ': 241,
      '\t': '09'
    };

    // prefijo utilizado en EuropioCode para la codificación de tags admitidos
    // en cadenas de texto con preformato
    this.preformat_prefix = "pFt";

    // tabla de tags permitidos en textos preformateados.
    // los hiperenlaces se manejan de forma separada. NO AGREGAR el tag <a>
    this.preformat_table = new Array(
            "<b>", "<strong>", "<i>", "<em>", "<u>",
            "<strike>", "<sub>", "<sup>",
            "<p>", "<blockquote>", "<hr>",
            "<ul>", "<ol>", "<li>",
            "<h1>", "<h2>", "<h3>", "<h4>", "<h5>", "<h6>",
            "<code>", "<pre>", "<br>", "<small>"
    );

    /**
    * Codificar el valor de un campo de formulario en formato EuropioCode
    *
    * @param  (string) campo -- id del campo de formulario a ser codificado
    * @return void
    */
    this.encode = function(campo) {
        cadena_original = document.getElementById(campo).value;
        longitud_cadena = cadena_original.length;

        resultado = cadena_original.replace(/-/g, 'ECODG45ECODC');
        resultado = resultado.replace(/;/g, 'ECODG59ECODC');

        for(i=0; i<longitud_cadena; i++) {
            buscar = cadena_original[i];
            codigo_reemplazo = "ECODG" + this.tabla[buscar] + "ECODC";
            reemplazo = this.tabla[buscar] ? codigo_reemplazo : buscar;
            resultado = resultado.replace(buscar, reemplazo);
        }

        resultado = resultado.replace(/\n/g, 'ECODS');
        resultado = resultado.replace(/\s/g, 'ECODG160ECODC');

        document.getElementById(campo).value = resultado;
        document.getElementById(campo).readOnly = 'ReadOnly';
    };


    /**
    * Decodifica una cadena en formato EuropioCode a sus entidades HTML
    *
    * @param  (string) cadena -- la cadena a ser decodificada
    * @param  (string) tipo_salto -- (opcional) '\n' o '<br>'
    * @return (string) retorno -- la cadena convertida a entidades HTML
    */
    this.decode = function(cadena, tipo_salto) {
        break_line = (tipo_salto) ? tipo_salto : '\n';
        retorno = cadena.replace(/ECODS/g, break_line);
        retorno = retorno.replace(/ECODG/g, '&#');
        retorno = retorno.replace(/ECODC/g, ';');
        return retorno;
    };


    /**
    * Codificar el valor de un campo de formulario de texto con preformato,
    * respetando los tags HTML permitidos, especificados en el array
    * EuropioCode.preformat_table
    *
    * @param  (string) campo -- id del campo de formulario a ser codificado
    * @return void
    */
    this.encode_preformat = function(campo) {
        texto_preformateado = document.getElementById(campo).value;
        cadena = encode_hyperlink(texto_preformateado);
        longitud_tabla = this.preformat_table.length;
        for(i=0; i<longitud_tabla; i++) {
            numero = (i < 10) ? '0' + i : i;

            tag_apertura = new RegExp(this.preformat_table[i], 'g');
            pFt_apertura = this.preformat_prefix + numero;
            cadena = cadena.replace(tag_apertura, pFt_apertura);

            tag_cierre_str = this.preformat_table[i].replace("<", "</");
            tag_cierre = new RegExp(tag_cierre_str, 'g');
            pFt_cierre = this.preformat_prefix + 'e' + numero;
            cadena = cadena.replace(tag_cierre, pFt_cierre);
        }

        document.getElementById(campo).value = cadena;
        document.getElementById(campo).readOnly = 'ReadOnly';
        this.encode(campo)
    };


    /**
    * Codificar los hiperenlaces en una cadena de texto con preformato
    * solo admite enlaces con el formato <a href="url"> o
    * <a href="url" target="_self|_top|_blank">
    *
    * @param  (string) cadena -- cadena de texto con preformato conteniendo
    *                            los enlaces a ser codificados
    * @return (string) cadena -- la cadena con los enlaces codificados
    */
    encode_hyperlink = function(cadena) {
        diccionario = {
            ">": "fT0x1", "\"": "", "<a\ href=": "aH0n2",
            "target=_": "tG0n7", ":\/\/": "pT7n3", "\/": "bB0n1",
            "\~": "nN0n5", "\\.": "p01nt", "\-": "gN6n1"
        }

        cadena = cadena.replace(/<\/a>/g, "eT0n1");

        coincidencias = cadena.match(/<a\ href=\"[a-zA-Z0-9|\.|\:|\/|-]+\"(\ target=\"_[a-z]{3,5}\")?>/g);
        hiperenlaces = (coincidencias) ? coincidencias : new Array();

        for(i=0; i<hiperenlaces.length; i++) {
            enlace = hiperenlaces[i];
            for(etiqueta in diccionario) {
                regex = new RegExp(etiqueta, 'g');
                enlace = enlace.replace(regex, diccionario[etiqueta]);
            }
            cadena = cadena.replace(hiperenlaces[i], enlace);
        }
        return cadena;
    }

}
