<?php

require_once("main.php");

//en esta variable se guarda lo que se esta enviando desde el formulario del archivo product_img
$product_id=limpiar_cadena($_POST['img_up_id']);

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


        #comprobar si se selecciono la imagen#
        if($_FILES['producto_foto']['name']=="" || $_FILES['producto_foto']['size']==0){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se ha seleccionado nunguna imagen valida!
            </div>
            ';
            exit();

        }

        #define el directorio de imagenes#
    $img_dir="../img/producto/";

        #creando directorio#
        if(!file_exists($img_dir)){
            if(!mkdir($img_dir,0777)){
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se puede crear el directorio!
                </div>
                ';
                exit();
            }
        }

        chmod($img_dir,0777); // esta funcion es para darle a la direccion permisos de lectura y escritura

        #veiricando formato de imagenes#
        if(mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png" ){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El formato de la imagen no esta permitido!
            </div>
            ';
            exit();
        }

        #verficando peso de la imagen#
        if(($_FILES['producto_foto']['size']/1024)>3072){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El Tamaño de la imagen supera el peso permitido de 3MB!
            </div>
            ';
            exit();
            
        }

        
        #extension de la imagem#

        switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
            case'image/jpeg':
                $img_ext=".jpg";
                break;

                case'image/png':
                    $img_ext=".png";
                    break;
        }

        //renombre el nombre de la imagen
        $img_nombre=renombrar_fotos($datos['producto_nombre']);
        $foto=$img_nombre.$img_ext;

        #moviendo la imagen al directorio#
        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen no se ha podido cargar al sistema en este momento!
            </div>
            ';
            exit();
        }

        #aqui vamos a eliminar la imagen anterior#

        if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto']!=$foto){

            chmod($img_dir.$datos['producto_foto'],0777);
            unlink($img_dir.$datos['producto_foto']);

        }

                /*== Actualizar datos ==*/
                $actualizar_producto=conexion();
                $actualizar_producto=$actualizar_producto->prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id");
                
                $marcadores=[
                    ":foto"=>$foto,
                    ":id"=>$product_id
                ];
        
                if($actualizar_producto->execute($marcadores)){
                    echo '
                        <div class="notification is-success is-light">
                            <strong>¡iMAGEN O FOTO ACTUALIZADA!</strong><br>
                            La IMAGEN se actualizo con exito,pulse aceptar para recargar los cambios!
        
                            <p class="has-text-centered pt-5 pb-5">
                                <a class=" button is-link is-rounded" href="index.php?vista=product_img&product_id_up='.$product_id.'">Aceptar</a>
                            </p>
                        </div>
                    ';
                }else{
                    if(is_file($img_dir.$foto)){
                        chmod($img_dir.$foto,0777);
                        unlink($img_dir.$foto);
                    }
                    echo '
                        <div class="notification is-warning is-light">
                            <strong>¡Ocurrio un error!</strong><br>
                            No podemos subir la imagen en este momento, Por favor intente nuevamente!
        
                        </div>
                    ';
                }
                $actualizar_producto=null;



?>
