<?php

$inicio = ($pagina>0) ? (($pagina*$registros)-$registros) : 0; // esta funcion es para saber donde iniciar la tabla
$tabla=""; //variable para generar todo el listado de usuarios

if(isset($busqueda) && $busqueda!=""){ //isset es para saber si la variable esta definida

    //esta consulta es la que se va a realizar para hacer el buscador 
    $consulta_datos="SELECT * FROM usuario WHERE ((usuario_id !='".$_SESSION['id']."')AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%') )ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

    //esta es la consulta que va a tener el total de resultados de la base de datos  que correspondan a la busqueda
    $consulta_total="SELECT COUNT(usuario_id) FROM usuario WHERE ((usuario_id !='".$_SESSION['id']."')AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%'))";

}else{
    //esta consulta es para ordenar los registros de forma ascedente limitando el usuario logiado
    $consulta_datos="SELECT * FROM usuario WHERE usuario_id !='".$_SESSION['id']."'ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

    // esta consulta es para hacer el conteo de todos los id que coinciden en la tabla
    $consulta_total="SELECT COUNT(usuario_id) FROM usuario WHERE usuario_id !='".$_SESSION['id']."'";
}

$conexion=conexion();