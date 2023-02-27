<?php

require_once("main.php");

    /*== Almacenando datos del usuario enviados desde el formulario del archivo category_new ==*/
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


     #guardando datos #
    $guardar_categoria=conexion();
    // $guardar_categoria=$guardar_categoria->query("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) VALUES('$nombre','$apellido','$usuario','$clave','$email')"); //este metodo es el comun insertado mediante query
    $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) VALUES(:nombre,:ubicacion)"); //con este metodo se evita la inyeccion sql

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion
    ];

    $guardar_categoria->execute($marcadores);

    if($guardar_categoria->rowCount()==1){
        echo '
        <div class="notification is-success is-light">
            <strong>¡Categoria Registrada!</strong><br>
            La categoria se registro correctamente!
        </div>
        ';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Categoria no registrada, intente nuevamente
        </div>
        ';
    }
    $guardar_categoria=null;