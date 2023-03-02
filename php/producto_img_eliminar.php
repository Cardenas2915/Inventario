<?php

require_once("main.php");

//en esta variable se guarda lo que se esta enviando desde el formulario del archivo product_img
$product_id=limpiar_cadena($_POST['img_del_id']);

//vericar el usuario si existe en la base de datos

$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id'");

if($check_producto->rowCount()==1){

    $datos=$check_producto->fetch(); // esto es para almacenar en un array todos los datos seleccionados anteriormente en la consulta

}else{
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La IMAGEN del producto no existe en el sistema!
    </div>
';
exit();
}

$check_producto=null;

    #define el directorio de imagenes#
    $img_dir="../img/producto/";
    chmod($img_dir,0777); // esta funcion es para darle a la direccion permisos de lectura y escritura

    if(is_file($img_dir.$datos['producto_foto'])){
        chmod($img_dir.$datos['producto_foto'],0777);

        if(!unlink($img_dir.$datos['producto_foto'])){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La IMAGEN no se pudo eliminar!
            </div>
        ';
        exit();
        }
    }

    
        /*== Actualizar datos ==*/
        $actualizar_producto=conexion();
        $actualizar_producto=$actualizar_producto->prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id");
        
        $marcadores=[
            ":foto"=>"",
            ":id"=>$product_id
        ];

        if($actualizar_producto->execute($marcadores)){
            echo '
                <div class="notification is-info is-light">
                    <strong>¡iMAGEN O FOTO ELIMINADA!</strong><br>
                    La IMAGEN se elimino con exito,pulse aceptar para recargar los cambios!

                    <p class="has-text-centered pt-5 pb-5">
                        <a class=" button is-link is-rounded" href="index.php?vista=product_img&product_id_up='.$product_id.'">Aceptar</a>
                    </p>
                </div>
            ';
        }else{
            echo '
                <div class="notification is-warning is-light">
                    <strong>¡iMAGEN O FOTO ELIMINADA!</strong><br>
                    ocurrieron algunos incovenientes,sin embargo la imagen del producto ha sido eliminada, pulse aceptar para recargar los cambios!

                    <p class="has-text-centered pt-5 pb-5">
                        <a class=" button is-link is-rounded" href="index.php?vista=product_img&product_id_up='.$product_id.'">Aceptar</a>
                    </p>
                </div>
            ';
        }
        $actualizar_producto=null;

        ?>

