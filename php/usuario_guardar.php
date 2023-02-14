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
            $check_email=null;

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