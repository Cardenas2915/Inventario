<?php

require_once("main.php");

//en esta variable se guarda lo que se esta enviando desde el formulario del archivo category_update
$id=limpiar_cadena($_POST['categoria_id']);

//vericar el usuario si existe en la base de datos

$check_categoria=conexion();
$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");

if($check_categoria->rowCount()<=0){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La categoria no existe en el sistema
    </div>
';
exit();

}else{
    $datos=$check_categoria->fetch(); // esto es para almacenar en un array todos los datos seleccionados anteriormente en la consulta
}

$check_categoria=null;

    /*== Almacenando datos del usuario enviados desde el formulario del archivo category_update ==*/
    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

    //verificando campos obligatorios
    if($nombre==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado los campos que son obligatorios
            </div>
        ';
        exit(); 
    }

    /*== Verificando integridad de los datos (usuario) ==*/
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

if($ubicacion!=""){

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La ubcicacion no coincide con el formato solicitado
        </div>
    ';
    exit();
}

}

    #Verfificando si el nombre de la categoria exite en la base de datos#
    if($nombre != $datos['categoria_nombre']){

        $check_nombre = conexion();// se abre la conexion en el archivo main
        $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");// se hace la consulta en la base de datos 
    
        if($check_nombre->rowCount()>0){ //rowCount nos devuleve cuantos registros se selecciono en la consulta de la base de datos y se hace la validacion
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro!
            </div>
            ';
            exit();
        }
    
        $check_nombre=null;
    }
    

    /*== Actualizar datos ==*/
    $actualizar_categoria=conexion();
    $actualizar_categoria=$actualizar_categoria->prepare("UPDATE categoria SET categoria_nombre=:nombre,categoria_ubicacion=:ubicacion WHERE categoria_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion,
        "id"=>$id
    ];

    if($actualizar_categoria->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO ACTUALIZADO!</strong><br>
                La categoria se actualizo con exitó
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar la CATEGORIA, por favor intente nuevamente!
            </div>
        ';
    }
    $actualizar_categoria=null;