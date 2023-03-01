<?php

require_once("main.php");

//en esta variable se guarda lo que se esta enviando desde el formulario del archivo category_update
$id=limpiar_cadena($_POST['producto_id']);

//vericar si el producto si existe en la base de datos
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$id'");

if($check_producto->rowCount()<=0){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El producto no existe en el sistema
    </div>
';
exit();

}else{
    $datos=$check_producto->fetch(); // esto es para almacenar en un array todos los datos seleccionados anteriormente en la consulta
}

$check_producto=null;

//almacenando datos de los inputs enviados desde el formulario del archivo product_actualizar
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

if($codigo != $datos['producto_codigo']){
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
}

    
#Verfificando el nombre#

if($nombre != $datos['producto_nombre']){
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
}

    #Verfificando la categoria#
if($categoria != $datos['categoria_id']){

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
    }

        /*== Actualizar datos ==*/
        $actualizar_producto=conexion();
        $actualizar_producto=$actualizar_producto->prepare("UPDATE producto SET producto_codigo=:codigo,producto_nombre=:nombre,producto_precio=:precio,producto_stock=:stock,categoria_id=:categoria WHERE producto_id=:id");
        
        $marcadores=[
            ":codigo"=>$codigo,
            ":nombre"=>$nombre,
            ":precio"=>$precio,
            ":stock"=>$stock,
            ":categoria"=>$categoria,
            ":id"=>$id
        ];

        if($actualizar_producto->execute($marcadores)){
            echo '
                <div class="notification is-info is-light">
                    <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
                    El PRODUCTO se actualizo con exitó
                </div>
            ';
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo actualizar el PRODUCTO, por favor intente nuevamente!
                </div>
            ';
        }
        $actualizar_producto=null;
