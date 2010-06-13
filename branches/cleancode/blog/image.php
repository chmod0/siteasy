 <?php

require_once '../bdd/Base.php';
require_once 'model/Billet.php';
require_once 'view/Affichage.php';


   function ajoutImage($num_image,$id_billet) {
        $bil = Billet::findById($id_billet);
	$image= $bil->getAttr('image');
	if(isset($image)){
		$bil->setAttr('image',$num_image);
		$bil->update();
		return true;
	}else{
		return false;
	}
    }

   function supImage($id_billet) {
        $bil = Billet::findById($id_billet);
	print_r($bil);
	$bil->setAttr('image',0);
	$bil->update();
    }



if(isset( $_REQUEST['id_billet'])) {
    if (isset ($_REQUEST['ac'])) {
        if ($_REQUEST['ac']==1){
           supImage($_REQUEST['id_billet']);
        }
    }else {
        ajoutImage($_REQUEST['num_image'],$_REQUEST['id_billet']);
    }
}





?>
