<?php
function mon_autoload ($espaceNomClasse)
{
	$chemin = str_replace("\\", DIRECTORY_SEPARATOR, $espaceNomClasse);
	
	require_once("src".DIRECTORY_SEPARATOR.$chemin.".php");
	
	//echo "src".DIRECTORY_SEPARATOR.$chemin.".php";
}

spl_autoload_register("mon_autoload");