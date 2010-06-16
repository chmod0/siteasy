<?php
session_start();
include_once '../user/User.php';
include_once '../sites/Site.php';
include_once '../page/Page.php';
include_once '../page/lien.php';
include_once '../design/design.php';
include_once '../modele/Modele.php';
include_once '../sites/Securite.php';
include_once '../sites/image.php';
include_once 'vue.php';
include_once '../bdd/Base.php';
include_once '../blog/model/Billet.php';
include_once '../blog/model/Commentaire.php';

class controlleur{

    static function droitAdm(){

	if (isset($_SESSION['mail'])&&isset($_SESSION['connecte'])){
	    if ($_SESSION['connecte']){
		$user = User::findById($_SESSION['mail']);
		if (!empty($user)){
		    $admin = $user->getAttr('admin');
		    return $admin;
		}
	    }
	}
	return 0;
    }

    static function donneDroit($mail){
	if (isset($mail)){
	    $user = User::findById($mail);	
	    $user->setAttr('admin',1);
	    $user->update();
	}
    }

    static function listeUser(){
	$tab = User::findAll();
	if (!empty($tab)){
	    $res ='<h1> Tout les Utilisateurs </h1>';
	    $res .= '<center><table border="1">';
	    $res.= '<tr><td> MAIL </td><td> NIVEAU </td><td> SITES </td><td> ADMIN </td><td> IMAGES </td><td style="border:none">  </td></tr>';
	    foreach ($tab as $user){
		$mail = $user->getAttr("mail");
		$admin = $user->getAttr("admin");
		if ($admin==1){
		    $admin = 'Administrateur';
		    $droit =" --- ";
		    $sup = "  ";
		}else{
		    $admin = 'Utilisateur';
		    $droit ="<a href=\"./admin.php?action=doadm&mail=$mail\">Ajouter</a>";
		    $sup = "<a href=\"./admin.php?action=rmusr&mail=$mail\"><img src=\"../design/admin/image/sup.png\"/></a>";

		}
		$image = "<a href=\"./admin.php?action=imusr&mail=$mail\">Liste</a>";
		$site = "<a href=\"./admin.php?action=lstsit&mail=$mail\">Liste</a>";

		$res.= '<tr><td>'.$mail.'</td><td>'.$admin.'</td><td>'.$site.'</td><td>'.$droit.'</td><td>'.$image.'</td><td style="border:none">'.$sup.'</td></tr>';

	    }
	    $res.='</table></center>';
	}
	vue::Affiche($res);
    }

    static function listeSiteUser($mail){
	$tab = Site::findByUser($mail);
	if (!empty($tab)){
	    $res = '<h1> Les sites de '.$mail.'</h1>';
	    $res .= '<table border="1">';
	    $res.= '<tr><td> NOM </td><td> TITRE </td><td> TYPE </td><td> CATEGORIE </td><td> MOT CLES</td><td> DESCRIPTION </td><td> CONTENU </td><td style="border:0px; color:#fff"></td></tr>';
	    foreach ($tab as $user){
		$nom = $user->getAttr("nom_site");
		$titre = $user->getAttr("titre_site");
		$id_model = $user->getAttr("id_modele");
		$mod = Modele::findById($id_model);
		$type = $mod->getAttr('libelle_modele');
		$desc_site = $user->getAttr("desc_site");
		$mot = $user->getAttr("mots_cle");
		$cate = $user->getAttr("categ_site");
		$urlcont = "<a href=\"./admin.php?action=lcs&nom=$nom\">Liste</a>";
		$urlsup = "<a href=\"./admin.php?action=rmsit&nom=$nom\"><img src=\"../design/admin/image/sup.png\"/></a>";
		$res.= '<tr><td>'.$nom.'</td><td>'.$titre.'</td><td>'.$type.'</td><td>'.$cate.'</td><td>'.$mot.'</td><td>'.$desc_site.'</td><td>'.$urlcont.'</td><td style="border:0px">'.$urlsup.'</td></tr>';

	    }
	    $res.='</table>';
	}
	vue::Affiche($res);
    }

    static function listeBanUser(){
	$tab = action::findBanni();
	$res .= '<h1> Les IP Bannies </h1>';
	$res .= '<table border="1">';
	$res.= '<tr><td> IP </td><td> AUTORISER </td></tr>';
	if(!empty($tab)){
	    foreach ($tab as $ip){
		$ip2 = long2ip($ip);	
		$url = "<a href=\"./admin.php?action=deban&ip=$ip\">Debannir</a>";
		$res.= '<tr><td>'.$ip2.'</td><td>'.$url.'</td></tr>';

	    }
	}else{
	    $res.= '<tr><td> Aucune </td><td> --- </td></tr>';
	}
	$res.='</table><br/><br/>';
	$res .= vue::formBan();
	vue::Affiche($res);
    }

    static function listeSite(){
	$tab = Site::findAll();
	if (!empty($tab)){
	    $res .= '<h1>Tout les sites</h1>';
	    $res .= '<table border="1">';
	    $res.= '<tr><td> PROPRIETAIRE </td><td> NOM </td><td> TITRE </td><td> TYPE </td><td> CATEGORIE </td><td> MOT CLES </td><td> DESCRIPTION </td><td> CONTENU </td><td style="border:0px; color:#fff"></td></tr>';
	    foreach ($tab as $user){
		$nom = $user->getAttr("nom_site");
		$titre = $user->getAttr("titre_site");
		$mail = $user->getAttr("mail");
		$mailto = '<a href="mailto:'.$mail.'">'.$mail.'</a>';
		$id_model = $user->getAttr("id_modele");
		$mod = Modele::findById($id_model);
		$type = $mod->getAttr('libelle_modele');
		$desc_site = $user->getAttr("desc_site");
		$mot = $user->getAttr("mots_cle");
		$cate = $user->getAttr("categ_site");
		$urlcont = "<a href=\"./admin.php?action=lcs&nom=$nom\">Liste</a>";
		$urlsup = "<a href=\"./admin.php?action=rmsit&nom=$nom\"><img src=\"../design/admin/image/sup.png\"/></a>";
		$res.= '<tr><td>'.$mailto.'</td><td>'.$nom.'</td><td>'.$titre.'</td><td>'.$type.'</td><td>'.$cate.'</td><td>'.$mot.'</td><td>'.$desc_site.'</td><td>'.$urlcont.'</td><td style="border:0px">'.$urlsup.'</td></tr>';

	    }
	    $res.='</table>';
	}
	vue::Affiche($res);
    }

    static function listeContenuSite($nom_site){
	$site = Site::findByNomSite($nom_site);
	$html = '<h1> Contenu Site</h1>';
	$html .= '<table border ="1">';
	if ($site->getAttr('id_modele')==1){
	    // blog
	    $html .= '<tr><td>TITRE BILLET</td><td>LIEN VERS BILLET </td></tr>';
	    $tab_billet = Billet::findByNomSite($nom_site);
	    if (!empty($tab_billet)){
		foreach($tab_billet as $page){
		    $titre = $page->getAttr('titre_billet');
		    $num = $page->getAttr('id_billet');
		    $url = 'http://' . $_SERVER["SERVER_NAME"].'/groupe12/blog/blog.php?action=detail&id='.$num.'&site='.$nom_site;		
		    $html .= '<tr><td>'.$titre.'</td><td>'.$url.' </td></tr>';
		}
	    }

	}else{
	    // page
	    $html .= '<tr><td>TITRE PAGE</td><td>lien vers page </td></tr>';
	    $tab_billet = Page::findBySite($nom_site);
	    if (!empty($tab_billet)){
		foreach($tab_billet as $page){
		    $titre = $page->getAttr('titre_page');
		    $num = $page->getAttr('num_page');
		    $url = 'http://' . $_SERVER["SERVER_NAME"].'/groupe12/page/moteur.php?action=detail&num='.$num;		
		    $html .= '<tr><td>'.$titre.'</td><td>'.$url.' </td></tr>';
		}
	    }

	}
	$html .= '</table>';
	vue::Affiche($html);
    }

    static function supprimerSite($nom_site){
	$site = Site::findByNomSite($nom_site);
	$type = $site->getAttr('id_modele');
	if ($type==1){
	    $tab_billet = Billet::findByNomSite($nom_site);	
	    if (!empty($tab_billet)){
		foreach($tab_billet as $billet){
		    $bil = $billet->getAttr("id_billet");
		    $tab_com = Commentaire::findByBillet($bil);
		    if (!empty($tab_billet)){
			foreach($tab_com as $com){
			    $com->delete();	
			}
		    }
		    $billet->delete();
		}
	    }		

	}else{
	    $tab_page = Page::findBySite($nom_site);	
	    if (!empty($tab_page)){
		foreach($tab_page as $page){
		    $num = $page->getAttr('num_page');
		    $tab_lien = lien::findByNum($num);
		    if (!empty($tab_lien)){
			foreach($tab_lien as $lien){
			    $lien->delete();
			}
		    }
		    $page->delete();
		}
	    }

	}

	$site->delete();
    }
    static function supprimerUser($mail){
	$user = User::findById($mail);
	controlleur::supimagesUtil($mail);
	$tab = Site::findByUser($mail);
	if (!empty($tab)){
	    foreach($tab as $site){
		$nom_site = $site->getAttr('nom_site');
		controlleur:: supprimerSite($nom_site);
	    }
	}
	$user->delete();
    }
    static function bannirIP($ip){
	$ip2=explode(".",$ip); 
	$controle=0; 
	foreach ($ip2 as $element) { 
	    if(filter_var($element, FILTER_VALIDATE_INT)){
		if ($element<0 && $element>255) 
		    $controle++; 
	    }else{
		$controle++; 
	    } 
	}
	if ($controle==0){
	    $ip = ip2long($ip);
	    action::bannir($ip);
	}
    }
    static function deBannirIP($ip){
	action::deBannirIp($ip);

    }
    static function acceuil(){
	controlleur::listeUser();
    }

    static function stat(){

	$html = '<h1> Statistiques </h1>';
	$nb_page = count(Page::findAll());
	$nb_site = count(Site::findAll());
	$nb_bill = count(Billet::findAll());
	$nb_com = count(Commentaire::findAll());
	$nb_user = count(User::findAll());
	$nb_ban = count(action::findBanni());
	$nb_im = count(image::findAll());
	$nb_site_user = $nb_site/$nb_user;
	$html .= '<table border ="1">
	    <tr><td> UTILISATEURS </td><td> SITES  </td><td> BILLETS  </td><td> PAGES </td><td> COMMENTAIRES  </td><td> BANNIS  </td><td> SITES/UTILISATEURS  </td></td><td> IMAGES </td></tr>';
	$html .= "<tr><td> $nb_user </td><td> $nb_site </td><td> $nb_bill </td><td> $nb_page </td><td> $nb_com </td><td> $nb_ban </td><td> $nb_site_user </td><td>$nb_im</td></tr> </table> ";
	vue::Affiche($html);
    }

    static function images ($debut=0,$fin=0){
	$tab = image::findAll();
	if (($debut ==0) &&( $fin==0) ){

	    $nb = image::nombre();
	    if ($nb>36){
		$i=0;
		$html = controlleur::htmlimage($i,$i+36,$tab);
		$i +=36 ;
		$i2 = $i+36  ;
		$html.= '<a href="./admin.php?action=lstimB&deb='.$i.'&fin='.$i2.' "><img src="../design/admin/image/sui.png"/></a>';

	    }else{
		$html = controlleur::htmlimage(0,36,$tab);
	    }
	}else{
	    $html = controlleur::htmlimage($debut,$fin,$tab);
	    if ($debut>=36){
		$html.= '<a href="./admin.php?action=lstimB&deb='.($debut-36).'&fin='.$debut.' "><img src="../design/admin/image/pre.png"/></a> ';
	    }

	    $html.= '<a href="./admin.php?action=lstimB&deb='.$fin.'&fin='.($fin+36).' "><img src="../design/admin/image/sui.png"/></a>';
	}
	vue::Affiche($html);
    }

    static function htmlimage($debut,$fin,$tab){
	$html = '<h1> Dernières images </h1>';
	$taille = count($tab);
	if ($taille > $debut){
	    for ($i=$debut;$i<$fin;$i++){
		$tab_image[]=$tab[$i];
	    }	
	    $html .= '<br /><br /><table border = "1">';
	    $i=0;
	    $html .= '<tr>';

	    foreach ($tab_image as $image){
		if (empty($image)){
		    break;
		}
		$titre = $image->getAttr('nom_image');
		$id = $image->getAttr('id');
		$proprio = $image->getAttr('mail');
		$nom_dur = $id;
		$nom_dur .= '.'.substr($titre, -3);
		$url_image = '../image/image/'.$nom_dur;

		$legende = $titre." de ".$proprio;
		if ($i>8){
		    $html .= '</tr><tr>';
		    $i=0;
		}

		$code = '<a href="'.$url_image.'"rel="lightbox[roadtrip]" title="'.$legende.'"><img src="'.$url_image.'"  height="100" width="100" /></a>';			
		$html.="<td>$code<br /><a href=\"./admin.php?action=rmim&id=$id\"><img src=\"../design/admin/image/sup.png\"/></a></td>";

		$i++;

	    }
	    $html .= '</tr>';
	    $html .= '</table>';
	}else{
	    $html .='aucune image';
	}
	return $html;

    }

    static function imagesUser ($mail,$debut=0,$fin=0){
	$tab = image::findMail($mail);
	if (($debut ==0) &&( $fin==0) ){
	    $nb = image::nombre();
	    if ($nb>36){
		$i=0;
		$html = controlleur::htmlimage($i,$i+36,$tab);
		$i +=36 ;
		$i2 = $i+36  ;
		$html.= '<a href="./admin.php?action=imusB&mail='.$mail.'&deb='.$i.'&fin='.$i2.' "><img src="./image/sui.png"/></a>';

	    }else{
		$html = controlleur::htmlimage(0,36,$tab);

	    }
	}else{
	    $html = controlleur::htmlimage($debut,$fin,$tab);
	    if ($debut>=36){
		$html.= '<a href="./admin.php?action=imusB&mail='.$mail.'&deb='.($debut-36).'&fin='.$debut.' "><img src=\"./image/pre.png\"/></a> ';
	    }

	    $html.= '<a href="./admin.php?action=imusB&mail='.$mail.'&deb='.$fin.'&fin='.($fin+36).' "><img src="./image/sui.png"/></a>';
	}
	vue::Affiche($html);
    }

    static function supimages($id){
	$i= image::findById($id);
	$titre = $i->getAttr('nom_image');
	$nom_dur = $i->getAttr('id');
	$nom_dur .= '.'.substr($titre, -3);
	$url_image = '../image/image/'.$nom_dur;
	unlink($url_image);
	$i->delete();
    }

    static function supimagesUtil($mail){
	$image= image::findMail($mail);
	foreach ($image as $i){
	    $id=$igetAttr('id');
	    controlleur::supimages($id);
	}
    }
}
