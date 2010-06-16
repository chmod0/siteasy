<?php
include_once '../bdd/Base.php';
include_once 'User.php';
echo "<h1>Portail test ....</h1>";
try{
    echo "<b>Test 1 : parcours des utilisateurs : </b><br/>" ;
    $lu = User::findAll();
    foreach ($lu as $user) {
	echo "mail : " . $user->getAttr('mail') . "<br/>" ;
	echo "password : " . $user->getAttr('password') . "<br/>" ;

    }

    echo "<b>Test 2 : ajout d'un utilisateur : </b><br/>" ;

    $u= new User();
    $u->setAttr('mail', "mail test");
    $u->setAttr('password', "password test");
    $u->insert();
    echo "Mail du nv utilisateur  : " . $u->getAttr('mail') .'<br/>';

    echo "nouvelle liste : <br/>";
    foreach (User::findAll() as $user) {
	echo "mail : " . $user->getAttr('mail') . "<br/>" ;
	echo "password : " . $user->getAttr('password') . "<br/>" ;

    }

    echo "<b>Test 3 : modification de l'utilistateur : </b><br/>" ;
    $u->setAttr('password', "nouveau mdp de lutilisateur de test");
    $u->update();

    echo "<b>Test 4 : retrouver un utilisateur </b><br/>";
    $um = User::findById($u->getAttr('mail'));
    echo "mail : " . $um->getAttr('mail') . "<br/>" ;
    echo "password : " . $um->getAttr('password') . "<br/>" ;

    echo "<b>Test 5 : supprimer un utilisateur </b><br/>";
    $um->delete();

    foreach (User::findAll() as $user) {
	echo "mail : " . $user->getAttr('mail') . "<br/>" ;
	echo "password : " . $user->getAttr('password') . "<br/>" ;
    }
}catch(Exception $e){
    echo "<p>EXCEPTION !!!</p>";
    echo $e->getMessage();	
}

?>
