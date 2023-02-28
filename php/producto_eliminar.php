<?php


$product_id_del=limpiar_cadena($_GET['product_id_del']);


#verficando si el producto si existe en la base de datos #
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id_del'");

if($check_producto->rowCount()==1){
    
    $datos=$check_producto->fetch();

    $eliminar_producto=conexion();
    $eliminar_producto=$eliminar_producto->prepare("DELETE FROM producto WHERE producto_id=:id");

    $eliminar_producto->execute([":id"=>$product_id_del]);

    if($eliminar_producto->rowCount()==1){

        if(is_file("./img/producto/".$datos['producto_foto'])){

            chmod("./img/producto/".$datos['producto_foto'],0777);   //esta funcion es para darle permisos de lectura y ecritura a este archivo
            unlink("./img/producto/".$datos['producto_foto']); //funcion para eliminar el archivo
        }

        echo '
        <div class="notification is-info is-light">
            <strong>¡Producto Eliminado!</strong><br>
            Los Productos se eliminaron correctamente!
        </div>
    ';

    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo eliminar el producto, Por favor intente nuevamente!
        </div>
    ';
    }
    $eliminar_producto=null;

}else{
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El producto no existe!
    </div>
';
}
$check_producto=null;