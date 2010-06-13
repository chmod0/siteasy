function insertTitre()
{
    // on récupère le contenu nettoyé du champ titre
    var titre = fieldTitre.getCleanContents();
    // on récupère le paramètre "site" passé à l'url
    var nomSite = recuperationNomSite();

    // creation de l'objet XHR qui envoie la requete
    objetXHRInsertTitre = creationXHR();
    // on ouvre une requete asynchrone POST vers le script moteur.php
    objetXHRInsertTitre.open("post", "../page/moteur.php", true);
    // on appelle insertTitreReturn lorsque la requete est terminée
    objetXHRInsertTitre.onreadystatechange = insertTitreReturn;
    // on envoie des données POST
    objetXHRInsertTitre.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=insertTitre&id=" + numPage + "&titre=" + titre + "&site=" + nomSite;
    // envoi du titre en parametre !
    objetXHRInsertTitre.send(parametres);

}

function insertContenu()
{
    // on récupère le contenu nettoyé du champ titre
    var contenu = fieldContenu.getCleanContents();
    // on récupère le paramètre "site" passé à l'url
    var nomSite = recuperationNomSite();

    // creation de l'objet XHR qui envoie la requete
    objetXHRInsertContenu = creationXHR();
    // on ouvre une requete asynchrone POST vers le script moteur.php
    objetXHRInsertContenu.open("post", "../page/moteur.php", true);
    // on appelle insertContenuReturn lorsque la requete est terminée
    objetXHRInsertContenu.onreadystatechange = insertContenuReturn;
    // on envoie des données POST
    objetXHRInsertContenu.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=insertContenu&id=" + numPage + "&contenu=" + contenu + "&site=" + nomSite;
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
	    if(numPage == '')
	    {
		var nouvelId = objetXHRInsertTitre.responseText;
		numPage = nouvelId;
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

	    if(numPage == '')
	    {
		var nouvelId = objetXHRInsertContenu.responseText;
		numPage = nouvelId;
	    }
	}
    }
}

function supprimerPage()
{
    if(numPage != '')
    {
	var confirmation = confirm("Voulez-vous vraiment supprimer ce billet ?");
	if(confirmation)
	{
	    objetXHRSupprPage = creationXHR();
	    objetXHRSupprPage.open("post", "../page/moteur.php", true);
	    objetXHRSupprPage.onreadystatechange = supprPageReturn;
	    objetXHRSupprPage.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    var parametres = "action=Sup&num=" + numPage + "&site=lol";
	    objetXHRSupprPage.send(parametres);
	}
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
		alert(reponse);
	    }
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
