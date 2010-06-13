<?php

include "imageController.php";
require_once("../bdd/Base.php");
$url = 'je suis ton pere lol';
$text ='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script language=javascript>
        function getElem(nom) {
			var Objdest=(document.getElementById) ? document.getElementById(nom):
			eval("document.all[nom]");
			return Objdest;
	}


        function ex(){
            var moncontenu=getElem("contenu");
            var url = "'.$url.'";
             moncontenu.innerHTML = url;
        }


    </script>
';

echo $text;







echo '

  </head>
  <body>

  <div id="contenu">
      <p>
      coucou
     </p>

  </div>
      <div style="width:100%;height:10%;text-align:center;">
       <input type="button" name="ok" value="a toi de jouer" onClick="javascript:ex();">
  </div>
  </body>
</html>';
 ?>