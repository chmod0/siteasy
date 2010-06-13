<?php

include_once 'Affichage.php';


class vue {

	static function Affiche($content) {
		Affichage::affichePage($content);
	}
	static function formBan(){
	$form = '
		<form method="post" action = "./admin.php?action=doban">
			<input type=\"text\" name="ip" /></input>
			<input type="submit" value = \'Bannir\'></input>
		</form>

	';
	return $form;
	}




}

?>
