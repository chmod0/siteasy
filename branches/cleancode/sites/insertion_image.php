<?php
include 'image.php';
?>

<html> 
    <head> 
	<link rel="stylesheet" type="text/css" href="../design/admin/style.css" media="screen" />
	<title>Gestionnaire d'image</title> 
    </head> 
    <body> 

<?php 
if ((!empty($_POST['im']))&&(!empty($_POST['haut']))&&(!empty($_POST['larg']))){
    $url_im = '../sites/images/'.$_POST['im'];
    $haut = $_POST['haut'];
    $larg = $_POST['larg'];
    if(filter_var($haut, FILTER_VALIDATE_INT)&&filter_var($larg, FILTER_VALIDATE_INT)){
	$code = "<img src=\"$url_im\" height=\"$haut\" width=\"$larg\" />";
	$code .= '<br/> Voici le code à inserer dans votre document : 
	    <br/> <br/> <textarea name="contenu" cols="50" rows="3"> '.$code.'</textarea>';
    }
    echo $code;


}else{
    echo "<p> <b>Chosir une image </b></p>";
    $tab_image = image::findMail($_SESSION['mail']);
    $html = '<h1> Toutes vos images </h1>';
    $html .= '<form action="'.$PHP_SELF.'" method="post">';
    $html .= '<table border = "1">';
    $i=0;
    $html .= '<tr>';
    foreach ($tab_image as $image){
	$titre = $image->getAttr('nom_image');
	$id = $image->getAttr('id');
	$nom_dur = $id;
	$nom_dur .= '.'.substr($titre, -3);
	$url_image = '../sites/images/'.$nom_dur;
	if ($i>2){
	    $html .= '</tr><tr>';
	    $i=0;
	}
	$code = '<img src="'.$url_image.'" height="100" width="100" > <br/><center><input type="radio" name="im" value="'.$nom_dur.'"></center>';
	$html.="<td>$code</td>";
	$i++;
    }
    $html .= '</tr></table>';
    $html .= '<table><tr><td>hauteur</td><td> <input type="text" name="haut" /></td></tr>';
    $html .= '<tr><td>largeur</td><td> <input type="text" name="larg" /></td></tr></table>';
    $html .= '<input type="submit" value="valider">';
    $html .= '</form>';
    echo $html;
}
?>   
	<br/>

	<a href="#" 
onClick="window.open('upload_image.php','Fiche','toolbar=no,status=no,width=300 ,height=700,scrollbars=yes,location=no,resize=yes,menubar=yes')" >Ajouter une nouvelle image</a>

	<p> Format :  .gif .jpg  .png  </p>
    </body> 
</html>
