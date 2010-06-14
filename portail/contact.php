<?php

class contact{	
    public static function header(){
	$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <title>EasyWeb, le portail simplifié de création de sites Web</title>
	    <link rel="stylesheet" type="text/css" href="css/style.css" />
	    </head>
	    <body>
	    <!-- HEADER -->
	    <a href="index.php"><div id="header"></div></a>';

	$html .= '<div id="top">';
	$html .= '<table><tr>
	    <td><a href="#">Qui sommes nous</a></td>
	    <td><a href="contact.php">Nous contacter</a></td>
	    <td><a href="index.php">Accueil</a></td>
	    <tr>
	    </table>
	    </div>';
	return $html;
    }

    public static function footer(){
	$html = '<div id="footer">
	    <p>Projet Tutoré - DUT Informatique - IUT Nancy-Charlemagne<br/>
	    Julien Guepin, Geoffrey Tisserand, Arnaud Lahaxe, Baptiste Kostrzewa ou pas</p>
	    </div>	
	    </body>
	    </html>';
	return $html;
    }	

    public static function afficheContact()
    {

	$html=Contact::header();
	$html.='
	    <!-- FORMULAIRE DE CONTACT -->
	    <div id="center">

	    <fieldset><br/>
	    <legend>Formulaire de contact</legend>
	    <form method="post" action="contact.php"> 

	    <label for="email">Votre adresse email : </label><br />
	    <input type="text" name="email" id="email" value="" /><br /><br />
	    <label for="desc_site">Message : </label><br />
	    <textarea id="message" cols="38" rows="5" name="message"></textarea><br /><br />

	    <input name="envoyer" type="image" src="img/send.png" id="envoyer" value="Envoyer"/>
	    </form>
	    </fieldset>
	    </div></div>';
	$html .= Contact::footer();
	echo $html;
    }

    public function envoi(){
	$email = 'babaom@hotmail.fr'; // Déclaration de l'adresse de destination
	$html = Contact::header();
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)) // O,n filtre les serveurs qui bugs
	{
	    $passage_ligne = "\r\n";
	}
	else
	{
	    $passage_ligne = "\n";
	}
	echo $passage_ligne;
	//=====Déclaration des messages au format texte et au format HTML
	$message_txt = 'Adresse mail : ' . $_POST['email'] . '\n' . 'Message : ' . $_POST['message'];
	$message_html = '<html><head></head><body><div id="center">'.'Adresse mail : ' . $_POST['email'] . '<br />' . 'Message : ' . $_POST['message'].'</div></body></html>';
	//==========

	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========

	//=====Définition du sujet
	$sujet = "EasyWeb";
	//=========

	//=====Création du header de l'e-mail
	$header = "From: <$email>".$passage_ligne;
	$header.= "Reply-to: <$email>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========

	//=====Création du message
	$message = $passage_ligne.$boundary.$passage_ligne;
	//=====Ajout du message au format texte
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========
	//=====Envoi de l'e-mail
	if(mail($email,$sujet,$message,$header)){

	    $html.='<div id="center"><br/>Votre demande a bien été envoyée ! <br/><br/> Notre équipe vous répondra dans les plus bref délais<br/><br/>
		<a href="index.php">Cliquez ici</a> pour retourner à laccueil<br/></div>';
	}
	else
	{
	    $html.='<div id ="center"><h4>Le formulaire n\'a pas pu être envoyé; veuillez <a href="contact.php">cliquez ici</a> pour recommencer.</h4>';
	}
	$html.= Contact::footer();
	echo $html;
    }		
}		
if(isset($_POST['email']) && isset($_POST['message'])){
    Contact::envoi();
}else{
    Contact::afficheContact();
}	
?>
