<?php
$database = "demo";
$username = "demouser";
$serverName = "localhost";
$password = "pass123#";

//table to use
define("TABLE_CONFIG","public_social_config");

/*
 * Conection bd
 * destine for posteo
 * */
$database_posteo = "informes";
$username_posteo = "informesdiarios.";
$serverName_posteo = "localhost";
$password_posteo = "kdfSgjkLZ";

//table destine for posteo
define("TABLE_POSTEO","dia_2");

$conn = mysqli_connect($serverName, $username, $password, $database);
$conn_posteo = mysqli_connect($serverName_posteo, $username_posteo, $password_posteo, $database_posteo);
mysqli_set_charset($conn_posteo,"utf8");

//Url rute images
$urlSiteImagenes = "http://www.informesdiarios.com.ar/paginas/fotos/";
//Url of posts
$urlsitePosts = "http://www.informesdiarios.com.ar/agricultura/technology_free.php?codigo=";
//url de presencia para redes sociales
$caption = "ww.informesdiarios.com.ar";

//API key of shortener
define("API_KEY_SHOTENER","YOURAPIKEGOOGLESHORTENER");
