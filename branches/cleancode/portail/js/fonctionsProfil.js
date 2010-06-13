// fonction d'envoi de la requete XHR pour obtenir plus d'infos sur un site
function detailsSite(nomsite)
{
    objetXHRDetails = new creationXHR();
    var parametres = "nomSite=" + nomsite + "&action=detailsSite";
    objetXHRDetails.open("post", "../sites/SiteController.php", "true");
    objetXHRDetails.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    objetXHRDetails.onreadystatechange = afficheDetails;
    objetXHRDetails.send(parametres);
}

// lorsque la requete est terminée, cette fonction affiche les resultats
function afficheDetails()
{
    if(objetXHRDetails.readyState == 4)
    {
        if(objetXHRDetails.status == 200)
        {
            var resultat = objetXHRDetails.responseText;
            var array_JSON = eval('(' + resultat + ')');
		    
            var nom_site = array_JSON.nom_site;
            var mail = array_JSON.mail;
            var id_modele = array_JSON.id_modele;
            var id_design = array_JSON.id_design;
            var titre_site = array_JSON.titre_site;
            var desc_site = array_JSON.desc_site;
            var mots_cle = array_JSON.mots_cle;
            var categ_site = array_JSON.categ_site;

            var affichageDetails = "<b>Nom : </b>" + ((nom_site != null) ? nom_site : "") +
            "<br/><b>Titre : </b> " + ((titre_site != null) ? titre_site : "") +
            "<br/><b>Description : </b> " + ((desc_site != null) ? desc_site : "") +
            "<br/><b>Modèle : </b> " + ((id_modele != null) ? id_modele : "") +
            "<br/><b>Design : </b> " + ((id_design != null) ? id_design : "") +
            "<br/><b>Catégorie : </b>" + ((categ_site != null) ? categ_site : "") +
            "<br/><b>Mots clé : </b> " + ((mots_cle != null) ? mots_cle : "");

            document.getElementById("details").innerHTML = affichageDetails;
            document.getElementById("details").style.display = "block";
            document.getElementById("boutonModif").style.visibility = "visible";
            document.getElementById("boutonDelete").style.visibility = "visible";
            document.getElementById("boutonVisit").style.visibility = "visible";
            // on insère le bon lien dans le bouton modifier
            if(id_modele == "Blog")
            {
                document.getElementById("lienModif").href = "../blog/blog.php?site=" + nom_site+"&action=edit" ;
                document.getElementById("lienVisit").href = "../index.php/blog/" + nom_site ;
            }
            else if(id_modele == "Page")
            {
                document.getElementById("lienModif").href = "../page/moteur.php?site=" + nom_site + "&action=editMode";
                document.getElementById("lienVisit").href = "../index.php/page/" + nom_site ;
            }
            // et on affecte le bon evenement au bouton supprime
            document.getElementById("lienDelete").onclick = function()
            {
                supprSite(nom_site);
            };
        }
    }
}

function supprSite(nomsite)
{
    
    if(confirm("Voulez-vous vraiment supprimer définitivement ce site ??"))
    {
        objetXHRSuppr = creationXHR();
        var parametres = "nomSite=" + nomsite + "&action=supprSite";
        objetXHRSuppr.open("post", "../sites/SiteController.php", "true");
        objetXHRSuppr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        objetXHRSuppr.onreadystatechange = rechargePage;
        objetXHRSuppr.send(parametres);
    }
}

function rechargePage()
{
    if(objetXHRSuppr.readyState == 4)
    {
        if(objetXHRSuppr.status == 200)
        {
            location.reload();
        }
    }
}




// envoie la requete permettant de recuperer les 16 images suivant l indice deb de l utilisateur
function afficheImage(deb){
    
    if(typeof(deb)=='undefined'){
        deb=0;
    }
    objetXHR= new creationXHR();
    var parametres = "deb="+deb+"taille=15";
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
			document.getElementById("btnchangeable").innerHTML = "Accueil";
			document.getElementById("btnchangeable").href = "index.php";
            var moncontenu=getElem('deb');
            deb = moncontenu.value;
            var lien = "";
            lien +="<center>";
            lien += '<a href="#" onClick="showDialog();"><img border="none" src="img/addImage.png" onmouseout="this.src=\'img/addImage.png\';" onmouseover="this.src=\'img/addImage_over.png\';"/></a><br/><br/>';
           
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




