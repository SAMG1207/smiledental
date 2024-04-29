//PARA MOSTRAR LOS ELEMENTOS EN LA TABLA
const mainCitas = document.getElementById("main-citas");
mainCitas.style.display = "none";

const mainPacientes = document.getElementById("main-pacientes");
mainPacientes.style.display = "none";

const mainFechas = document.getElementById("main-fechas");
mainFechas.style.display = "none";

let lista = document.querySelectorAll(".lista");
let opciones = [mainCitas, mainPacientes, mainFechas];

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

//RESERVA DE CITAS

let first = document.getElementById("first");
   let fechaEmergencia = document.getElementById("fecha-emergencia");
   let avisoFecha =document.getElementById("avisoFecha");

let second = document.getElementById("second");
   let dniEm = document.getElementById("dni-emergencia");
   let avisoEm = document.getElementById("avisoEmergencia");
let third = document.getElementById("third");
   let horaCita = document.getElementById("hora-cita");

let fourth = document.getElementById("fourth");
   let drCita = document.getElementById("dr-cita");
   let nombreDr = document.querySelectorAll(".dCita");

let fifth = document.getElementById("fifth");

//CODIGO para la reserva de citas de emergencia: 
second.style.display = "none";
third.style.display ="none";
fourth.style.display="none"
fifth.style.display="none"
let correctaFecha = false;
fechaEmergencia.addEventListener("input", function(){
  
  var fechaEscogida = new Date(fechaEmergencia.value);
 //Aseguramos que no sea en fin de semana
  var diaEscogido =fechaEscogida.getDay();
  if(diaEscogido === 0 || diaEscogido ===6){
     diaEscogido="";
     correctaFecha=false
     avisoFecha.textContent = "Debe escoger dia de semana";

  }else{
    avisoFecha.textContent="";
    correctaFecha=true;
  }
  if(correctaFecha===true){
    second.style.display="block";
  }else{
    second.style.display = "none";
  }
});
// validamos el dni 
function validarDNI(dni) {
    const formatoDNI = /^[0-9]{8}[a-zA-Z]$/;
    const formatoNIE =/^[YXYZ]\d{7}[A-Za-z]$/;
    if(formatoDNI.test(dni)){
        return true;
    }else{
        return formatoNIE.test(dni);
    }
}
// Evaluamos si el paciente está registrado
dniEm.addEventListener("input", function(){
    let dniEmValue = dniEm.value;
    if(validarDNI(dniEmValue)){
        let xhrD = new XMLHttpRequest();
        xhrD.open("POST", "../Ajax/getPacientes.php", true);
        xhrD.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrD.onreadystatechange = function (){
            if (xhrD.readyState == 4 && xhrD.status == 200){
                var response1 = JSON.parse(xhrD.responseText);
                if(response1.registrado){
                    avisoEm.innerText="Registrado";
                    third.style.display = "block";
                }
                else{
                    avisoEm.innerText="No registrado";
                    third.style.display = "none";
                }
                xhrD=null;
            }
        }
        
        xhrD.send('dni=' + encodeURIComponent(dniEmValue));
       
    }else{
        dniEmValue = "";
        avisoEm.innerText="Formato no válido";
        third.style.display = "none";
    }  
});
// Escogemos la cita
horaCita.addEventListener("change", function(){
    drCita.selectedIndex = -1; // Esto eliminará la selección actual
    fourth.style.display = "block";

    if(horaCita.value < 12){
    
     nombreDr[0].style.display="block";
     nombreDr[1].style.display="block";
     nombreDr[2].style.display="none";
     nombreDr[3].style.display="none";
    }
    else if(horaCita.value>13 && horaCita.value<16){
        nombreDr[0].style.display="block";
        nombreDr[1].style.display="block";
        nombreDr[2].style.display="block";
        nombreDr[3].style.display="block";
     
    }
    else if(horaCita.value >15){
     nombreDr[0].style.display="none";
     nombreDr[1].style.display="none";
     nombreDr[2].style.display="block";
     nombreDr[3].style.display="block";

    }

    fourth.style.display = "block"
})

drCita.addEventListener("input", function(){
    fifth.style.display ="block";
})


//PROGRAMACIÓN CITAS ESPECIALISTA:

var aviso = document.getElementById("aviso");
var dniInput = document.getElementById("dni");
var resultado = document.getElementById("resultado");
var verificarForm = document.getElementById("verificar-form");
var fechaCita = document.getElementById("fecha-cita");
var espec = document.getElementById("espec");
espec.style.display = "none";


verificarForm.addEventListener('click', function (event) {
    event.preventDefault();
    var reg = false;
    var dni = dniInput.value;
    var xhr = new XMLHttpRequest();

    xhr.open('POST', '../Ajax/getPacientes.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.registrado) {
              
                resultado.innerHTML = '<p style="color: green;">El paciente está registrado.</p>';
                espec.style.display = "block";
            } else {
                
                resultado.innerHTML = '<p style="color: red;">El paciente no está registrado.</p>';
                espec.style.display = "none";
            }
            xhr = null;
        }
    }

    xhr.send('dni=' + encodeURIComponent(dni));
});
var tipo = document. getElementById("tipo");
var divFecha = document.getElementById("fecha");
var dateInput = document.getElementById("fecha-cita");
var dia = document.getElementById("dia");
var dtToday = new Date();
var hora=  dtToday.getHours();
var month = dtToday.getMonth() + 1;
var day = dtToday.getDate();
var year = dtToday.getFullYear();
    if (month < 10)
        month = '0' + month.toString();
    if (day < 10)
        day = '0' + day.toString();
    
    var minDate = year + '-' + month + '-' + day;
    dateInput.setAttribute("min", minDate);
    var newmonth;
    if(month<=9){
      newmonth = month + 3;
    }else{
        newmonth = month - 9
        year++;
        if (newmonth < 1) {
    newmonth = 12 - (Math.abs(newmonth) % 12);
    year--;}
}     
if (newmonth < 10) {
    newmonth = '0' + newmonth.toString();
}
    var maxDate = year + '-' + newmonth + '-' + day;
    dateInput.setAttribute("max", maxDate);
    var dias = document.querySelectorAll(".dias");
// divFecha.style.display = "none";
//PARA SELECCIONAR EL TIPO DE ESPECIALIDAD
//ESTA no es la mejor manera de obtener estos datos, el desarrollador está consciente de esto
let horaEntrada = 0;
let horaSalida = 0;
let idDentista2;
tipo.addEventListener("change", function(){
    divFecha.style.display = "block";
//vemos las disponibilidades de los dentistas especialistas
    switch(tipo.value){
        case "cirugia":
            dias[0].innerText = "Lunes";
            dias[0].value = 1;
           dias[1].innerText = "Jueves";
           dias[1].value = 4;   
           horaEntrada = 13;
           horaSalida = 21;
         break;

         case "estetica":
            dias[0].innerText = "Martes";
            dias[0].value = 2;
           dias[1].innerText = "Miércoles";
           dias[1].value = 3;
           horaEntrada = 12;
           horaSalida = 20;
         break;

         case "endodoncia":
            dias[0].innerText = "Jueves";
            dias[0].value = 4;
           dias[1].innerText = "Viernes";
           dias[1].value = 5;
           horaEntrada = 8;
           horaSalida = 16;
         break;

         default: 
         preventDefault();
         break;

        }
        idDentista2 = tipo.selectedOptions[0].getAttribute("name"); //la especialidad del dentista
        console.log(idDentista2);
        console.log(horaEntrada);
    });

    dia.addEventListener("change", function(){
    dateInput.value ="";
    });
// Sedebe escoger el día que trabaja el dentista, si trabaja los lunes debe ser lunes entones la fecha escogida
    dateInput.addEventListener("input", function () {
    var selectedDate = new Date(this.value);
    var formattedSelectedDate = selectedDate.toISOString().split('T')[0]; // Formatear a 'YYYY-MM-DD'
    var selectedDay = selectedDate.getDay();
    var selectedDayOption = parseInt(dia.selectedOptions[0].value);
    console.log(selectedDay, selectedDayOption);
   
    if (selectedDay !== selectedDayOption) {
        this.value = "";
        aviso.innerText = "La fecha no coincide con el día seleccionado.";
        aviso.classList.remove("non-alert");
        aviso.classList.add("alert");
    } else {
        aviso.innerText = "";
        aviso.classList.remove("alert");
        aviso.classList.add("non-alert");
    }

    console.log(idDentista2);
    
//aqui vemos las horas que tiene cada dentista en el día
    var xhr = new XMLHttpRequest();
xhr.open('POST', '../Ajax/getEspecialista.php', true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

var data = 'fecha=' + formattedSelectedDate + '&id_dentista=' + idDentista2;
var hours = document.getElementById("hours");
hours.innerHTML="";
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        var horasOcupadas = JSON.parse(xhr.responseText);

        console.log(horasOcupadas[0]);
        for(var i = horaEntrada; i<horaSalida; i++){
            
            if(parseInt(horasOcupadas[0])===i){
                continue;
            }else{
                let option = document.createElement("option");
                option.value = i;
                option.innerText =i+":00";
                hours.append(option);

            } 
         xhr = null;  
        }
    }
};
xhr.send(data);
   
    });

    //VER CITAS POR PACIENTES__________________________________________________
    let dniMainPacientes = document.getElementById("dniMainPacientes");
    let verificaMainPacientes = document.getElementById("verificaMainPacientes");
    let parrafoMainPacientes = document.getElementById("parrafoVerifica");
    let tabla = document.getElementById("tabla");
    tabla.style.display ="none";
    let registered = false;

// Verificamos que el paciente este registrado esto puede resumirse en una función
    verificaMainPacientes.addEventListener("click", function(){
      var dniVerificado = dniMainPacientes.value;
      parrafoMainPacientes.textContent="";
      if(validarDNI(dniVerificado)===true){
        var xhrV = new XMLHttpRequest();
        xhrV.open('POST', '../Ajax/getPacientes.php', true);
        xhrV.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrV.onreadystatechange = function(){
          if(xhrV.readyState == 4 && xhrV.status == 200){
              var responseVe = JSON.parse(xhrV.responseText);
              if (responseVe.registrado) {
                  parrafoMainPacientes.innerHTML = '<p style="color: green;">El paciente está registrado.</p>';
                  tabla.style.display = "block";
                  registered = true;
  
                  if(registered === true){
                      var xhrTabla = new XMLHttpRequest();
                      xhrTabla.open('POST', '../Ajax/getCitas.php', true);
                      xhrTabla.onreadystatechange = function(){
                          if(xhrTabla.readyState == 4 && xhrTabla.status == 200){
                              var responseTabla = JSON.parse(xhrTabla.responseText);
                              if (responseTabla.registrado) { // Aquí se usa el atributo 'registrado' de la respuesta de getCitas.php
                                  var tablaGenerada = responseTabla.fechas;
                                  var contenedorTabla = document.getElementById('contenedorTabla');
                                  contenedorTabla.innerHTML = '';
                                  if(tablaGenerada && tablaGenerada.length > 0){
                                      document.getElementById('contenedorTabla').innerHTML = JSON.stringify(tablaGenerada);
                                     // Se crea la tabla con los datos de las citas por paciente
                                      var tableHTML = '<table class="m-2"><thead><tr><th class="p-2">ID Cita</th><th class="p-2">ID Paciente</th><th class="p-2">ID Dentista</th><th class="p-2">Fecha</th><th class="p-2">Hora</th></tr></thead><tbody>';
                                      for (var i = 0; i < tablaGenerada.length; i++) {
                                          var cita = tablaGenerada[i];
                                          tableHTML += '<tr>';
                                          tableHTML += '<td class="border border-primary border-2">' + cita.id_cita + '</td>';
                                          tableHTML += '<td class="border border-primary border-2">' + cita.id_paciente + '</td>';
                                          tableHTML += '<td class="border border-primary border-2">' + cita.id_dentista + '</td>';
                                          tableHTML += '<td class="border border-primary border-2">' + cita.fecha + '</td>';
                                          tableHTML += '<td class="border border-primary border-2">' + cita.hora + '</td>';
                                          tableHTML += '</tr>';
                                      }
                                      
                                      tableHTML += '</tbody></table>';
                                      contenedorTabla.innerHTML = tableHTML;
                                  }else{
  
                                  }xhrTabla = null;
                                
                              }
                          }
                      };
                      xhrTabla.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                      xhrTabla.send('dni='+ encodeURIComponent(dniVerificado));
                  }
              } else {
                  parrafoMainPacientes.innerHTML = '<p style="color: red;">El paciente no está registrado.</p>';
                  tabla.style.display = "none";
                  registered = false;
              } xhrV = null;
          }
        }
        xhrV.send('dni=' + encodeURIComponent(dniVerificado));
        
      }else{
      
        parrafoMainPacientes.textContent="El DNI no tiene formato válido"
        tabla.style.display = "none";
        contenedorTabla.innerHTML = '';
        verificaMainPacientes.preventDefault();
      }
  
    });

    //VER CITAS POR FECHA-------------------------------------------------------------------
    let inputFecha1 = document.getElementById("dateApp");
    let lufd = document.getElementById("lookUpForDate");
    let parrfoFecha = document.getElementById("parrafoFecha");
    let tablaFacha = document.getElementById("tablaFecha");
    tablaFacha.style.display = "none";
    let anyAppOnThatDate = false;
    
    lufd.addEventListener("click", function(){
        var inputFecha = inputFecha1.value; 
        let xhrF = new XMLHttpRequest();
        xhrF.open('POST', '../Ajax/getFechas.php', true);
        xhrF.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrF.onreadystatechange = function(){
            if(xhrF.readyState == 4 && xhrF.status == 200){
                var responseFe = JSON.parse(xhrF.responseText);
                if(responseFe.registrado){
                    var tableDates = responseFe.fechas;
                    var contenedorTableDates = document.getElementById("contenedorTablaFecha");
                    contenedorTableDates.innerHTML = "";
    
                    if(tableDates && tableDates.length > 0){
                        var tableHTMLFecha = '<table class="m-2"><thead><tr><th class="p-2">ID Cita</th><th class="p-2">ID Paciente</th><th class="p-2">ID Dentista</th><th class="p-2">Fecha</th><th class="p-2">Hora</th></tr></thead><tbody>';
                          // con los resultados imprimimos una tabla con los datos de la cita por fecha
                        for (var i = 0; i < tableDates.length; i++) {
                            var cita = tableDates[i];
                            tableHTMLFecha += '<tr>';
                            tableHTMLFecha += '<td class="border border-primary border-2">' + cita.id_cita + '</td>';
                            tableHTMLFecha += '<td class="border border-primary border-2">' + cita.id_paciente + '</td>';
                            tableHTMLFecha += '<td class="border border-primary border-2">' + cita.id_dentista + '</td>';
                            tableHTMLFecha += '<td class="border border-primary border-2">' + cita.fecha + '</td>';
                            tableHTMLFecha += '<td class="border border-primary border-2">' + cita.hora + '</td>';
                            tableHTMLFecha += '</tr>';
                        }
                        tableHTMLFecha += '</tbody></table>';
                        contenedorTableDates.innerHTML = tableHTMLFecha;
                    } else {
                        contenedorTableDates.innerHTML = "<p style ='color: red'> No hay citas para esta fecha </p>";
                    } xhrF = null;
                }
            }
        };
        xhrF.send('fecha=' + encodeURIComponent(inputFecha));
    });
    

