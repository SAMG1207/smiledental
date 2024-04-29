let formulario = document.getElementById("formulario");

setTimeout(function() {

  formulario.style.opacity = "1"; 
}, 800);

// Agrega un controlador de eventos al elemento close
let iconos = document.querySelectorAll(".icono-vc");
//Esto para ver las claves en los formularios si pasamos por encima del icono
iconos.forEach(function(icono) {
  icono.addEventListener("mouseover", function() {
    const inputElement = icono.previousElementSibling;
    if (inputElement) {
      inputElement.type = "text";
    }
  });
});

iconos.forEach(function(icono) {
  icono.addEventListener("mouseout", function() {
    const inputElement = icono.previousElementSibling;
    if (inputElement) {
      inputElement.type = "password";
    }
  });
});
 
if(document.getElementById("aviso")){
    formulario.style.display="none";
    
}





