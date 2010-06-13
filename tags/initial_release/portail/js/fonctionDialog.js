goog.events.listen(window, 'unload', function(){
	goog.events.removeAll();
	afficheImage(0);
	});

function showDialog()
{
    objetXHR= new creationXHR();
    var parametres = "";
    objetXHR.open("post", "../sites/upload_image_user.php", "true");
    objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    objetXHR.onreadystatechange = retourneContent;
    objetXHR.send(parametres);
}

function retourneContent()
{
    if(objetXHR.readyState == 4)
    {

	if(objetXHR.status == 200)
	{
	    dialog = new goog.ui.Dialog();

	    var contenu = objetXHR.responseText;
	    dialog.setContent(contenu);
	    dialog.setTitle("Nouvelle  image");
	    dialog.setButtonSet(null);
	    dialog.setVisible(true);
	}

    }
}
function afficheImage(deb){

    if(typeof(deb)=='undefined'){
        deb=0;
    }
    objetXHR= new creationXHR();
    var parametres = "deb="+deb;
    objetXHR.open("post", "../sites/imageController.php", "true");
    objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    objetXHR.onreadystatechange = listeimages;
    objetXHR.send(parametres);

}

// Affichage des images
function listeimages()
{
    if(objetXHR.readyState == 4)
    {
        if(objetXHR.status == 200)
        {
            var moncontenu=getElem('deb');
            deb = moncontenu.value;
            var lien = "";
            lien +="<center>";
              lien += "<a href=\"#\" onclick=\"showDialog();\">"+'<img border="none" src="img/addImage.png" onmouseout="this.src="img/addImage.png";" onmouseover="this.src="img/addImage_over.png";"style="float:right;" /> '+"</a><br/>";
            // resultat est un tableau de tableau
            var resultat = objetXHR.responseText;
            var array_JSON =  jsonParse(resultat);
            var id,nom_image,adresse;
            var i=0;
            for (var k in array_JSON) {
                id = array_JSON[k].id;
                if(typeof(id)!='undefined'){
                    nom_image = array_JSON[k].nom_image;
                    if(typeof(nom_image)!='undefined'){
                        i++;
                        adresse = adresseImage(nom_image,id);
                        lien += '<a href="'+adresse+'"rel="lightbox[roadtrip]" title="'+nom_image+'&nbsp;&nbsp;&nbsp;&lt;a href=&quot#&quot onClick = &quot javascript:supprimerImage('+id+');&quot;&gt;Supprimer&lt;/a&gt;" ><img src="'+adresse+'"  height="100" width="100" /></a>';
                    }
                }
            }
            if (i==0){
                lien += "Vous n'avez aucune image !";
            }
            lien += "</center></br>";
            if (deb!=0)
                lien += '<a href="#" onClick = "javascript:ImagesPrecedentes('+deb+');"><img border="none" src="img/previous.png" onmouseout="this.src=\'img/previous.png\';" onmouseover="this.src=\'img/previous_over.png\';" style="float:left;"/></a>';
            if (i==15)
                lien +='<a href="#" onClick = "javascript:ImagesSuivantes('+deb+');"><img border="none" src="img/next.png" onmouseout="this.src=\'img/next.png\';" onmouseover="this.src=\'img/next_over.png\';"style="float:right;" />  </a>';


           lien+='<br/>';
           document.getElementById("center").innerHTML = lien;
        }
    }
}
// methode style getter setter generique
function getElem(nom) {
    var Objdest=(document.getElementById) ? document.getElementById(nom):
    eval("document.all[nom]");
    return Objdest;
}

// envoie de la requete pour supprimer une image
function supprimerImage(id){
    if(typeof(id)!='undefined'){
        if (confirm('Etes-vous sûr de vouloir supprimer cette image ?')){
            objetXHR= new creationXHR();
            var parametres = "id="+id;
            objetXHR.open("post", "../sites/imageController.php", "true");
            objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            objetXHR.onreadystatechange = validSup;
            objetXHR.send(parametres);
        }
    }
}
// on reaffiche les images apres une suppression
function validSup()
{
    if(objetXHR.readyState == 4)
    {
        if(objetXHR.status == 200)
        {
            var resultat = objetXHR.responseText;
            if (!resultat){
                alert('Erreur');
            }
            var moncontenu=getElem('deb');
            deb = moncontenu.value;
            afficheImage(deb);

        }
    }
}

// donne l'indice suivant a deb'
function ImagesSuivantes(deb){
    deb+=15;
    document.getElementById("deb").value=deb;
    afficheImage(deb);
}

// donne l'indice precedent a deb'
function ImagesPrecedentes(deb){
    if (deb >=15){
        deb -=15;
    }else{
        deb=0;
    }
    document.getElementById("deb").value=deb;
    afficheImage(deb);
}

// retourne le nom sous lequel est stocké l image sur le disque
function adresseImage(nom,id)
{
    var taille = nom.length;
    var res = '../image/image/'+id+'.'+nom.substring(taille-3, taille);
    return res;
}
