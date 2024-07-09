// Colores de las leyendas
const error = 'red';
const info = 'blue';
const success = 'green';

// Funcion para cargar el texto dentro de un div con todas sus propiedades  
function cargarTexto(legendElement,tipo,mensaje) {
    document.getElementById(legendElement).innerHTML = `<b><font color="${tipo}">${mensaje}</font></b>`;
}

// Funcion para validar si no se ha ingresado texto y si cumple con la longitud correcta
function validateCambiarClave(elemento,legendElement){
    if (textEmpty(elemento)) {
        if (textLenght(elemento)) {
            cargarTexto(legendElement,success,'La contraseña ingresada posee la longitud correcta.');
        } else {
            cargarTexto(legendElement,info,'Debes ingresar una clave de minímo 8 caracteres.');   
        }
    } else {
        cargarTexto(legendElement,error,'Si deseas cambiar tu clave ingresa la nueva clave.');
    }
}

// Funcion para validar la longitud de un input
function textLenght(elemento){
    if (document.getElementById(elemento).value.length >= 7) {
        return true;    
    } else {
        return false;
    }
}

// Funcion para validar si no se ha ingresado texto
function validatetextEmpty(elemento,legendElement){
    if (document.getElementById(elemento).value != '') {
        cargarTexto(legendElement,info,'Corrobore que el dato ingresado sea correcto.');   
    } else {
        cargarTexto(legendElement,error,'Complete el campo, es obligatorio.');
    }
}

// Funcion para validar si no se ha ingresado texto
function validateTextPassword(elemento,legendElement){
    if (document.getElementById(elemento).value != '') {
        cargarTexto(legendElement,info,'Corrobora que la contraseña ingresada sea correcta.');   
    } else {
        cargarTexto(legendElement,error,'Si deseas actualizar tu contraseña, este campo es obligatorio.');
    }
}

// Funcion para validar si no se ha ingresado texto
function validateTextNewPassword(elemento,legendElement){
    if (document.getElementById(elemento).value != '') {
        if (document.getElementById(elemento).value.length >= 7) {
            cargarTexto(legendElement,success,'La longitud de la nueva contraseña es correcta.');      
        } else {
            cargarTexto(legendElement,info,'La longitud de la contraseña debe ser de 8 caracteres como mínimo.');   
        }
    } else {
        cargarTexto(legendElement,error,'Si deseas actualizar tu contraseña, este campo es obligatorio.');
    }
}

// Funcion para validar si no se ha ingresado texto
function validateTextEmpty(elemento,legendElement){
    if (document.getElementById(elemento).value != '') {
        cargarTexto(legendElement,info,'Corrobora que el dato ingresado sea correcto.');   
    } else {
        cargarTexto(legendElement,error,'El campo no puede quedar vacío.');
    }
}

// Funcion para validar si el input contiene un correo con formato correcto
function validateTextMail(elemento,legendElement){
    if (document.getElementById(elemento).value.includes("@gmail.com")) {
        cargarTexto(legendElement,success,'La dirección ingresada cumple el formato correcto');
    } else {
        cargarTexto(legendElement,error,'La dirección de correo electrónico no es valida');
    }
}

// Funcion para validar si el telefono contiene los caracteres y estructura correcta
function validateTextPhone(elemento,legendElement){
    if (document.getElementById(elemento).value.length > 7) {
        if (document.getElementById(elemento).value.includes("-")) {
            cargarTexto(legendElement,success,'El formato del teléfono es correcto');
        } else {
            cargarTexto(legendElement,info,'Debe incluir un guión luego del cuarto dígito.');
        }
    } else {
        cargarTexto(legendElement,error,'Ingrese el número de teléfono completo.');
    }
}