// Clase para el control de eventos dentro de las paginas web

// Colocamos en una constante los input a los que queremos aplicar el efecto
/*const inputs = document.querySelectorAll('.input');

// Al deseleccionar un input el placeholder cambiara de lugar
const focusFunc = () => {
    let parent = this.parentNode.parentNode;
    parent.parentNode.classList.add('focus');
}

// Al deseleccionar un input el placeholder cambiara de lugar
const blurFunc = () => {
    if (this.value == "") {
        this.parentNode.parentNode.classList.remove('focus');
    }

}

inputs.forEach(input => {
    input.addEventListener('focus', focusFunc);
    input.addEventListener('blur', blurFunc);
})*/
///
const inputs = document.querySelectorAll('.input');

function focusFunc(){
    let parent = this.parentNode.parentNode;
    parent.classList.add('focus');
}

function blurFunc(){
    let parent = this.parentNode.parentNode;
    if(this.value== "") {
        parent.classList.remove('focus');
    }

}

inputs.forEach(input => {
    input.addEventListener('focus', focusFunc);
    input.addEventListener('blur', blurFunc);
})