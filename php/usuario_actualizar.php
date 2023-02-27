<?php
//solo es para actualizar estando en su perfil de cuenta propia

require_once("../incluir/session_start.php");
require_once("main.php");

$id=limpiar_cadena($_POST['usuario_id']);

//vericar el usuario si existe en la base de datos

$check_usuario=conexion();
$check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

if($check_usuario->rowCount()<=0){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El usuario no existe en el sistema
    </div>
';
exit();

}else{
    $datos=$check_usuario->fetch(); // esto es para almacenar en un array todos los datos seleccionados anteriormente en la consulta
}

$check_usuario=null;

//validando si cumple con los parametros

$admin_usuario=limpiar_cadena($_POST['administrador_usuario']);
$admin_clave=limpiar_cadena($_POST['administrador_clave']);

if($admin_usuario=="" || $admin_clave==""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado los campos que son obligatorios,que corresponden a su usuario y clave
    </div>
';
exit();
}

if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        su usuario no coincide con su formato solicitado
    </div>
';
exit();
}


if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        su clave no coincide con el formato solicitado
    </div>
';
exit();
}

//verificando el admin

$check_admin=conexion();
$check_admin=$check_admin->query("SELECT usuario_usuario,usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."' ");

if($check_admin->rowCount()==1){
    $check_admin=$check_admin->fetch();

    if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave,$check_admin['usuario_clave'])){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Usuario o clave de administrador incorrectos
        </div>
    ';
    exit();
    }


}else{
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Usuario o clave de administrador incorrectos
    </div>
';
exit();

}
$check_admin=null;



    /*== Almacenando datos del usuario enviados desde el formulario del archivo user_update ==*/
    $nombre=limpiar_cadena($_POST['usuario_nombre']);
    $apellido=limpiar_cadena($_POST['usuario_apellido']);

    $usuario=limpiar_cadena($_POST['usuario_usuario']);
    $email=limpiar_cadena($_POST['usuario_email']);

    $clave_1=limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2=limpiar_cadena($_POST['usuario_clave_2']);


    //verificando campos obligatorios
    if($nombre=="" || $apellido=="" || $usuario==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit(); 
    }

        /*== Verificando integridad de los datos (usuario) ==*/
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
    
        if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El USUARIO no coincide con el formato solicitado
                </div>
            ';
            exit();
        }

        /*== Verificando email ==*/
    if($email!="" && $email!=$datos['usuario_email']){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
            if($check_email->rowCount()>0){
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El correo electrónico ingresado ya se encuentra registrado, por favor elija otro
                    </div>
                ';
                exit();
            }

            $check_email=null;
            
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Ha ingresado un correo electrónico no valido
                </div>
            ';
            exit();
        } 
    }


    /*== Verificando usuario ==*/
    if($usuario!=$datos['usuario_usuario']){
        $check_usuario=conexion();
        $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
            if($check_usuario->rowCount()>0){
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El USUARIO ingresado ya se encuentra registrado, por favor elija otro
                </div>
            ';
            exit();
        }
        $check_usuario=null;
    }


    /*== Verificando claves ==*/
    if($clave_1!="" || $clave_2!=""){
        if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Las CLAVES no coinciden con el formato solicitado
                </div>
            ';
            exit();
        }else{
            if($clave_1!=$clave_2){
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        Las CLAVES que ha ingresado no coinciden
                    </div>
                ';
                exit();
            }else{
                $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
            }
        }
    }else{
        $clave=$datos['usuario_clave'];
    }


    /*== Actualizar datos ==*/
    $actualizar_usuario=conexion();
    $actualizar_usuario=$actualizar_usuario->prepare("UPDATE usuario SET usuario_nombre=:nombre,usuario_apellido=:apellido,usuario_usuario=:usuario,usuario_clave=:clave,usuario_email=:email WHERE usuario_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email,
        ":id"=>$id
    ];

    if($actualizar_usuario->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO ACTUALIZADO!</strong><br>
                El usuario se actualizo con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el usuario, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_usuario=null;