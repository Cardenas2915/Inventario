<?php
require_once("../incluir/session_start.php");
require_once("main.php");


//almacenando datos de los inputs enviados desde el formulario del archivo product_new
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);
$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);



#verificando campos obligatorios#
if($nombre=="" || $codigo=="" || $precio=="" || $stock=="" || $categoria==""){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}


#Verficando integridad de los datos del formulario# la funcion verificar datos es llamado mediante el required en el archivo main donde ya esta establecida

if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El CODIGO de BARRAS no coincide con el formato solicitado
    </div>
';
exit();
}

if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato solicitado
    </div>
';
exit();
}


if(verificar_datos("[0-9.]{1,25}",$precio)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El PRECIO no coincide con el formato solicitado
    </div>
';
exit();
}

if(verificar_datos("[0-9]{1,25}",$stock)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El STOCK no coincide con el formato solicitado
    </div>
';
exit();
}


#Verfificando el CODIGO DE BARRAS#

$check_codigo = conexion();// se abre la conexion en el archivo main
$check_codigo = $check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");// se hace la consulta en la base de datos 

    if($check_codigo->rowCount()>0){ //rowCount nos devuleve cuantos registros se selecciono en la consulta de la base de datos y se hace la validacion
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El CODIGO DE BARRAS ingresado ya se encuentra registrado, por favor elija otro!
        </div>
        ';
        exit();
    }
    $check_codigo=null;


    
#Verfificando el nombre#

$check_nombre = conexion();// se abre la conexion en el archivo main
$check_nombre = $check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre='$nombre'");// se hace la consulta en la base de datos 

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


    #Verfificando la categoria#

$check_categoria = conexion();// se abre la conexion en el archivo main
$check_categoria = $check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");// se hace la consulta en la base de datos 

    if($check_categoria->rowCount()<=0){ //rowCount nos devuleve cuantos registros se selecciono en la consulta de la base de datos y se hace la validacion
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La CATEGORIA seleccionada no existe!
        </div>
        ';
        exit();
    }
    $check_categoria=null;



    #define el directorio de imagenes#
    $img_dir="../img/producto/";

    #comprobar si se selecciono la imagen#

    if($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size']>0){
        
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

            chmod($img_dir,0777); // esta funcion es para darle a la direccion permisos de lectura y escritura

            $img_nombre=renombrar_fotos($nombre);
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
    }else{
        $foto="";
    }


    #guardando fotos#
    $guardar_producto=conexion();
    $guardar_producto=$guardar_producto->prepare("INSERT INTO producto(producto_codigo,producto_nombre,producto_precio,producto_stock,producto_foto,categoria_id,usuario_id) VALUES(:codigo,:nombre,:precio,:stock,:foto,:categoria,:usuario)");

    $marcadores=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock,
        ":foto"=>$foto,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id']
    ];
    $guardar_producto->execute($marcadores);

    if($guardar_producto->rowCount()==1){
        echo '
        <div class="notification is-success is-light">
            <strong>¡Registro Exitoso!</strong><br>
            El PRODUCTO se registro correctamente!
        </div>
        ';
    }else{
        if(is_file($img_dir.$foto)){ //is_file nos devuelve si el archivo existe
            chmod($img_dir,0777); // esta funcion es para darle a la direccion permisos de lectura y escritura
            unlink($img_dir.$foto); //esta funcion es para eliminar una imagen
        }
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Usuario no registrado, intente nuevamente
        </div>
        ';
    }
    $guardar_producto=null;
