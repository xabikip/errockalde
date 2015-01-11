function habilitar(field) {
    if(document.getElementById('level').selectedIndex == 5) {
        document.getElementById(field).disabled = false;
        document.getElementById(field).focus();
    } else {
        document.getElementById(field).value = '';
        document.getElementById(field).disabled = true;
    }
    
}


function generar_pwd(field) {
    var raritos = ["@", "#", "$", "-", "_"];
    var minusculas = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
        'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    var mayusculas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
        'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    var numeros = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];

    clave = get_random_char(minusculas, 6);
    clave += get_random_char(raritos, 1);
    clave += get_random_char(mayusculas, 3);
    clave += get_random_char(numeros, 2);

    document.getElementById(field).readOnly = true;
    document.getElementById(field).value = clave;
    document.getElementById(field).className = 'm';
    document.getElementById('errorpwd').innerHTML = '';
    document.getElementById('pwd_generada').style.display = 'inline';
    str_clave = " - Guarde esta clave: <b>" + clave + "</b>";
    document.getElementById('pwd_generada').innerHTML = str_clave;
}


function get_random_char(stack, cantidad) {
    choice = '';
    for(i=0; i<cantidad; i++) {
        indice = Math.floor((Math.random() * (stack.length - 1)) + 1);
        choice += stack[indice];
    }
    return choice;
}


function verificar_nombre() {
    username = document.getElementById('name').value;
    if(username.length > 5) {
        document.getElementById('name').className = 's';
        document.getElementById('usererror').innerHTML = '';
    }
}


function confirmar(username, userid) {
    pregunta = "¿Está seguro de eliminar al usuario \"" + username + "\"?";
    if(confirm(pregunta)) {
        location.href='/users/user/eliminar/' + userid;
    }
}
