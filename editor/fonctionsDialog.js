// affiche le Dialog contenant le formulaire de modification des infos du site
function showInfosDialog()
{
    // création du dialog
    dialogReglages = new goog.ui.Dialog();
    // on charge le code du formulaire depuis la page
    var contenuFormInfos = document.getElementById("infos").innerHTML;
    // on met ce code dans le dialog
    dialogReglages.setContent(contenuFormInfos);
    // on vide la zone qui contenait le code du formulaire (pour éviter les conflits d'id)
    document.getElementById("infos").innerHTML = "";
    dialogReglages.setTitle("Modification des informations");
    dialogReglages.setVisible(true);

    // si l'utilisateur valide ou quitte le dialog, on appelle la fonction envoyerModificationsSite
    goog.events.listen(dialogReglages, goog.ui.Dialog.EventType.SELECT, function(e){
	    envoyerModificationsSite(e.key, contenuFormInfos);
	    });

    goog.events.listen(window, 'unload', function(){
	    goog.events.removeAll();
	    });
}

function envoyerModificationsSite(action, contenuFormInfos)
{
    // si l'utilisateur a cliqué sur OK
    if(action == "ok")
    {
	// on créé un objet XHR qui va envoyer la requete de sauvegarde au script PHP
	objetXHRModifInfo = creationXHR();
	objetXHRModifInfo.open("post","../sites/SiteController.php", true);
	objetXHRModifInfo.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	// on charge le nouveau titre
	var titre = document.getElementById("modif_titre_site").value;

	// on charge la nouvelle catégorie
	var cat = document.getElementById("modif_categ_site").value;

	// on charge les nouveaux mots clé
	var mots = document.getElementById("modif_mots_cle_site").value;

	/* on charge la nouvelle description depuis la liste d'options
	   il s'agit d'une liste de titre_site_categ, clé unique de la table sitecateg
	   il n'est donc pas nécessaire de parcourir tout l'élement, element.value retourne le titre_site_categ
	   */
	var desc = document.getElementById("modif_description_site").value;

	/* on charge le nouveau design
	   il s'agit d'une liste de libelle_design, on doit donc récupérer l' id_design du design sélectionné
	   en parcourant l'element DOM et ses fils
	   */
	// on récupère l'element qui contient la liste <select>
	var listeDesigns = document.getElementById("modif_design_site").childNodes;
	// on initialise la variable qui contiendra l'id du design sélectionné
	var design = 0;
	for(var i = 0; i < listeDesigns.length; i++)
	{
	    if(listeDesigns[i].selected == true)
	    {
		design = listeDesigns[i].id;
	    }
	}

	// on charge le nomSite (input caché) pour connaitre le site à modifier
	var nom = document.getElementById("nomSite").value;

	// on concatène tous ces paramètres
	var parametres = "action=modifDetails&nom_site=" + nom  + "&titre_site=" + titre + "&categ_site=" +cat + "&mots_cle=" + mots + "&desc_site=" + desc + "&design_site=" + design;

	// on envoie la requete au script PHP
	objetXHRModifInfo.send(parametres);
	// on recharge la page pour éventuellement charger le nouveau design ou le nouveau titre
	location.reload();
    }
    // on réinitialise la zone "infos" qui contenait le formulaire d'infos, mais que l'on a supprimée pour éviter les conflits
    document.getElementById("infos").innerHTML = contenuFormInfos;
}


function showImageDialog()
{
    afficheImage(0);

}



//=========================

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
	    var deb = moncontenu.value;
	    var num_page = getElem('num_page').value;
	    var lien = "<h2>Clicker pour ajouter l'image de votre choix</h2><br/><div>";
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
			lien += '<a href="#" onClick ="ajoutImagePage('+id+','+num_page+')" ><img src="'+adresse+'"  height="100" width="100" /></a>';
		    }
		}
	    }
	    if (i==0){
		lien += "Vous n'avez aucune image !";
	    }
	    lien += '</div><br/>';
	    if (deb!=0)
		lien += '<a href="#" onClick = "javascript:ImagesPrecedentes('+deb+');"><img border="none" src="../portail/img/previous.png" onmouseout="this.src=\'../portail/img/previous.png\';" onmouseover="this.src=\'../portail/img/previous_over.png\';" style="float:left;"/></a>';
	    if (i==15)
		lien +='<a href="#" onClick = "javascript:ImagesSuivantes('+deb+');"><img border="none" src="../portail/img/next.png" onmouseout="this.src=\'../portail/img/next.png\';" onmouseover="this.src=\'../portail/img/next_over.png\';"style="float:right;" />  </a>';

	    lien+='<br/><br/>';

	    dialog = new goog.ui.Dialog();
	    dialog.setContent(lien);
	    dialog.setTitle("Ajouter une Image");
	    dialog.setButtonSet(null);
	    dialog.setVisible(true);

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


// donne l'indice suivant a deb'
function ImagesSuivantes(deb){
    deb+=15;
    dialog.setVisible(false);
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
    dialog.setVisible(false);
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

function ajoutImagePage(id,num_page){
    objetXHR= new creationXHR();
    var parametres = "num_image="+id+"&num_page="+num_page;
    objetXHR.open("post", "controller.php", "true");
    objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    objetXHR.send(parametres);
    objetXHR.onreadystatechange = reload;

}

function dialogSupImage(id,num_page){
    if (confirm("Retirer l'image de cette page ?")){
	objetXHR= new creationXHR();
	var parametres = "num_image="+id+"&num_page="+num_page+"&ac=1";
	objetXHR.open("post", "controller.php", "true");
	objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	objetXHR.send(parametres);
	objetXHR.onreadystatechange = reload;
    }
}


function reload()
{
    if(objetXHR.readyState == 4)
    {
	if(objetXHR.status == 200)
	{
	    location.reload();
	}
    }
}


//===============================+> De meme pour les billet mais avec 1image par billet !

function ajoutImageBlog(id,num_bil){
    objetXHR= new creationXHR();
    var parametres = "num_image="+id+"&id_billet="+num_bil;
    objetXHR.open("post", "image.php", "true");
    objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    objetXHR.send(parametres);
    objetXHR.onreadystatechange = reload;

}
function dialogSupImageBlog(num_bil){
    if (confirm("Retirer l'image de ce billet ?")){
	objetXHR= new creationXHR();
	var parametres = "id_billet="+num_bil+"&ac=1";
	objetXHR.open("post", "image.php", "true");
	objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	objetXHR.send(parametres);
	objetXHR.onreadystatechange = reload;
    }
}

//===============================+>


function showImageDialogBlog()
{
    afficheImageBlog(0);

}
function afficheImageBlog(deb){

    if(typeof(deb)=='undefined'){
	deb=0;
    }
    objetXHR= new creationXHR();
    var parametres = "deb="+deb;
    objetXHR.open("post", "../sites/imageController.php", "true");
    objetXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    objetXHR.onreadystatechange = listeimagesBlog;
    objetXHR.send(parametres);

}

// Affichage des images
function listeimagesBlog()
{
    if(objetXHR.readyState == 4)
    {
	if(objetXHR.status == 200)
	{
	    var moncontenu=getElem('deb');
	    var deb = moncontenu.value;
	    var idB = getElem('id').value;
	    var lien = "<h2>Cliquez pour ajouter l'image de votre choix</h2><br/><div>";
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
			lien += '<a href="#" onClick ="ajoutImageBlog('+id+','+idB+')" ><img src="'+adresse+'"  height="100" width="100" /></a>';
		    }
		}
	    }
	    if (i==0){
		lien += "Vous n'avez aucune image !";
	    }
	    lien += '</div><br/>';
	    if (deb!=0)
		lien += '<a href="#" onClick = "javascript:ImagesPrecedentes('+deb+');"><img border="none" src="../portail/img/previous.png" onmouseout="this.src=\'../portail/img/previous.png\';" onmouseover="this.src=\'../portail/img/previous_over.png\';" style="float:left;"/></a>';
	    if (i==15)
		lien +='<a href="#" onClick = "javascript:ImagesSuivantes('+deb+');"><img border="none" src="../portail/img/next.png" onmouseout="this.src=\'../portail/img/next.png\';" onmouseover="this.src=\'../portail/img/next_over.png\';"style="float:right;" />  </a>';

	    lien+='<br/><br/>';

	    dialog = new goog.ui.Dialog();
	    dialog.setContent(lien);
	    dialog.setTitle("Ajouter une Image");
	    dialog.setButtonSet(null);
	    dialog.setVisible(true);

	}
    }
}
