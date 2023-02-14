const formularios_ajax=document.querySelectorAll(".formularioAjax");

function enviar_formulario_ajax(e){
    
    e.preventDefault(); //aqui prevenimos el evento por default que lo que hace es cuando enviamos un formulario se redirije al archivo que le estamos dando mediante en el form que es el action

    let enviar = confirm("quieres enviar el formulario"); //el confirm ejecuta una ventana donde aparece que si quieres enviar el formulario

    if(enviar == true){
        
        let data = new FormData(this);// aqui vamos a crear datos los datos enviados desde el formulario
        let method=this.getAttribute("method");//esta variale guarda el metodo de envio del formulario
        let action=this.getAttribute("action");//esta variable guarda la url donde enviamos el formulario

        let encabezados = new Headers();//esta variable crea los encabezados

        let config ={
            method:method,
            Headers:encabezados,
            mode:"cors",
            cache:'no-cache',
            body:data
        };


        fetch(action,config) 
        .then(respuesta => respuesta.text())//almacena lo que envia el formulario y la formatea en texto plano
        .then(respuesta =>{
            let contenedor=document.querySelector(".form-rest");
            contenedor.innerHTML = respuesta;
        });

    }
}

formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit", enviar_formulario_ajax); //cuando un formulario se envie mediante submit se ejecutara la funcion que se llama enviar_formulario_ajax
});