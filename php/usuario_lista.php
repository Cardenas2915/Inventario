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

$datos=$conexion->query($consulta_datos);
$datos=$datos->fetchAll();  //esto es para hacer un array de todos los registros seleccionados mediante la consulta anterior

$total=$conexion->query($consulta_total);
$total=(int) $total->fetchColumn();  //aqui convertimos a enteros con int y fetchColumn nos devuelve una columna de los resultados

$Npaginas=ceil($total/$registros); //ceil()funcion que sirve para redondear a su entero proximo el resultado de sus parametros dados

$tabla.='
<div class="table-container">
<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
        <tr class="has-text-centered">
            <th class="has-text-centered">#</th>
            <th class="has-text-centered">Nombres</th>
            <th class="has-text-centered">Apellidos</th>
            <th class="has-text-centered">Usuario</th>
            <th class="has-text-centered">Email</th>
            <th class="has-text-centered" colspan="2">Opciones</th>
        </tr>
    </thead>
    <tbody>

';

if($total>=1 && $pagina<=$Npaginas){
$contador=$inicio+1; // esta varibale llevar la numeracion en la tabla 
$pag_inicio=$inicio+1; // variable para saber en que pocision de la pagina inicial estamos mostrado en el texto "mostrando usuarios"

foreach($datos as $rows){
    $tabla.='
        <tr class="has-text-centered" >
        <td>'.$contador.'</td>
        <td>'.$rows['usuario_nombre'].'</td>
        <td>'.$rows['usuario_apellido'].'</td>
        <td>'.$rows['usuario_usuario'].'</td>
        <td>'.$rows['usuario_email'].'</td>
        <td>
            <a href="index.php?vista=user_update&user_id_up='.$rows['usuario_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
        </td>
        <td>
            <a href="'.$url.$pagina.'&user_id_del='.$rows['usuario_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
        </td>
        </tr>
    ';
    $contador++;

}

$pag_final=$contador-1; //variable para saber en que pocision de la pagina final estamos mostrado en el texto "mostrando usuarios"


}else{

    if($total>=1){
        $tabla.='
        <tr class="has-text-centered" >
        <td colspan="7">
            <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                Haga clic acá para recargar el listado
            </a>
        </td>
        </tr>
        ';

    }else{
        $tabla.='
        <tr class="has-text-centered" >
        <td colspan="7">
            No hay registros en el sistema!
        </td>
        </tr>
        ';
    }


}
$conexion=null;
$tabla.=' </tbody></table></div>';

    

    if($total>=1 && $pagina <= $Npaginas){
        $tabla.='
        <p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
        ';
    }


    $conexion=null;
    echo $tabla;

    if($total>=1 && $pagina <= $Npaginas){
        echo paginador_tablas($pagina,$Npaginas,$url,2); //funcion ya definida en el archivo main.php
    }