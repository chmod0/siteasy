<?php

class Affichage{

    static function affichePage($content){
	$menu = '
	    <a href="./admin.php?action=lstmrd">Utilisateurs</a>
	    <a href="./admin.php?action=lstban">Bannis</a>
	    <a href="./admin.php?action=lstallsit">Sites</a>
	    <a href="./admin.php?action=stat">Statistiques</a>
	    <a href="./admin.php?action=lstim">Images</a>
	    ';

	echo "
	    <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
	    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
	    
	    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">
	    <head>
	    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />
	    <title>
	    ADMINISTRATION
	    </title>
	    <!-- La feuille de styles \"base.css\" doit être appelée en premier. -->
	    <!-- GERER LES DIFFERENTS DESIGN -->

	    
	    <link rel=\"stylesheet\" type=\"text/css\" href=\"../design/page/base.css\" media=\"all\" />
	    <link rel=\"stylesheet\" type=\"text/css\" href=\"../design/admin/style.css\" media=\"screen\" />

	    <link rel=\"stylesheet\" href=\"../image/css/lightbox.css\" type=\"text/css\" media=\"screen\" />
	    <script type=\"text/javascript\" src=\"../image/js/prototype.js\"></script>
<script type=\"text/javascript\" src=\"../image/js/scriptaculous.js?load=effects,builder\"></script>
<script type=\"text/javascript\" src=\"../image/js/lightbox.js\"></script>


</head>

<body>
<div id=\"global\">
<div id=\"navigation\">
$menu
</div><!-- #navigation -->
<div id=\"centre\">
<div id=\"principal\">

<center>$content</center>
<a href=\"javascript:history.go(-1)\">Retour</a> 

<br/><br/>
</div><!-- #principal -->

<div id =\"copyright\">	
<a href=\"../portail/index.php\">Portail de Creation EasyWeb</a>

</div>

</div><!-- #centre -->

</div><!-- #global -->
</body>
</html>";
    }
}
?>
