<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="index.php?vista=home">
        <img src="./img/logo.png" width="65" height="28">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Usuarios</a>

                <div class="navbar-dropdown">
                <a class="navbar-item" href="index.php?vista=user_new">Nuevo</a>
                <a class="navbar-item" href="index.php?vista=user_list">Lista</a>
                <a class="navbar-item" href="index.php?vista=user_search">Buscar</a>
                
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Categorias</a>

                <div class="navbar-dropdown">
                <a href="index.php?vista=category_new" class="navbar-item">Nueva categoria</a>
                <a class="navbar-item" href="index.php?vista=category_list">Lista categoria</a>
                <a class="navbar-item" href="index.php?vista=category_search">Buscar categoria</a>
                
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Productos</a>

                <div class="navbar-dropdown">
                <a class="navbar-item" href="index.php?vista=product_new">Nuevo producto</a>
                <a class="navbar-item" href="index.php?vista=product_list" >Lista producto</a>
                <a class="navbar-item" href="index.php?vista=product_category">productos por categorias</a>
                <a class="navbar-item" href="index.php?vista=product_search">Buscar producto</a>

                </div>
            </div>

        </div>

        <div class="navbar-end">
        <div class="navbar-item">
            <div class="buttons">
            <a href="index.php?vista=user_update&user_id_up=<?php echo $_SESSION['id']; ?>" class="button is-primary is-rounded">
                Mi cuenta
            </a>
            <a href="index.php?vista=logout" class="button is-link is-rounded">
                Cerrar Sesion
            </a>
            </div>
        </div>
        </div>
    </div>
    </nav>  

</body>
</html>