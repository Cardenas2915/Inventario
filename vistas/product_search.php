<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Buscar producto</h2>
</div>

<div class="container pb-6 pt-6">

<?php
    require_once("./php/main.php");

    if(isset($_POST['modulo_buscador'])){
        
        require_once("./php/buscador.php");
    }

    if(!isset($_SESSION['busqueda_producto'])&& empty($_SESSION['busqueda_producto'])){

    ?>

    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="producto">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <?php }else{ ?>

    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="producto"> 
                <input type="hidden" name="eliminar_buscador" value="producto">
                <p>Estas buscando <strong>"<?php echo $_SESSION['busqueda_producto']; ?>"</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>

    <?php

    #condicion para eliminar productos#
    if(isset($_GET['product_id_del'])){
    require_once("./php/producto_eliminar.php");
    }

if(!isset($_GET['page'])){
    $pagina=1;

}else{
    $pagina=(int) $_GET['page']; //int pasa la varible a un numero entero

    if($pagina<=1){
        $pagina=1;
    }
}

$categoria_id=(isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

$pagina=limpiar_cadena($pagina);
$url="index.php?vista=product_search&page="; //esta variable va a contener la url completa del sistema de la tabla
$registros=15; // esta va a mostrar el numero total de registrados en cada pagina
$busqueda=$_SESSION['busqueda_producto']; //esta variable se va a usar para realizar la busqueda

require_once("./php/producto_lista.php");

} ?>


</div>