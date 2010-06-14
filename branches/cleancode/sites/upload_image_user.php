<?php
include 'image.php';

if(!empty($_POST['posted'])) {
    $res =  image::upload();
    echo '<html>
	<head><meta http-equiv="Refresh" content="0;URL=../portail/index.php?up=1"></head>
	<body>';
}else {
    echo '<html>
	<head>
	<link rel="stylesheet" type="text/css" href="../design/admin/style.css" media="screen" />
	<title>Upload d\'une image sur le serveur !</title>
	';
    echo '
	</head>
	<body>
	<p>Le nom du fichier ne doit pas contenir de point.</p>
	<form enctype="multipart/form-data" action="../sites/upload_image_user.php?txt=0" method="POST" name ="upload">
	<strong>S&eacute;lectionnez le fichier :</strong><br /><br /> 
	<input type="hidden" name="posted" value="1" /> 
	<input name="fichier" type="file" value="Choisir"/> 
	<input type="submit" value="Envoyer" action="javascript:envoyer();" />
	</form> 		
	<p> Formats support&eacute;s :  .gif .jpg  .png  </p>
	';

}
echo '</body>
    </html>';

?>
