<?php

include_once('lien.php');
include_once('Affichage.php');
include_once('AffichageEditeur.php');

class vue {

    private $menu;
    private $droite;
    private $content;

    static function Affiche($content, $menu,$design,$lien,$page_site,$num_page,$nom_site,$titre_site, $image=null) {

	Affichage::affichePage($content, $menu, $design,$lien,$page_site,$num_page,$nom_site, $titre_site, $image);
    } 

    static function AfficheEditeur($content, $menu,$design,$lien,$page_site,$num_page,$nom_site, $titre_site, $image=null)
    {
	AffichageEditeur::affichePage($content, $menu, $design,$lien,$page_site,$num_page,$nom_site, $titre_site, $image);
    }

    static function affichePage($page){
	$titre = $page->getAttr('titre_page');
	$res .= "<h2 align='center'>$titre</h2><br/><br/>";
	$res .= $page->getAttr('contenu_page');
	$nom_site = $page->getAttr('nom_site');		
	$tab = lien::findByPage($page->getAttr('num_page'));
	$res .= vue::AfficheLienPage($tab);
	$tab = Page::findBySite($nom_site);
	return $res;	
    }
    
    static function affichePageEdition($page){
	$titre = $page->getAttr('titre_page');
	
	// affichage du titre et des boutons de sauvegarde/annulation
	$res .= "<h2 align=\"center\"><div id='titre'>$titre</div></h2>";
	$res .= '<input type="button" id="saveTitreButton" value="Enregistrer" disabled="true" style="visibility:hidden;" />
		<input type="button" id="cancelTitreButton" value="Annuler" style="visibility:hidden;" />';
	$res .= '<br />'; 
	// affichage du titre et des boutons de sauvegarde/annulation
	$res .= '<div id="contenu">';
	$res .= $page->getAttr('contenu_page');
	$res .= '</div>';
	$res .= '<input type="button" id="saveContenuButton" value="Enregistrer" disabled="true" style="visibility:hidden;" />
		<input type="button" id="cancelContenuButton" value="Annuler" style="visibility:hidden;" />'; 
	$nom_site = $page->getAttr('nom_site');		
	$tab = lien::findByPage($page->getAttr('num_page'));
	$res .= vue::AfficheLienPage($tab);
	$tab = Page::findBySite($nom_site);
	//$res .= vue::ListePage($tab,$page->getAttr('num_page'));
	return $res;	
    }

    static function AfficheLienPage($tab){
	if (!empty($tab)){
	    $res ="<br><br><br><strong>R&eacute;f&eacute;rence :</strong><br> <ul>";
	    foreach($tab as $l){
		$cible = $l->getAttr('num_page');
		if (!empty($cible)){
		    $page = Page::findByNum($cible);
		    $titre = $page->getAttr('titre_page');
		    $res .= "<li><a href=\"./moteur.php?action=detail&num=$cible\">$titre</a> ";
		}else{
		    $url = $l->getAttr('lien_cible');
		    $res .= "<li><a href=\"$url\">$url</a> ";
		}
		$id = $l->getAttr('id_lien');
		$res .= "<a href=\"./moteur.php?action=SupL&id=$id\" title=\"Supprimer le lien\"><img src=\"../editor/img/icon_close.png\" onmouseout=\"this.src='../editor/img/icon_close.png'\" onmouseover=\"this.src='../editor/img/icon_close_over.png'\" align=\"right\" alt=\"Supprimer le lien\" /></a>";
	    }
	    $res .="</ul>";
	}
	return $res;
    }

 

    static function acceuilPage($nom_site){
	if (!empty($nom_site)){
	    $tab = Page::findBySite($nom_site);
	    if (!empty($tab)){
		$titre = $tab[0]->getAttr('titre_page');
		$contenu = "<h2 align='center'><div id='titre'>$titre</div></h2>";
		$contenu .= '<div id="contenu">' . $tab[0]->getAttr('contenu_page') . '</div>';
		return $contenu;
	    }
	}
	return null;
    }


    static function Menu($nom_site){
	if (!empty($nom_site)){
	    $tab = Page::findBySite($nom_site);
	    $menu = vue::NomPage($tab, $nom_site);
	    return $menu;
	}
    }


/*
    static function ListePage($tab,$indice){
	if (!empty($tab)){
	    $nb = count($tab);
	    $res = "<br><br>Pages : ";
	    $num =0;
	    foreach ($tab as $page){
		$numero = $page->getAttr('num_page');
		if ($numero==$indice){
		    break;
		}
		$num++;
	    }
	    if (($num-1)>=0){
		$precedent = $tab[$num-1]->getAttr('num_page');
		$res.= "<a href=\"./moteur.php?action=detail&num=$precedent\"><</a>";
	    }
	    if ( $nb > 20){
		$i=0;
		for ($i=$num-4;$i<$num;$i++){
		    if ($i>=0){
			$nume = $tab[$i]->getAttr("num_page");
			$res .= "<a href=\"./moteur.php?action=detail&num=$nume\">$i</a> " ;
		    }				
		}
		if ($i+4<$nb){
		    $res.="...";
		}
		$min = $i;
		for ($i=$num;$i<=$num+4;$i++){
		    if ($i<$nb && $i>=$min){
			$nume = $tab[$i]->getAttr("num_page");
			$res .= "<a href=\"./moteur.php?action=detail&num=$nume\">$i</a> " ;	
		    }			
		}
	    }else{
		for ($i=0;$i<$nb;$i++){
		    $nume = $tab[$i]->getAttr("num_page");
		    $j = $i+1;
		    $res .= "<a href=\"./moteur.php?action=detail&num=$nume\">$j</a> " ;				
		}
	    }
	    if($num+1<$nb){
		$suivant = $tab[$num+1]->getAttr('num_page');
		$res.= "<a href=\"./moteur.php?action=detail&num=$suivant\">></a>";
	    }
	    return $res;
	}
    }
*/
    static function NomPage($tab, $nom_site){
	if (!empty($tab)){
	    $nb = count($tab);
	    $res = "";
	    if ( $nb > 15){
		for ($i=1;$i<=15;$i++){
		    $nom = $tab[$i]->getAttr("titre_page");
		    $res .= "<a href=\"./moteur.php?action=detail_titre&titre=$nom&site=$nom_site\">$nom</a> " ;				
		}
	    }else{
		for ($i=0;$i<$nb;$i++){
		    $nom = $tab[$i]->getAttr("titre_page");
		    $res .= "<a href=\"./moteur.php?action=detail_titre&titre=$nom&site=$nom_site\">$nom</a> " ;				
		}
	    }
	    return $res;
	}
    }


    static function afficheLien($lien,$num_page,$numpage_ref){	
	$res = "<a href=\"./moteur.php?action=newL&cible=$num_page&source=$numpage_ref\" >$lien</a>"; 
	return $res;
    }

    static function LienExterne($num){
	$res = "<form name=\"formulaire\" method=\"post\" action=\"./moteur.php?action=lienExt&num=$num\">";
	$res .= "<input type=\"text\" name=\"url\"style=\"width:100px\"; > ";
	$res .= "<input type=\"submit\" name=\"Submit\" value=\"OK\" style=\"width:100px\";>";
	$res .="</form>";
	return $res;
    }

    static function listePageSite($nb_col,$tab){
	$res = "<br><br><br><TABLE BORDER='0'><tr>";
	$i=0;
	foreach ($tab as $page){


	    if ($i==$nb_col){
		$res .= "</tr><tr>";
		$i=0;
	    }
	    $num = $page->getAttr('num_page');
	    $titre = $page->getAttr('titre_page');
	    $res .= "<td width=\"250\"> <center> <a href=\"./moteur.php?action=detail&num=$num\">$titre</a></center></td>" ;
	    $i++;
	}
	$res.="</tr></table>";
	return $res;
    }
}
?>
