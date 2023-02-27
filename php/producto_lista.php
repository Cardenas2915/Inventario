<?php

$inicio = ($pagina>0) ? (($pagina*$registros)-$registros) : 0; // esta funcion es para saber donde iniciar la tabla
$tabla=""; //variable para generar todo el listado de productos

$campos="producto.producto_id,producto.producto_codigo,producto.producto_nombre,producto.producto_precio,producto.producto_stock,producto.producto_foto,categoria.categoria_id,categoria.categoria_nombre,
usuario.usuario_id,usuario.usuario_nombre,usuario.usuario_apellido";



if(isset($busqueda) && $busqueda!=""){ //isset es para saber si la variable esta definida

    //esta consulta es la que se va a realizar para hacer el buscador 
    $consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id WHERE producto.producto_codigo LIKE '%$busqueda%' OR producto.producto_nombre LIKE '%$busqueda%'  ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

    //esta es la consulta que va a tener el total de resultados de la base de datos  que correspondan a la busqueda
    $consulta_total="SELECT COUNT(producto_id) FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

}elseif($categoria_id>0){

    //esta consulta es la que se va a realizar para hacer el buscador 
    $consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id WHERE producto.categoria_id='$categoria_id'  ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

    //esta es la consulta que va a tener el total de resultados de la base de datos  que correspondan a la busqueda
    $consulta_total="SELECT COUNT(producto_id) FROM producto WHERE categoria_id='$categoria_id'";
    
}else{
    //esta consulta es para ordenar los registros de forma ascedente limitando el usuario logiado
    $consulta_datos="SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id INNER JOIN usuario ON producto.usuario_id=usuario.usuario_id ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

    // esta consulta es para hacer el conteo de todos los id que coinciden en la tabla
    $consulta_total="SELECT COUNT(producto_id) FROM producto";
}

$conexion=conexion();

$datos=$conexion->query($consulta_datos);
$datos=$datos->fetchAll();  //esto es para hacer un array de todos los registros seleccionados mediante la consulta anterior

$total=$conexion->query($consulta_total);
$total=(int) $total->fetchColumn();  //aqui convertimos a enteros con int y fetchColumn nos devuelve una columna de los resultados

$Npaginas=ceil($total/$registros); //ceil()funcion que sirve para redondear a su entero proximo el resultado de sus parametros dados


if($total>=1 && $pagina<=$Npaginas){
$contador=$inicio+1; // esta varibale llevar la numeracion en la tabla 
$pag_inicio=$inicio+1; // variable para saber en que pocision de la pagina inicial estamos mostrado en el texto "mostrando usuarios"


//la funcion substr es para recortar el texto mostrado
foreach($datos as $rows){
    $tabla.='

    <article class="media">
    <figure class="media-left">
        <p class="image is-64x64">';
        //si existe la imagen la ruta especificada mostrar esa foto si no mostrar la otra
        if(is_file("./img/producto/".$rows['producto_foto'])){

            $tabla.='<img src="./img/producto/'.$rows['producto_foto'].'">';
        }else{

            $tabla.='<img src="./img/producto.png">';
        }
            
            
    $tabla.='</p>
    </figure>
    <div class="media-content">
        <div class="content">
            <p>
                <strong>'.$contador.' - '.$rows['producto_nombre'].'</strong><br>
                <strong>CODIGO:</strong> '.$rows['producto_codigo'].', 
                <strong>PRECIO:</strong> $'.$rows['producto_precio'].', 
                <strong>STOCK:</strong> '.$rows['producto_stock'].', 
                <strong>CATEGORIA:</strong> '.$rows['categoria_nombre'].', 
                <strong>REGISTRADO POR:</strong> '.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'
            </p>
        </div>
        <div class="has-text-right">
            <a href="index.php?vista=product_img&product_id_up='.$rows['producto_id'].'" class="button is-link is-rounded is-small">Imagen</a>
            <a href="index.php?vista=product_update&product_id_up='.$rows['producto_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
            <a href="'.$url.$pagina.'&product_id_del='.$rows['producto_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
        </div>
    </div>
    </article>

   

    ';
    $contador++;

}

$pag_final=$contador-1; //variable para saber en que pocision de la pagina final estamos mostrado en el texto "mostrando usuarios"


}else{

    if($total>=1){
        $tabla.='
        <p class="has-text-centered">
            <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                Haga clic acá para recargar el listado
            </a>
            </p>
        ';

    }else{
        $tabla.='
        <p class="has-text-centered">
            No hay registros en el sistema!
        </p>
        ';
    }


}


    if($total>0 && $pagina <= $Npaginas){
        $tabla.='
        <p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
        ';
    }


    $conexion=null;
    echo $tabla;

    if($total>=1 && $pagina <= $Npaginas){
        echo paginador_tablas($pagina,$Npaginas,$url,3); //funcion ya definida en el archivo main.php
    }


