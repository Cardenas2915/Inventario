<?php

//esta es la conexio a la base de datos
function conexion(){
$pdo = new PDO('mysql:host=localhost;dbname=inventario','root',''); 
return $pdo;
}

//verificar datos que sean los requeridos dados en formulario del login
function verificar_datos($filtro,$cadena){

    if(preg_match("/^".$filtro."$/",$cadena)){
        return false;
    }else{
        return true;
    }
}
		//ejemplo para la funcion de verificar_datos
// $nombre = "Carlos"
// if(verificar_datos("[a-z-A-Z]{6,10}",$nombre)){
// 	echo "los datos no coinciden";
// }

//sirve para limpiar cadenas de texto 
function limpiar_cadena($cadena){
        $cadena = trim($cadena);      // la funcion trim elimina espacios en blanco del inicio o al final de la cadena
        $cadena = stripcslashes($cadena);  //stripcslashes quita las barras e un string con comillas escapadas
        $cadena = str_ireplace("<script>", " " ,$cadena); //reemplaza un texto mediante una busqueda, esta version es incensible para mayusculas y minusculas
		//aqui esta reemplazando los primeros parametros por espacios vacios...Esto se usa para evitar inyeccion SQL
        $cadena = str_ireplace("</script>", " " ,$cadena); 
        $cadena = str_ireplace("<script src", "", $cadena);
		$cadena = str_ireplace("<script type=", "", $cadena);
		$cadena = str_ireplace("SELECT * FROM", "", $cadena);
		$cadena = str_ireplace("DELETE FROM", "", $cadena);
		$cadena = str_ireplace("INSERT INTO", "", $cadena);
		$cadena = str_ireplace("DROP TABLE", "", $cadena);
		$cadena = str_ireplace("DROP DATABASE", "", $cadena);
		$cadena = str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena = str_ireplace("SHOW TABLES;", "", $cadena);
		$cadena = str_ireplace("SHOW DATABASES;", "", $cadena);
		$cadena = str_ireplace("<?php", "", $cadena);
		$cadena = str_ireplace("?>", "", $cadena);
		$cadena = str_ireplace("--", "", $cadena);
		$cadena = str_ireplace("^", "", $cadena);
		$cadena = str_ireplace("<", "", $cadena);
		$cadena = str_ireplace("[", "", $cadena);
		$cadena = str_ireplace("]", "", $cadena);
		$cadena = str_ireplace("==", "", $cadena);
		$cadena = str_ireplace(";", "", $cadena);
		$cadena = str_ireplace("::", "", $cadena);
		$cadena=trim($cadena);
		$cadena=stripslashes($cadena);
		return $cadena;
}
//ejemplo para la funcion limpiar_cadena
// $texto = "<script> hola mundo </script>";
// echo limpiar_cadena($texto);


//funcion paara renombrar fotos
function renombrar_fotos($nombre){
	
	$nombre = str_ireplace(" ","_",$nombre);
	$nombre = str_ireplace("/","_",$nombre);
	$nombre = str_ireplace("#","_",$nombre);
	$nombre = str_ireplace("-","_",$nombre);
	$nombre = str_ireplace("$","_",$nombre);
	$nombre = str_ireplace(".","_",$nombre);
	$nombre = str_ireplace(",","_",$nombre);

	$nombre = $nombre."_".rand(0,100); //la funcion rand seleccion un numero aleatorio entre los parametros dados en este caso es de 0 a 100
	return $nombre;
}

//ejemplo de la funcion renombrar_fotos
// $foto = "play station#5 black/edition";
// echo renombrar_fotos($foto);


#funcion paginador de tablas#cap 14
function paginador_tablas($paginas,$Npaginas,$url,$botones){
	$tabla ='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

	//FUNCION PARA EL BOTON ANTERIOR
	if($paginas <= 1){
		$tabla.='<a class="pagination-previous is-disabled" disabled>Anterior</a>
		<ul class="pagination-list">
		';
	}else{
		$tabla.='<a class="pagination-previous" href="'.$url.($pagina-1).'">Anterior</a>
		<ul class="pagination-list">
		<li><a class="pagination-link" href="'.$url.'1">1</a></li>
		<li><span class="pagination-ellipsis">&hellip;</span></li>
		';
	}

	//funcion para los botones del medio
	$ci=0;
	for($i=$pagina; $i<=$pagina; $i++){

		if($ci>=$botones){
			break;
		}

		if($pagina==$i){
			$tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';

		}else{
			$tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
		}
		$ci++;
	}

	//FUNCION PARA EL BOTON SIGUIENTE
	if($paginas == $Npaginas ){
		$tabla.='
		</ul>
		<a class="pagination-next is-disabled" disabled>Siguiente</a>
		<ul class="pagination-list">
		';
	}else{
		$tabla.='
		<li><span class="pagination-ellipsis">&hellip;</span></li>
		<li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
		</ul>
		<a class="pagination-next is-disabled" disabled href="'.$url.($pagina+1).'">Siguiente</a>
		';
	}

	$tabla.='</nav>';
	return $tabla;
}