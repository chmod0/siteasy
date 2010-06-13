function saveTitre()
{
    var titre = fieldTitre.getCleanContents();

    // creation de l'objet XHR qui envoie la requete
    objetXHRSaveTitre = creationXHR();
    // on ouvre une requete asynchrone POST vers le script php
    objetXHRSaveTitre.open("post", "../page/moteur.php", true);
    // on appelle saveTitreReturn lorsque la requete est terminée
    objetXHRSaveTitre.onreadystatechange = saveTitreReturn;
    // on envoie des données POST
    objetXHRSaveTitre.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=ediTitre&num=" + numPage + "&titre=" + titre;
    // envoi du titre en parametre !
    objetXHRSaveTitre.send(parametres);
    reloadMenu(numPage);

}




function reloadMenu(numPage)
{
    objetXHRSave = creationXHR();
    objetXHRSave.open("post", "controller.php", true);
    objetXHRSave.onreadystatechange = afficheMenu;
    objetXHRSave.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "num_page=" + numPage + "&ac=2";
    objetXHRSave.send(parametres);
}


function afficheMenu()
{
    if(objetXHRSave.readyState == 4)
    {
	if(objetXHRSave.status == 200)
	{
	     var resultat = objetXHRSave.responseText;
	     var menus = resultat.split("&|-|&");
	     document.getElementById("navigation").innerHTML = menus[0];
	     document.getElementById("pagesite").innerHTML = menus[1];
	}
    }
}




function saveContenu()
{
    var contenu = fieldContenu.getCleanContents();

    // creation de l'objet XHR qui envoie la requete
    objetXHRSaveContenu = creationXHR();
    // on ouvre une requete asynchrone POST vers le script PHP
    objetXHRSaveContenu.open("post", "../page/moteur.php", true);
    // on appelle saveTitreReturn lorsque la requete est terminée
    objetXHRSaveContenu.onreadystatechange = saveContenuReturn;
    // on envoie des données POST
    objetXHRSaveContenu.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=ediContenu&num=" + numPage + "&contenu=" + contenu;
    // envoi du titre en parametre !
    objetXHRSaveContenu.send(parametres);

}

function saveTitreReturn()
{
    if(objetXHRSaveTitre.readyState == 4)
    {
	if(objetXHRSaveTitre.status == 200)
	{
	    desactiverChamp(fieldTitre);
	}
    }

}

function saveContenuReturn()
{
    if(objetXHRSaveContenu.readyState == 4)
    {
	if(objetXHRSaveContenu.status == 200)
	{
	    desactiverChamp(fieldContenu);
	}
    }
}

function supprimerPage()
{
    var confirmation = confirm("Voulez-vous vraiment supprimer cette page ?");
    if(confirmation)
    {

	objetXHRSupprPage = creationXHR();
	objetXHRSupprPage.open("post", "../page/moteur.php", true);
	objetXHRSupprPage.onreadystatechange = supprPageReturn;
	objetXHRSupprPage.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var parametres = "action=Sup&num=" + numPage;
	objetXHRSupprPage.send(parametres);
    }
}

function supprPageReturn()
{
    if(objetXHRSupprPage.readyState == 4)
    {
	if(objetXHRSupprPage.status == 200)
	{
	    var reponse = objetXHRSupprPage.responseText;
	    
	    if(reponse != "OK")
	    {
		alert("Impossible de supprimer cette Page");
	    }else{
		// on retourne à l'acceuil du site si on a supprimé
		var nom_site = document.getElementById("nomSite").value;
		document.location="./index.php?site="+nom_site;
		
	    }
	}
    }
}


