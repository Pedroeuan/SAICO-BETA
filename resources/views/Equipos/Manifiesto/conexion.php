<?php
$server='localhost';
$user='root';
$pass= ''; 
$db= 'saico_beta';    
$conexion= new mysqli($server,$user,$pass,$db);  

if ($conexion->connect_errno){
    die( "Error". $conexion->connect_errno);
} else {
    //echo "Conectado";
}
