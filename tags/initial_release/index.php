<?php
include ('./bdd/Base.php');
include ('./sites/Securite.php');

if (!Securite::estBanni()){
	Securite::antiDOS();
	$url = $_SERVER['REQUEST_URI'];
// on recupere le debut de l'url
	$tab =  explode ("index.php",$url);
	$debut_url = $tab[0];
	$tab = array();
// on recupere se qui va servir de query
	$tab =  explode (".php",$url);
	if (!empty($tab[0])&&!empty($tab[0])){
		$param = $tab[1];
		$tab = array();
		// on separe les parametre de selection du site
		$tab =  explode ("/",$param);
		if (strcmp($tab[1],'page')==0){
			$lien_serveur = $debut_url.'page/index.php?';
			$lien_serveur.= 'site='.$tab[2];
		}else{
			if (strcmp($tab[1],'blog')==0){
				$lien_serveur = $debut_url.'blog/index.php?';
				$lien_serveur.= 'site='.$tab[2];
			}else{
				$lien_serveur=null;
			}
		}
	}else{
		$lien_serveur=null;
	}
// on cree l url de destination 
	if ($lien_serveur!=null){
		$redirection = '<meta http-equiv="refresh" content="0; url='.$lien_serveur.'">';
		
	}else{
		$portail .= $debut_url.'portail/index.php';
		$redirection = '<meta http-equiv="refresh" content="0; url='.$portail.'">';
	}
// on redirige
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		    '.$redirection.'
		    <link rel="stylesheet" type="text/css" href="style.css" />
		</head>
		</html>';
}
?>
