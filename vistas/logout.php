<?php

session_destroy();
if(headers_sent()){ // si se envia encabezados devuelve TRUE
    echo "<script> window.location.href='index.php?vista=login'; </script>";
}else{
    header("Location: index.php?vista=login");
}
