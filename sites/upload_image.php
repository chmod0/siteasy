<?php
include 'image.php';
?>

<html> 
    <head> 
	<link rel="stylesheet" type="text/css" href="../design/admin/style.css" media="screen" />
        <title>Chargement d'une image sur le serveur !</title> 
    </head> 
    <body onunload='javascript:opener.document.location= "http://localhost/groupe12/administration/admin.php?action=lstim";'>

<?php 
if(!empty($_POST['posted'])) { 
	image::upload();	
  }
?>   

	<p>Le nom du fichier ne doit pas contenir de point.</p>
	 <form enctype="multipart/form-data" action="<?php echo $PHP_SELF; ?>" method="POST"> 
            <strong>S&eacute;lectionnez le fichier :</strong><br /><br /> 
            <input type="hidden" name="posted" value="1" /> 
            <input name="fichier" type="file" value="Choisir"/> 
            <input type="submit" value="Envoyer" /> 
        </form> 
	
	

	<p> Formats support&eacute; :  .gif .jpg  .png  </p>
    </body> 
</html>
