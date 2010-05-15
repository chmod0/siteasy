<?php
session_start();
include ('../sites/Securite.php');
include ('recuperation.php');
include ('../user/User.php');

class controleur_recuperation{

	static function newRecuperation($mail){	
		$id = Securite::random();	
		while (recuperation::findById($id)!=null){
			$id = Securite::random();
		}
		$rec = new recuperation();
		$rec->setAttr('id',$id);
		$rec->setAttr('mail',$mail);
		$rec->insert();
		return "?mail=$mail&id=$id";
	}

	


}




$fini = false;
if ((!empty($_POST['motpasse'])) && (!empty($_POST['confpass'])) && (!empty($_GET['id'])) && (!empty($_GET['mail']))   ){
	if (strcmp($_POST['motpasse'],$_POST['confpass'])==0){
		$rec = recuperation::findByid($_GET['id']);
		$mail_id = $rec->getAttr('mail');		
		if ($mail_id !=null){
			if (strcmp($mail_id,$_GET['mail'])==0){
				$user= User::findById($mail_id);
				if (!empty($user) && $user!=null){
					$pass = $_POST['confpass'];
					$crypte = Securite::crypte($pass);
					$user->setAttr('password',$crypte);
					$user->update();
					$rec->delete();
					echo '<meta http-equiv="refresh" content="0; url=../portail/index.php">';
					echo "<script>alert(\"Votre mot de passe est modifié\")</script>"; 	
					$fini = true;
				}
		 	}
		}
	}
	
}

if (!$fini){

	if ((!empty($_GET['id'])) && (!empty($_GET['mail'])) ){
		$url = '../portail/nouveauMDP.php?mail='.$_GET['mail'].'&id='.$_GET['id'];
		echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
		echo "<script>alert(\"Saisie incorrect\")</script>"; 
	}else{
		echo '<meta http-equiv="refresh" content="0; url=../portail/index.php">';
		echo "<script>alert(\"Lien invalide ou deja utilise\")</script>"; 
	}
}

?>
