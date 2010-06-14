<?php
session_start();
require_once("model/Billet.php");
require_once("model/Categorie.php");
require_once("../bdd/Base.php");

/**
 * Controleur permettant de g�n�rer les affichages d'administration des billets et des cat�gories et aussi la manipulation des tables
 */
class EditionController{

    /**
     * M�thode r�alisant l'ajout d'un billet en utilisant les variables pass�es gr�ce au formlulaire
     */
    public function ajoutTitreBillet(){
	if ($_REQUEST['titre_billet'] != ''){
	    if($_REQUEST['id'] == "")
	    {
		$billet = new Billet();
	    }
	    else
	    {
		$billet = Billet::findById($_REQUEST['id']);
	    }
	    $billet->setAttr('titre_billet',$_REQUEST['titre']);
	    $billet->setAttr('auteur_billet',$_SESSION['mail']);
	    $billet->setAttr('id_categ',$_REQUEST['categ_billet']);
	    $billet->setAttr('nom_site', $_REQUEST['nom_site']);
	    $retour = $billet->save();
	}
	return $retour;
    }

    public function ajoutContenuBillet(){
	if ($_REQUEST['contenu_billet'] != ''){
	    if($_REQUEST['id'] == "")
	    {
		$billet = new Billet();
	    }
	    else
	    {
		$billet = Billet::findById($_REQUEST['id']);
	    }
	    $billet->setAttr('auteur_billet',$_SESSION['mail']);
	    $billet->setAttr('contenu_billet',$_REQUEST['contenu']);
	    $billet->setAttr('id_categ',$_REQUEST['categ_billet']);
	    $billet->setAttr('nom_site', $_REQUEST['nom_site']);
	    $retour = $billet->save();
	}
	return $retour;
    }	

    /**
     * M�thode r�alisant l'ajout d'une cat�gorie en utilisant les variables pass�es gr�ce au Dialog
     */
    public function ajoutCategorie(){			
	if (($_REQUEST['titre_categ'] != '') && ($_REQUEST['description_categ'] != ''))
	{
	    $cat = new Categorie();
	    $cat->setAttr('titre_categ',$_REQUEST['titre_categ']);
	    $cat->setAttr('libelle_categ',$_REQUEST['description_categ']);
	    $cat->setAttr('nom_site', $_REQUEST['nom_site']);
	    $cat->insert();
	    return "Categorie ins�r�e";
	}
	else
	    return "bug";    
    }

    /**
     * M�thode r�alisant la suppression d'une cat�gorie en utilisant les variables pass�es gr�ce au formlulaire
     */
    public function suppressionCategorie(){
	if ($_REQUEST['id'] != ''){
	    $cat = new Categorie();
	    $cat->setAttr('id_categ',$_REQUEST['id']);
	    $cat->delete();
	}
    }

    /**
     * M�thode r�alisant la suppression d'un billet en utilisant les variables pass�es gr�ce au formlulaire
     */
    public function suppressionBillet(){			
	if ($_REQUEST['id'] != ''){
	    $billet = new Billet();
	    $billet->setAttr('id_billet',$_REQUEST['id']);
	    $billet->delete();
	}
    }


    /**
     * M�thode r�alisant la modification d'une cat�gorie en utilisant les variables pass�es gr�ce au formlulaire
     */
    public function modificationCategorie(){
	$categ = Categorie::findById($_REQUEST['id']);
	if (($_REQUEST['titre'] != '') && ($_REQUEST['description'] != '')){
	    $categ->setAttr('titre',$_REQUEST['titre']);
	    $categ->setAttr('description',$_REQUEST['description']);
	    $categ->update();
	}
    }

    /**
     * M�thode r�alisant la modification d'un billet en utilisant les variables pass�es gr�ce au formlulaire
     */
    public function modificationTitreBillet(){			
	$billet = Billet::findById($_REQUEST['id']);
	if ($_REQUEST['titre'] != ''){
	    $billet->setAttr('titre_billet',$_REQUEST['titre']);
	    $billet->setAttr('id_categ',$_REQUEST['categ_billet']);
	    $billet->update();
	}
    }

    public function modificationContenuBillet(){			
	$billet = Billet::findById($_REQUEST['id']);
	if ($_REQUEST['contenu'] != '')
	{
	    $contenu = $_REQUEST['contenu'];
	    $contenu = strip_tags ($contenu,'<p><b><font><blockquote><span><br><code><em><h1><h2><h3><h4><h5><h6><hr><table><td><tr><img><li><s><q><u><xmp><bq><sup><sub><center><left><right>');
	    $billet->setAttr('contenu_billet',$contenu);
	    $billet->setAttr('id_categ',$_REQUEST['categ_billet']);
	    $billet->update();
	}
    }
}
?>
