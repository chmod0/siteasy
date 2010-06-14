<?php
session_start();

include("User.php");
include("../bdd/Base.php");
include ("../sites/Securite.php");

$action = $_POST['action'];
$mail = $_POST['email'];
$password = $_POST['password'];

// ON CRYPTE LE MOT DE PASS
if (!empty($password))
    $password= Securite::crypte($password);

switch($action)
{
case 'addUser' : 
    $email_existant = User::findById($mail);
    if($email_existant == null)
    {
	$u = new User();
	$u->setAttr('mail', $mail);
	$u->setAttr('password', $password);
	$u->setAttr('admin', 0);
	$u->insert();
	$_SESSION['mail'] = $mail;
	$_SESSION['password'] = $password;
	$_SESSION['connecte'] = true;

	$resultat = "true";

	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)) // O,n filtre les serveurs qui bugs
	{
	    $passage_ligne = "\r\n";
	}
	else
	{
	    $passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML
	$message_txt = 'Bonjour,'.$passage_ligne.'Votre inscription a bien ete effectuee. Vous pouvez desormais creer votre site web'.$passage_ligne.$passage_ligne.'L\'equipe 		EasyWeb';
	$message_html = '<html><head></head><body><div id="center">Bonjour, <br /> Votre inscription a bien été effectuée. Vous pouvez désormais créer votre site web. <br /		><br />L\'équipe EasyWeb</div></body></html>';
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
	mail($email,$sujet,$message,$header);
    }	
    else
    {		
	$resultat = "false";
    }

    break;

case 'connect' :

    $email_existant = User::findById($mail);
    if($email_existant == null)
    {
	$resultat = "falseMail";
    }
    else
    {
	if($email_existant->getAttr('password') == $password)
	{
	    $resultat = "true";
	    $_SESSION['mail'] = $mail;
	    $_SESSION['password'] = $password;
	    $_SESSION['connecte'] = true;
	}
	else
	{
	    $resultat = "falsePassword";
	}
    }
    break;

case 'passwordUser' :
    // on teste si l'utilisateur existe
    $mail = $_POST['email'];
    //echo $mail;

    //PROBLEME GROS PROBLEME TRES GROS PROBLEME !!!
    $user = User::findById($mail);
    if($user == null)
    {
	$resultat = "false";
    }
    else
    {
	//creation du lien unique pour la creation d'un nouveau pass
	$lien = controleur_recuperation::newRecuperation($mail);
	$url = "http://localhost/groupe12/recuperation/demande.php$lien";
	// on teste si l'adresse est micro$oft, qui ne respecte pas les normes, pour adapter le caractère de retour à la ligne
	if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2-4}$#", $mail))
	{
	    $passage_ligne = "\r\n";
	}
	else
	{
	    $passage_ligne = "\n";
	}
	$resultat="true";
    }
    $resultat = 'viande';
    break;

case 'userExists':
    $email_existant = User::findById($mail);
    if($email_existant == null)
    {
	$resultat = "false";
    }
    else
    {
	$resultat = "true";
    }
    break;
}

echo $resultat;
?>
