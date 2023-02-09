<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="https://bulma.io">
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
                <a class="navbar-item">Nuevo</a>
                <a class="navbar-item">Lista</a>
                <a class="navbar-item">Buscar</a>
                
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Categorias</a>

                <div class="navbar-dropdown">
                <a class="navbar-item">Nueva categoria</a>
                <a class="navbar-item">Lista categoria</a>
                <a class="navbar-item">Buscar categoria</a>
                
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Productos</a>

                <div class="navbar-dropdown">
                <a class="navbar-item">Nuevo producto</a>
                <a class="navbar-item">Lista producto</a>
                <a class="navbar-item">productos por categorias</a>
                <a class="navbar-item">Buscar producto</a>

                </div>
            </div>

        </div>

        <div class="navbar-end">
        <div class="navbar-item">
            <div class="buttons">
            <a class="button is-primary is-rounded">
                Mi cuenta
            </a>
            <a class="button is-link is-rounded">
                Cerrar Sesion
            </a>
            </div>
        </div>
        </div>
    </div>
    </nav>  

</body>
</html>