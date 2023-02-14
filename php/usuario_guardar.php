<?php
require_once("main.php");


//almacenando datos de los inputs enviados desde el formulario del archivo user_new
$nombre = limpiar_cadena($_POST['usuario_nombre']);
$apellido = limpiar_cadena($_POST['usuario_apellido']);
$usuario = limpiar_cadena($_POST['usuario_usuario']);
$email = limpiar_cadena($_POST['usuario_email']);
$clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
$clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

#verificando campos obligatorios#

if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2==""){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

#Verficando integridad de los datos del formulario# la funcion verificar datos es llamado mediante el required en el archivo main donde ya esta establecida

if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato solicitado
    </div>
';
exit();
}

if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El APELLIDO no coincide con el formato solicitado
    </div>
';
exit();
}

if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La clave no coincide con el formato solicitado
    </div>
';
exit();
}

#verificando el correo desde la base de datos#

if($email != ""){

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

        $check_email = conexion();
        $check_email = $check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");

            if($check_email->rowCount()>0){ //rowCount nos devuleve cuantos registros se selecciono en la consulta de la base de datos y se hace la validacion
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El CORREO ingresado ya se encuentra registrado, por favor elija otro!
                </div>
                ';
                exit();
            }
            $check_email=null; //esta funcion lo que hace es cerrar la conexion  que se abrio en la linea 63

    }else{

        echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El EMAIL no es valido!
    </div>
    ';
    exit();

    }

}

#Verfificando el usuario#

$check_usuario = conexion();// se abre la conexion en el archivo main
$check_usuario = $check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");// se hace la consulta en la base de datos 

    if($check_usuario->rowCount()>0){ //rowCount nos devuleve cuantos registros se selecciono en la consulta de la base de datos y se hace la validacion
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO ingresado ya se encuentra registrado, por favor elija otro!
        </div>
        ';
        exit();
    }
    $check_usuario=null;


    #verificando que las claves coincidan#
    if($clave_1 != $clave_2){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las CONTRASEÑAS no coinciden
        </div>
        ';
        exit();
    }else{
        $clave=password_hash($clave_1, PASSWORD_BCRYPT,["cost"=>10]); //esta funcion es para encriptar la contraseña
    }

    #guardando datos#

    $guardar_usuario=conexion();
    // $guardar_usuario=$guardar_usuario->query("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) VALUES('$nombre','$apellido','$usuario','$clave','$email')"); //este metodo es el comun insertado mediante query
    $guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) VALUES(:nombre,:apellido,:usuario,:clave,:email)"); //con este metodo se evita la inyeccion sql

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email
    ];
    $guardar_usuario->execute($marcadores);

    if($guardar_usuario->rowCount()==1){
        echo '
        <div class="notification is-success is-light">
            <strong>¡Registro Exitoso!</strong><br>
            El usuario se registro correctamente!
        </div>
        ';
    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Usuario no registrado, intente nuevamente
        </div>
        ';
    }
    $guardar_usuario=null;