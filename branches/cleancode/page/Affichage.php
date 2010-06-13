<?php

class Affichage{

    static function affichePage($content,$menu,$design,$cote=null,$page_site,$num_page,$nom_site, $titre_site, $image=null){

	$url = $_SERVER['REQUEST_URI'];
	// on recupere le debut de l'url
	$tab =  explode ("page/",$url);
	$debut_url = $tab[0];

	$url = 'http://' . $_SERVER["SERVER_NAME"].$debut_url.'index.php/page/'.$nom_site;


        $image = controller::getTabImagePage($num_page);


	echo "
	    <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
	    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
	    
	    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">
	    <head>
	    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	    <title>
	    $nom_site
	    </title>
	    <!-- La feuille de styles \"base.css\" doit être appelée en premier. -->
	    <!-- GERER LES DIFFERENTS DESIGN -->
	    <link rel=\"stylesheet\" type=\"text/css\" href=\"../design/page/base.css\" media=\"all\" />
	    <link rel=\"stylesheet\" type=\"text/css\" href=\"..".$design."style.css\" media=\"screen\" />

	    <link rel=\"stylesheet\" href=\"../image/css/lightbox.css\" type=\"text/css\"  />

	    <script type=\"text/javascript\" src=\"../image/js/prototype.js\"></script>
            <script type=\"text/javascript\" src=\"../image/js/scriptaculous.js?load=effects,builder\"></script>
            <script type=\"text/javascript\" src=\"../image/js/lightbox.js\"></script>


</head>

    <body>


        $cote


        <div id=\"global\">
        <div id=\"entete\">
        <h1>


        </h1>
        <p class=\"sous-titre\">

        </p>

            <h1><b><a href=\"index.php?site=$nom_site\">$titre_site</a></b></h1>

        </div><!-- #entete -->
        <div id=\"navigation\">

            $menu

        </div><!-- #navigation -->

        <div id=\"centre\">

        <div id=\"principal\">
            $image



            $content

        <div id= \"pagesite\">
            $page_site
        </div>
        <br/><br/>
            <a class=\"fb_share_button\" style=\"text-decoration: none;\" onclick=\"return fbs_click()\" href=\"http://www.facebook.com/share.php?u=$url\" target=\"_blank\"><img src=\"http://b.static.ak.fbcdn.net/images/share/facebook_share_icon.gif?8:26981\" alt=\"\" /></a>

        </div><!-- #principal -->


        <div id =\"copyright\">
             <a href=\"../portail/index.php\">Portail de cr&eacute;ation EasyWeb</a>

        </div>

        </div><!-- #centre -->



        </div><!-- #global -->

    </body>
</html>";
    }

}

?>
