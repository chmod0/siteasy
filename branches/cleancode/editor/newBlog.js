function insertTitre()
{
    // on récupère le contenu nettoyé du champ titre
    var titre = fieldTitre.getCleanContents();
    // on récupère l'index de la catégorie sélectionnée
    var categ_billet = document.getElementById("categ_billet").selectedIndex;
    // on récupère le paramètre "site" passé à l'url
    var nomSite = recuperationNomSite();

    // creation de l'objet XHR qui envoie la requete
    objetXHRInsertTitre = creationXHR();
    // on ouvre une requete asynchrone POST vers le script adresseScript
    objetXHRInsertTitre.open("post", "../blog/edit.php", true);
    // on appelle saveTitreReturn lorsque la requete est terminée
    objetXHRInsertTitre.onreadystatechange = insertTitreReturn;
    // on envoie des données POST
    objetXHRInsertTitre.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=insertTitre&id=" + idBillet + "&titre=" + titre + "&categ_billet=" + categ_billet + "&nom_site=" + nomSite;
    // envoi du titre en parametre !
    objetXHRInsertTitre.send(parametres);

}

function insertContenu()
{
    // on récupère le contenu nettoyé du champ titre
    var contenu = fieldContenu.getCleanContents();
    // on récupère l'index de la catégorie sélectionnée
    var categ_billet = document.getElementById("categ_billet").selectedIndex;
    // on récupère le paramètre "site" passé à l'url
    var nomSite = recuperationNomSite();

    // creation de l'objet XHR qui envoie la requete
    objetXHRInsertContenu = creationXHR();
    // on ouvre une requete asynchrone POST vers le script adresseScript
    objetXHRInsertContenu.open("post", "../blog/edit.php", true);
    // on appelle saveTitreReturn lorsque la requete est terminée
    objetXHRInsertContenu.onreadystatechange = insertContenuReturn;
    // on envoie des données POST
    objetXHRInsertContenu.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=insertContenu&id=" + idBillet + "&contenu=" + contenu + "&categ_billet=" + categ_billet + "&nom_site=" + nomSite;
    // envoi du titre en parametre !
    objetXHRInsertContenu.send(parametres);

}

function insertTitreReturn()
{
    if(objetXHRInsertTitre.readyState == 4)
    {
	if(objetXHRInsertTitre.status == 200)
	{
	    desactiverChamp(fieldTitre);
	    if(idBillet == '')
	    {
		var nouvelId = objetXHRInsertTitre.responseText;
		idBillet = nouvelId;
	    }
	}
    }

}

function insertContenuReturn()
{
    if(objetXHRInsertContenu.readyState == 4)
    {
	if(objetXHRInsertContenu.status == 200)
	{
	    desactiverChamp(fieldContenu);

	    if(idBillet == '')
	    {
		var nouvelId = objetXHRInsertContenu.responseText;
		idBillet = nouvelId;
	    }
	}
    }
}

function supprimerBillet()
{
    if(idBillet != '')
    {
	var confirmation = confirm("Voulez-vous vraiment supprimer ce billet ?");
	if(confirmation)
	{
	    objetXHRSupprBillet = creationXHR();
	    objetXHRSupprBillet.open("post", "../blog/edit.php", true);
	    objetXHRSupprBillet.onreadystatechange = supprBilletReturn;
	    objetXHRSupprBillet.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    var parametres = "action=supprBillet&id=" + idBillet;
	    objetXHRSupprBillet.send(parametres);
	}
    }
}

function supprBilletReturn()
{
    if(objetXHRSupprBillet.readyState == 4)
    {
	if(objetXHRSupprBillet.status == 200)
	{
	    alert("Le billet a été supprimé");
	}
    }
}

function recuperationNomSite()
{
    var parametresUrl = location.search.substring(1).split('&');
    var tabIndexParametres = [];
    for(var i = 0; i < parametresUrl.length; i++)
    {
	var tabCleValeur = parametresUrl[i].split('=');
	tabIndexParametres[tabCleValeur[0]] = tabCleValeur[1]; 
    }
    return tabIndexParametres['site'];
}
