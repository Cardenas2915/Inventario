<?php

































//funcion para renombrar fotos
function renombrar_fotos($nombre){
    //en esta parte esta definiendo que caracteres borrar y que sean reemplazados por un guion bajo
    $nombre = str_ireplace(" ","_",$nombre);
    $nombre = str_ireplace("/","_",$nombre);
    $nombre = str_ireplace("#","_",$nombre);
    $nombre = str_ireplace("-","_",$nombre);
    $nombre = str_ireplace("$","_",$nombre);
    $nombre = str_ireplace(".","_",$nombre);
    $nombre = str_ireplace(",","_",$nombre);
    $nombre = $nombre. "_". rand(0,100); //funcion rand genera un numero aleatorio en este caso de 0 a 100 que fueron los parametros que se establecieron,que - 
    //se usa para ponerle ese numero al final del nombre y asi no se repita el nombre
    return $nombre;
}

// $foto = "play station 5 black/edition";
// echo renombrar_fotos($foto);



//funcion paginador de tablas
funtion paginador_tablas($pagina,$Npaginas,$url,$botones){

}