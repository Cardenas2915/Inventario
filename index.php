<?php require("incluir/session_start.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("incluir/head.php"); ?>
    <title>Sistema de inventario</title>
</head>
<body>

<?php 

if(!isset($_GET['vista']) || $_GET['vista']=="" ){

    $_GET['vista']="login";
}

if(is_file("./vistas/".$_GET['vista'].".php") &&  $_GET['vista']!="login" &&  $_GET['vista']!="404"){  //la funcion "is_fle" es para verificar si un archivo existe en la ruta dada

    #RESTRINGIR ACCESO   SI NO HA INICIADO SESION#
    
    if((!isset($_SESSION['id']) || $_SESSION['id']=="") || (!isset($_SESSION['usuario']) || $_SESSION['usuario']=="")){ //esto es para definir si una varibale esta definida o no tiene valor en este caso isset devuleve que si esta definida entonces hacemos que devuelva false 
        //si las varibles no estan definidas entonces destruye sesion y redirecciona al login
        include("./vistas/logout.php");
        exit();
    
    }

    include("incluir/navBar.php");
    include("vistas/".$_GET['vista'].".php");
    include("incluir/script.php");

}else{
    if($_GET['vista']=="login"){

        include("vistas/login.php");

    }else{
        include("vistas/404.php"); 
    }
}
?>


</body>
</html>