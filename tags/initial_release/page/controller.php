<?php
session_start();
include_once 'vue.php';
include_once 'lien.php';
include_once 'image_page.php';
include_once '../sites/Site.php';
include_once '../sites/image.php';
include_once '../design/design.php';

class controller {


    static function existeSite($nom_site) {
        $tab = Page::findBySite($nom_site);
        if (!empty($tab)) {
            return true;
        }else {
            return false;
        }
    }


    static function defaut($nom_site) {
        $content = vue::acceuilPage($nom_site);
        $page = controller::returnPageAcceuil($nom_site);
        $num= $page->getAttr('num_page');
        if (!isset($content)) {
            $content = "Il n'y a pas de page pour ce site";
        }else {
            $menu = vue::Menu ($nom_site);
        }

        $design = controller::getPath($nom_site);
        $page_site = controller::lientoutsite($nom_site);
        $site = Site::findByNomSite($nom_site);
        $titre_site = $site->getAttr('titre_site');
        vue::affiche($content,$menu,$design,"",$page_site,$num,$nom_site, $titre_site);
    }

    static function editMode($nom_site) {
    //$content = vue::acceuilPage($nom_site);
        $page = controller::returnPageAcceuil($nom_site);
        $num= $page->getAttr('num_page');
        $content = vue::affichePageEdition($page);
        if (!isset($content)) {
            $content = "Il n'y a pas de page pour ce site";
        }else {
            $menu = vue::Menu ($nom_site);
        }

        $design = controller::getPath($nom_site);
        $page_site = controller::lientoutsite($nom_site);
        $site = Site::findByNomSite($nom_site);
        $titre_site = $site->getAttr('titre_site');
        vue::AfficheEditeur($content,$menu,$design,"",$page_site,$num,$nom_site, $titre_site);
    }

    static function lientoutsite($nom_site) {
        $tab = Page::findBySite($nom_site);
        $nb = count($tab);
        $nb_col = 4;
        $res = vue::listePageSite($nb_col,$tab);
        return $res;
    }



    static function estAdmin($nom_site) {
	/*if ($_SESSION['connecte'])
	{
	    if (isset($_SESSION['SITES'])&& !empty($_SESSION['SITES'] ))
	    {
		foreach ($_SESSION['SITES'] as $cle => $val)
		{
		    if(strcmp($cle,$nom_site)==0){
			return $val;
		    }

		}
	    }
	    $site = site::findByNomSite($nom_site);
	    if($site != null)
	    {
		$auteur = $site->getAttr('mail');
		if (isset($_SESSION['mail'])&&!empty($_SESSION['mail']))
		{
		    if(strcmp($auteur,$_SESSION['mail'])==0)
		    {

			$_SESSION['SITES'][$nom_site]=true;

			return true;
		    }
		}
	    }
	    return false;
	}
	$_SESSION['SITES'][$nom_site]=false;
	return false;*/
        return true;

    }


    static function getPath($nom_site) {
        $design = controller::chercheDesign($nom_site);
        $path = $design->getAttr("path_design");

        if (empty($path)) {
            $path = "../design/page/vert/";
        }
        return $path;
    }

    static function chercheDesign($nom_site) {
        $site = Site::findByNomSite($nom_site);
        $id = $site->getAttr('id_design');
        $design = design::findById($id);
        return $design;
    }


    static function pageNum($num) {
        if (!empty($num)) {
            if($_SESSION['editPage'] != true) {
                $page = Page::findByNum($num);
                controller::page($page);
            }
            else {
                $page = Page::findByNum($num);
                controller::pageEditMode($page);
            }
        }
    }

    static function pageTitre($nom , $nom_site) {
        if (!empty($nom) && !empty($nom_site)) {
            if($_SESSION['editPage'] != true) {
                $page = Page::findByTitre($nom,$nom_site);
                controller::page($page);
            }
            else {
                $page = Page::findByTitre($nom,$nom_site);
                controller::pageEditMode($page);
            }
        }
    }

    static function page($page,$image=null) {

        if ( !empty($page) ) {
            $num= $page->getAttr('num_page');
            $nom_site = $page->getAttr('nom_site');
            $content = vue::affichePage($page);
            $menu = vue::Menu ($nom_site);

            $design = controller::getPath($nom_site);
            $page_site = controller::lientoutsite($nom_site);
            $site = Site::findByNomSite($nom_site);
            $titre_site = $site->getAttr('titre_site');
            vue::affiche($content,$menu,$design," ",$page_site,$num,$nom_site, $titre_site,$image);
        }
    }

    static function pageEditMode($page,$image=null) {
        if ( !empty($page) ) {
            $num= $page->getAttr('num_page');
            $nom_site = $page->getAttr('nom_site');
            $content = vue::affichePageEdition($page);
            $menu = vue::Menu ($nom_site);
            $design = controller::getPath($nom_site);
            $page_site = controller::lientoutsite($nom_site);
            $site = Site::findByNomSite($nom_site);
            $titre_site = $site->getAttr('titre_site');
            vue::AfficheEditeur($content,$menu,$design," ",$page_site,$num,$nom_site, $titre_site,$image);
        }
    }

    static function lien($num) {
        if (!empty ($num)) {
            $lien = controller::lienAjout($num);
            $page = Page::findByNum($num);
            $nom_site = $page->getAttr('nom_site');
            $content = vue::affichePage($page);
            $menu = vue::Menu ($nom_site);
            $design = controller::getPath($nom_site);
            $page_site = controller::lientoutsite($nom_site);
            $site = Site::findByNomSite($nom_site);
            $titre_site = $site->getAttr('titre_site');
            vue::AfficheEditeur($content,$menu,$design,$lien,$page_site,$num,$nom_site, $titre_site);
        }
    }

    static function ajoutLien($cible,$source) {
        $page = Page::findByNum($source);
        $nom_site = $page->getAttr('nom_site');
        if ( controller::estAdmin($nom_site)) {
            $lien = new lien();
            if ($cible != $source) {
                $lien->setAttr("num_page",$cible);
                $lien->setAttr("num_page_est_reference_par",$source);
                $tab = lien::findByPage($source);
                $ajouter = true;
                foreach($tab as $l) {
                    $cible_l = $l->getAttr('num_page');
                    if ($cible==$cible_l) {
                        $ajouter=false;
                        break;
                    }
                }
                if ($ajouter) {
                    $lien->insert();
                }else {
                    echo "<script>alert(\"Le lien existe déjà\")</script>";
                }
            }
        }
    }



    static function lienAjout($numpage_ref) {
        $page = Page::findByNum($numpage_ref);
        $nom_site = $page->getAttr('nom_site');
        if ( controller::estAdmin($nom_site)) {
            $page = Page::findByNum($numpage_ref);
            $nom_site = $page->getAttr('nom_site');
            $tab = Page::findBySite($nom_site);
            $res = "<div id=\"cote\">";
            $res .= "<a href=\"./moteur.php?action=detail&num=$numpage_ref\" title=\"Fermer le menu\"><img src=\"../editor/img/icon_close.png\" onmouseout=\"this.src='../editor/img/icon_close.png'\" onmouseover=\"this.src='../editor/img/icon_close_over.png'\" align=\"right\" alt=\"Fermer le menu\" /></a>";
            $res .= "<h3>Interne<br></h3>";
            $res .= '<ul>';
            foreach ($tab as $p) {
                $titre = $p->getAttr('titre_page');
                $num = $p->getAttr('num_page');
                $res .= '<li>'.vue::afficheLien($titre,$num,$numpage_ref);
                $res .= "<br>";
            }
            $res .= '</ul>';
            $res .= "<h3>Externe<br></h3>";
            $res .= vue::LienExterne($numpage_ref);
            $res .= "</div>";
            return $res;
        }
    }

    static function lienExterne($num_ref,$url_cible) {
        $page = Page::findByNum($num_ref);
        $nom_site = $page->getAttr('nom_site');
        if ( controller::estAdmin($nom_site)) {
            if(filter_var($url_cible, FILTER_VALIDATE_URL)) {
                $lien = new lien();
                $lien->setAttr('num_page_est_reference_par',$num_ref);
                $lien->setAttr('lien_cible',$url_cible);
                $test = lien::findByCible($url_cible);
                if (empty($test)) {
                    $lien->insert();
                }else {
                    echo "<script>alert(\"L'url est déjà référencée\")</script>";
                }
            }else {
                echo "<script>alert(\"L'url n'est pas valide\")</script>";
            }
            controller::lien($num_ref);
        }
    }

    static function returnPageAcceuil($nom_site) {
        $tab = Page::findBySite($nom_site);
        if (empty ($tab)) {
            return null;
        }
        return $tab[0];
    }

    static function Supression($nom_site,$num) {
        if (!empty($num)) {
            if ( controller::estAdmin($nom_site)) {
            // verification que la page appartien bien au site
                $page = Page::findByNum($num);
                if (!empty($page)) {
                    $site = $page->getAttr("nom_site");
                    $nb = count(Page::findBySite($site));
                    if ($nb>1) {
                        $page->delete();
                        $tab_lien = lien::findByPage($num);
                        foreach ($tab_lien as $l) {
                            $l->delete();
                        }
                        $tab_lien = lien::findByPageCible($num);
                        foreach ($tab_lien as $l) {
                            $l->delete();
                        }

                        $retour = "OK";
                    }
                }else {
                    $retour = "La page n'existe pas";
                }
            }else {
                $retour = "Erreur de suppression 2";
            }
        }
        else {
            $retour = "Erreur de suppression 3";
        }
        echo $retour;
    }




    static function EditionTitrePage($num_page,$titre) {
        if (!empty($num_page)&&!empty($titre)) {
            $page = Page::findByNum($num_page);
            $page->setAttr('titre_page',$titre);
            $page->update();
        }
    }

    static function EditionContenuPage($num_page, $contenu) {
        if (!empty($num_page)&&!empty($contenu)) {
            $page = Page::findByNum($num_page);

            $contenu = strip_tags ($contenu,'<p><b><a><font><blockquote><span><br><code><em><h1><h2><h3><h4><h5><h6><hr><table><td><tr><img><li><s><q><u><xmp><bq><sup><sub><center><left><right>');
            $page->setAttr('contenu_page',$contenu);
            $page->update();
        }
    }

    static function newPage($nom_site) {
        if (!empty($nom_site)) {
            if ( controller::estAdmin($nom_site)) {
                $page = new Page();
                $titre = "Titre de la page";
                $contenu = "Contenu de la page";
                $page->setAttr("nom_site",$nom_site);
                $page->setAttr("titre_page",$titre);
                $page->setAttr("id_bloc",0);
                $page->setAttr("contenu_page",$contenu);
                $page->insert();
                controller::pageNum($page->getAttr("num_page"));
            }
        }
    }

    static function newInsertPageTitre() {
        $nom_site = $_REQUEST['site'];
        $id = $_REQUEST['id'];
        if (!empty($nom_site)) {
            if ( controller::estAdmin($nom_site)) {
                if($_REQUEST['id'] == '') {
                    $page = new Page();
                    $page->setAttr("contenu_page", "Contenu de la page");
                }
                else {
                    $page = Page::findByNum($id);
                }
                $titre = $_POST['titre'];
                $contenu = $_POST['contenu'];
                $page->setAttr("nom_site",$nom_site);
                $page->setAttr("titre_page",$titre);
                $page->setAttr("id_bloc",0);
                $id = $page->save();
            }
        }
        return $id;
    }

    static function newInsertPageContenu() {
        $nom_site = $_REQUEST['site'];
        $id = $_REQUEST['id'];
        if (!empty($nom_site)) {
            if ( controller::estAdmin($nom_site)) {
                if($_REQUEST['id'] == '') {
                    $page = new Page();
                    $page->setAttr("titre_page", "Titre de la page");
                }
                else {
                    $page = Page::findByNum($id);
                }

                $titre = $_POST['titre'];
                $contenu = $_POST['contenu'];
                $page->setAttr("nom_site",$nom_site);
                $page->setAttr("titre_page",$titre);
                $page->setAttr("id_bloc",0);
                $page->setAttr("contenu_page",$contenu);
                $id = $page->save();
            }
        }
        return $id;
    }

    static function ChangeModele($nom_site) {
        if ( controller::estAdmin($nom_site)) {
            $site = Site::findByNomSite($nom_site);
            $id = $site->getAttr('id_design');

            switch ($id) {
                case 0:
                    $id=2;
                case 2:
                    $id =3;
                    break;
                case 3:
                    $id =4;
                    break;
                case 4:
                    $id =5;
                    break;
                case 5:
                    $id =2;
                    break;
            }
            $site->setAttr('id_design',$id);
            $site->update();
        }
    }
    static function supprimerLien($id) {
        if (!empty($id)) {
            $lien = lien::findByNum($id);
            if($lien != null) {
                $num = $lien->getAttr('num_page_est_reference_par');
                $page = Page::findByNum($num);
                $nom_site = $page->getAttr('nom_site');
                if ( controller::estAdmin($nom_site)) {
                    $lien->delete();
                }
            }
            controller::pageNum($num);
        }
    }


    static function formim($num) {
        $page = Page::findByNum($num);

        $tab_image = image::findMail($_SESSION['mail']);
        $html = '<h1> Toutes vos images </h1>';
        $html .= '<form action="./moteur.php?action=codemim&num='.$num.'" method="post">';
        $html .= '<table border = "1">';
        $i=0;
        $html .= '<tr>';
        foreach ($tab_image as $image) {
            $titre = $image->getAttr('nom_image');
            $id = $image->getAttr('id');
            $nom_dur = $id;
            $nom_dur .= '.'.substr($titre, -3);
            $url_image = '../image/image/'.$nom_dur;
            $proprio = $image->getAttr('mail');
            $legende = $titre." de ".$proprio;
            if ($i>9) {
                $html .= '</tr><tr>';
                $i=0;
            }
            $code = '<a href="'.$url_image.'"rel="lightbox[roadtrip]" title="'.$legende.'"><img src="'.$url_image.'"  height="100" width="100" /> </a><br/><center><input type="radio" name="im" value="'.$nom_dur.'"></center>';
            $html.="<td>$code</td>";
            $i++;
        }
        $html .= '</tr></table>';
        $html .= '<table><tr><td>hauteur</td><td> <input type="text" name="haut" /> pixel</td></tr>';
        $html .= '<tr><td>largeur</td><td> <input type="text" name="larg" /> pixel</td></tr></table>';
        $html .= '<input type="submit" value="valider">';
        $html .= '</form>';
        controller::pageEditMode($page,$html);
    }

    static function getTabImagePage($num_page,$edit=false) {
        $tab = image_page::findByPage($num_page);
        $im="";
        if (count($tab)>0) {
            $i=0;
            foreach($tab as $ref) {
                $id= $ref->getAttr('num_image');
                $image=image::findById($id);
                $titre = $image->getAttr('nom_image');
                $nom_dur = $id;
                $nom_dur .= '.'.substr($titre, -3);
                $url_image = '../image/image/'.$nom_dur;
                if ($edit) {
                    $im .= "&nbsp;<img src=\"$url_image\"  height=\"200\" width=\"200\" style=\"cursor:url('../editor/img/icon_close.png');\" onClick = 'javascript:dialogSupImage(".$id.",".$num_page.");' />&nbsp;";
                }else {
                    $im .= "&nbsp;<a href=\"$url_image\" rel=\"lightbox[roadtrip]\"><img src=\"$url_image\"  height=\"200\" width=\"200\" /></a>&nbsp;";
                }
                $i++;
                if ($i==4) {
                    $im.='<br>';
                    $i=0;
                }

            }
        }
        return $im;
    }


    static function ajoutImage($num_image,$num_page) {
        $im=new image_page();
        $im->setAttr('num_page', $num_page);
        $im->setAttr('num_image', $num_image);
        $im->insert();
    }
     static function supImage($num_image,$num_page) {
        $im=new image_page();
        $im->setAttr('num_page', $num_page);
        $im->setAttr('num_image', $num_image);
        $im->delete();
    }


  



}
if(isset($_SESSION['mail']) && $_SESSION['connecte']&&  isset( $_REQUEST['num_page'])) {
    if (isset ($_REQUEST['ac'])) {
        if ($_REQUEST['ac']==1){
		if (isset( $_REQUEST['num_image']))
            		controller::supImage($_REQUEST['num_image'],$_REQUEST['num_page']);
        }
	 if ($_REQUEST['ac']==2){
		$page = Page::findByNum($_REQUEST['num_page']);
                $nom_site = $page->getAttr('nom_site');
		$menu = vue::Menu ($nom_site); 
		$menu .="&|-|&".controller::lientoutsite($nom_site);
		echo $menu;
	}
	
    }else {
        controller::ajoutImage($_REQUEST['num_image'],$_REQUEST['num_page']);
    }
}



?>
