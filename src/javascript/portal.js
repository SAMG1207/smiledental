
let alta =document.getElementById("alta");
alta.style.display ="none";

let baja = document.getElementById("baja");
baja.style.display ="none";

let modificar = document.getElementById("modificar");
modificar.style.display ="none";

let paciente = document.getElementById("paciente");
paciente.style.display="none";

let opciones = [alta, baja, modificar, paciente];

let lista = document.querySelectorAll(".lista");

lista.forEach((elemento, index) => {
elemento.addEventListener("click", function() {
   opciones.forEach((opcion, i) => {
       if (i === index) {
           opcion.style.display = "block";
       } else {
           opcion.style.display = "none";
       }
   });
});
});

var especialidad = document.getElementById("especialidad");
var general = document.getElementById("odGral");
var especial = document.getElementById("odEspecial");
// para cambiar el menu desplegable
especialidad.addEventListener("change",function(){
var valor = especialidad.value;
if(valor === "general"){
  general.classList.remove("non-visible");
  general.classList.add("visible");
  especial.classList.remove("visible");
  especial.classList.add("non-visible");
}else if(valor === "cirugia"||valor === "estetica"|| valor === "endodoncia"){
  especial.classList.remove("non-visible");
  especial.classList.add("visible");
    general.classList.remove("visible");
    general.classList.add("non-visible");
  
}
});

function wellName(nombreV) {
  const regex = /^[^\s<>]+(\s[^\s<>]+)*$/; // Expresión regular para nombres sin caracteres <, >, &
  
  // Verificar el formato del nombre con la expresión regular
  return regex.test(nombreV);
}


// funcion para validar el correo
function validCorreo(correoV) {
  var patronCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return patronCorreo.test(correoV);
}
// funcion para validar el numero
function wellNumber(numberV){
  var regex = /^\d{9}$/;
  return regex.test(numberV);
}
// funcion para validar el numero de colegiado
function wellColegiado(numberV){
  var regex = /^\d{5}$/;
  return regex.test(numberV);
}
// funcion para validar el password
function wellPassword(password) {
  var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^*()?/\\|}{~:¡¿!?]).{8,}$/;
  var invalidChars = /[<>&]/;
  if(invalidChars.test(password)){
    return false;
  }
  return regex.test(password);
}
// funcion para validar el DNI
function wellDni(dniV){
  var patronDni=/^\d{8}[a-zA-Z]$/g;
  var patronNie =/^[XYZxyz]\d{7}[A-Za-z]$/g;
  if(patronDni.test(dniV)||patronNie.test(dniV)){
    return true;
  }else {
    return false;
  }
 }

// Hacemos el cuestionario para incorporar a un nuevo dentista

 let nombreD = document.getElementById("nombreD");
 let apellidoD = document.getElementById("apellidoD");
 let colegiado = document.getElementById("colegiado");
  // contraloms el largo del input
colegiado.addEventListener("input", function() {
  if (this.value.length >= 5) {
    this.maxLength = 5; // Una vez que se alcancen los 5 caracteres, se limita la longitud máxima a 5
  }
});


 let correoD = document.getElementById("correoD");
 let dniD = document.getElementById("dniD");
 let claveD = document.getElementById("claveD");
 let telefonoD = document.getElementById("telefonoD");
 // contraloms el largo del input
 telefonoD.addEventListener("input", function(){
   if(this.value.length >= 9){
    this.maxLength = 9;
   }
 });
 let boton = document.getElementById("boton");
 let formularioA = document.getElementById("formularioA");
  
 boton.addEventListener("click", function(event){
 
  let nombreOk  = wellName(nombreD.value);
  console.log(nombreOk);
  let apellidoOk  = wellName(apellidoD.value);
  console.log(apellidoOk);
  let colegiadoOk  = wellColegiado(colegiado.value);
  console.log(colegiadoOk);
  let correoOk  = validCorreo(correoD.value);
  console.log(correoOk);
  let dniOk  = wellDni(dniD.value);
  console.log(dniOk);
  let claveOk  = wellPassword(claveD.value);
  console.log(claveOk);
  let telefonoOk  = wellNumber(telefonoD.value);
  console.log(telefonoOk);
  
  if(nombreOk &&
    apellidoOk &&
    colegiadoOk &&
    correoOk &&
    dniOk &&
    claveOk &&
    telefonoOk
  ){
    alert("formulario enviado");
  

  } else {
    alert("revise los datos");
    event.preventDefault();

  }
});


 let formularioB = document.getElementById("formularioB");
 let botonB = document.getElementById("botonB");
 botonB.style.display="none";
 let dniBaja = document.getElementById("dniB");
 dniBaja.addEventListener("input", function(){
  let dniBajaValor = dniBaja.value;
  const dniBajaV = wellDni(dniBajaValor);
  if(dniBajaV){
    var xhrN = new XMLHttpRequest();
    xhrN.open("POST", '../Ajax/getDentista.php', true);
    xhrN.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhrN.onreadystatechange = function(){
      if(xhrN.readyState == 4 && xhrN.status == 200){
        console.log(xhrN.responseText);
        var reply = JSON.parse(xhrN.responseText);
        if(reply.registrado){
         botonB.style.display="inline";
        }else{

          botonB.style.display="none";
        }
        xhrN = null;
      }
    };
    xhrN.send('dniM=' + encodeURIComponent(dniBaja.value));
  }else{
    botonB.style.display="none";
  }
  // if(dniBajaV){
  //   botonB.style.display="inline";
  
  // }else{
  //   botonB.style.display="none";
  // }
 });
//________________________Controlamos los valores que se han de cambiar_____________________//

 const botonC = document.getElementById("botonC");
 const modificacionSelect = document.getElementById('modificacion');
 let cambio = document.getElementById("cambio");
 cambio.style.display="none";
 const valorInput = document.querySelector('input[name="valor"]');
 let avisoChange = document.getElementById("avisoChange");


modificacionSelect.addEventListener("change",function(){
  const selectedOption = modificacionSelect.value;
if(selectedOption !==""){
  cambio.style.display ="block";
}else{
  cambio.style.display ="none";

}
   
    switch (selectedOption){
      case "clave":
        valorInput.type = "password";
        valorInput.addEventListener("input",function(){
          if(wellPassword(valorInput.value) && valorInput.value !==""){
            botonC.style.display="block";
            botonC.style.margin="0 auto";
            avisoChange.textContent = "";
            console.log(valorInput.value);
           
          }else{
            botonC.style.display="none";
            avisoChange.textContent = "La contraseña no es lo suficientemente segura";
          }
        })
       
        break;
       case "nombre":
        valorInput.addEventListener("input",function(){
          if(wellName(valorInput.value) && valorInput.value !==""){
            botonC.style.display="block";
            botonC.style.margin="0 auto";
            avisoChange.textContent = "";
            console.log(valorInput.value);
          }else{
            botonC.style.display="none";
            avisoChange.textContent = "Input Inseguro";
          }
        })
        break;
        case "apellido":
        valorInput.addEventListener("input",function(){
          if(wellName(valorInput.value) && valorInput.value !==""){
            botonC.style.display="block";
            console.log(valorInput.value);
            avisoChange.textContent = "";
          }else{
            botonC.style.display="none";
            
            avisoChange.textContent = "Input Inseguro";
          }
        })
        break;
        case "correo":
          valorInput.addEventListener("input",function(){
            if(validCorreo(valorInput.value) && valorInput.value !==""){
              botonC.style.display="block";
              botonC.style.margin="0 auto";
              avisoChange.textContent = "";
              console.log(valorInput.value);
            }else{
              botonC.style.display="none";
              avisoChange.textContent = "Input Inseguro";
            }
          })
          break;
          case "telefono":
            valorInput.addEventListener("input",function(){
              if(wellNumber(valorInput.value) && valorInput.value !==""){
                botonC.style.display="block";
                botonC.style.margin="0 auto";
                avisoChange.textContent = "";
                console.log(valorInput.value);
              }else{
                botonC.style.display="none";
                avisoChange.textContent = "Input Inseguro";
              }
            })
            break;
            case "nro_colegiado":
              valorInput.addEventListener("input",function(){
                if(wellColegiado(valorInput.value) && valorInput.value !==""){
                  botonC.style.display="block";
                  botonC.style.margin="0 auto";
                  avisoChange.textContent = "";
                  console.log(valorInput.value);
                }else{
                  botonC.style.display="none";
                  avisoChange.textContent = "Input Inseguro";
                }
              })
              break;
              default:
          
               avisoChange.textContent = "";
               break;
          }
  });
    

// SABER SI EL DENTISTA ESTA REGISTRADO

let dniM = document.getElementById("dniM");
let verifica = document.getElementById("verifica");
let aviso = document.getElementById("aviso");
let change = document.getElementById("change");
change.style.display="none";
let registrado = false;

dniM.addEventListener("input", function(){
dniValor = dniM.value;
if(wellDni(dniValor)){
var xhr = new XMLHttpRequest();
xhr.open("POST", '../Ajax/getDentista.php', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.onreadystatechange = function(){
  if(xhr.readyState == 4 && xhr.status == 200){
    
    var respuesta = JSON.parse(xhr.responseText);
   
    if(respuesta.registrado){
      aviso.innerText = "Dentista registrado";
      change.style.display="block";
    }else{
      aviso.innerText = "Dentista no registrado";
      change.style.display="none";
    }
    xhr = null;
  }
};
xhr.send('dniM=' + encodeURIComponent(dniValor));
}
aviso.innerText ="not on my watch"
change.style.display="none";
});

let botonD = document.getElementById("botonD");
let patient = document.getElementById("paciente");
botonD.style.display = "none";

patient.addEventListener("input", function(){
  let patientCorrecto = wellName(patient.value);
  if(patientCorrecto){
    console.log(patientCorrecto);
    botonD.style.display="inline";
  }else{
    botonD.style.display="none";
  }
});

