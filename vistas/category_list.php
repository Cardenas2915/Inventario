<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Lista de categoría</h2>
</div>

<div class="container pb-6 pt-6">



<?php

    require_once("./php/main.php");

    #condicion para eliminar categoria#
    if(isset($_GET['category_id_del'])){
        require_once("./php/categoria_eliminar.php");
    }

        // esta definiendo en que pagina de la lista se ecuentra
    if(!isset($_GET['page'])){
        $pagina=1;

    }else{
        $pagina=(int) $_GET['page']; //int pasa la varible a un numero entero

        if($pagina<=1){
            $pagina=1;
        }
    }
    $pagina=limpiar_cadena($pagina);
    $url="index.php?vista=category_list&page="; //esta variable va a contener la url completa del sistema de la tabla
    $registros=15; // esta va a mostrar el numero total de registrados en cada pagina
    $busqueda=""; //esta variable se va a usar para realizar la busqueda

    require_once("./php/categoria_lista.php");

    ?>

    
</div>