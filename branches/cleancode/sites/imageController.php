<?php
session_start();
include "image.php";
require_once ('../page/image_page.php');
require_once("../sites/JSON.php");
require_once("../blog/model/Billet.php");
class imageController{

    static function htmlimage($debut,$fin,$tab){

	$html = '<h2>Vos images</h2>';

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
		if ($i>4){
		    $html .= '</tr><tr>';
		    $i=0;
		}

		$code = '<a href="'.$url_image.'"rel="lightbox[roadtrip]" title="'.$legende.'"><img src="'.$url_image.'"  height="100" width="100" /></a>';
		$html.="<td>$code<br /><a href=\"./index.php?action=rmimage&id=$id\"><img src=\"../design/admin/image/sup.png\"/></a></td>";


		$i++;

	    }
	    $html .= '</tr>';
	    $html .= '</table>';
	}else{
	    $html .='aucune image';
	}
	$html .= "<a href=\"#\" onClick=\"window.open('../sites/upload_image.php','UPLOAD','toolbar=no,status=no,width=650 ,height=600,scrollbars=yes,location=no,resize=yes,menubar=yes')\"> <h2>Nouvelle image</h2</a>";
	return $html;

    }

    static function supimages($id){
	$i= image::findById($id);
	$titre = $i->getAttr('nom_image');
	$nom_dur = $i->getAttr('id');
	$nom_dur .= '.'.substr($titre, -3);
	$url_image = '../image/image/'.$nom_dur;
	unlink($url_image);
	$i->delete();
	//=+> On supprime les images dans page_image
	$tab_im_p = image_page::findByImage($id);

	foreach ($tab_im_p as $ref){
	    $ref->delete();
	}

	//=+> On enleve les reference images dans les billet
	$tab_billet = Billet::findByImage($id);
	foreach ($tab_billet as $bil){
	    $bil->setAttr('image',0);
	    $bil->update();
	}


	return true;
    }

    static function imagesUser($mail,$debut=0){
	$tabimage = image::findMail($mail);
	if ($debut ==0){
	    $nb = count($tabimage);
	    if ($nb>16){
		$tabimage=imageController::decoupeTab($tabimage, 0, 15);
	    }else{
		$tabimage=imageController::decoupeTab($tabimage, 0, 15);
	    }
	}else{
	    if ($debut>=15){
		$tabimage=imageController::decoupeTab($tabimage, $debut, $debut+15);
	    }else{
		$tabimage=imageController::decoupeTab($tabimage, 0, 15);
	    }
	}

	$objetJSON = new Services_JSON();
	foreach ($tabimage as $im){
	    $tab[] = $im->toArray();

	}
	$resultatJSON = $objetJSON->encode($tab);
	return $resultatJSON;
    }

    static function decoupeTab($tab,$deb){
	$fin = $deb+15;
	for($i=$deb;$i<$fin;$i++){
	    if ($tab[$i]!=null)
		$res[]=$tab[$i];
	}
	return $res;
    }

}
if(isset($_SESSION['mail']) && $_SESSION['connecte']&& isset( $_REQUEST['deb'])){
    echo imageController::imagesUser($_SESSION['mail'],$_REQUEST['deb']);

}
if (isset( $_REQUEST['id'])){
    echo imageController::supimages($_REQUEST['id']);

}else{
    echo  false;
}
?>
