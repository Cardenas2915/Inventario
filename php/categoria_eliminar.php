<?php

$category_id_del=limpiar_cadena($_GET['category_id_del']);



#verficando si la categoria existe en la base de datos #
$check_categoria=conexion();
$check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$category_id_del'");



if($check_categoria->rowCount()==1){  //devuelve cuantos registros se han seleccionado con la consulta

    #verficando si el usuario tiene productos registrados #
$check_productos=conexion();
$check_productos=$check_productos->query("SELECT categoria_id FROM producto WHERE categoria_id='$category_id_del'LIMIT 1");

if($check_productos->rowCount()<=0){

    $eliminar_categoria=conexion();
    $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id=:id");

    $eliminar_categoria->execute([":id"=>$category_id_del]);

    if($eliminar_categoria->rowCount()==1){
        echo '
        <div class="notification is-info is-light">
            <strong>¡Categoria Eliminado!</strong><br>
            Los datos de la categoria se eliminaron correctamente!
        </div>
    ';

    }else{
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo eliminar la categoria, Por favor intente otraves!
        </div>
    ';
    }

    $eliminar_categoria=null;

}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡No se pudo eliminar!</strong><br>
            La CATEGORIA que intenta eliminar tiene un producto registrado!
        </div>
    ';
    
}

}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La categoria que intenta eliminar no existe!
        </div>
    ';
    
}
$check_productos=null;
