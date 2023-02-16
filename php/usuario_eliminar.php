<?php

$user_id_del=limpiar_cadena($_GET['user_id_del']);



#verficando usuario #
$check_usuario=conexion();
$check_usuario=$check_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id='$user_id_del'");



if($check_usuario->rowCount()==1){  //devuelve cuantos registros se han seleccionado con la consulta

    #verficandovsi el usuario tiene productos registrados #
$check_productos=conexion();
$check_productos=$check_productos->query("SELECT usuario_id FROM producto WHERE usuario_id='$user_id_del'LIMIT 1");

if($check_productos->rowCount()==0){

    $eliminar_usuario=conexion();
    $eliminar_usuario=$eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id=:id");

    $eliminar_usuario->execute([":id"=>$user_id_del]);

    if($eliminar_usuario->rowCount()==1){
        echo '
        <div class="notification is-info is-light">
            <strong>¡Usuario Eliminado!</strong><br>
            Los datos del usuario se eliminaron correctamente!
        </div>
    ';

    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo eliminar el usuario, Por favor intente otraves!
        </div>
    ';
    }

    $eliminar_usuario=null;

}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡No se pudo eliminar!</strong><br>
            El USUARIO que intenta eliminar tiene un producto registrado!
        </div>
    ';
    
}

}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO que intenta eliminar no existe!
        </div>
    ';
    
}
$check_usuario=null;