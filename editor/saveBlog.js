function saveTitre()
{
    var titre = fieldTitre.getCleanContents();
    // on récupère l'index de la catégorie sélectionnée
    var id_categ_billet = document.getElementById("categ_billet").selectedIndex;
    var categ_billet = (document.getElementById("categ_billet")[id_categ_billet]).id;

    // creation de l'objet XHR qui envoie la requete
    objetXHRSaveTitre = creationXHR();
    // on ouvre une requete asynchrone POST vers le script adresseScript
    objetXHRSaveTitre.open("post", "../blog/edit.php", true);
    // on appelle saveTitreReturn lorsque la requete est terminée
    objetXHRSaveTitre.onreadystatechange = saveTitreReturn;
    // on envoie des données POST
    objetXHRSaveTitre.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=modifTitre&id=" + idBillet + "&titre=" + titre + "&categ_billet=" + categ_billet;
    // envoi du titre en parametre !
    objetXHRSaveTitre.send(parametres);


}

function saveContenu()
{
    var contenu = fieldContenu.getCleanContents();
    // on récupère l'index de la catégorie sélectionnée
    var id_categ_billet = document.getElementById("categ_billet").selectedIndex;
    var categ_billet = (document.getElementById("categ_billet")[id_categ_billet]).id;
    // creation de l'objet XHR qui envoie la requete
    objetXHRSaveContenu = creationXHR();
    // on ouvre une requete asynchrone POST vers le script adresseScript
    objetXHRSaveContenu.open("post", "../blog/edit.php", true);
    // on appelle saveTitreReturn lorsque la requete est terminée
    objetXHRSaveContenu.onreadystatechange = saveContenuReturn;
    // on envoie des données POST
    objetXHRSaveContenu.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "action=modifContenu&id=" + idBillet + "&contenu=" + contenu + "&categ_billet=" + categ_billet;
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

	    reloadMenu(idBillet);

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

function supprimerBillet()
{
    var confirmation = confirm("Voulez-vous vraiment supprimer ce billet ?");
    if(confirmation)
    {
	objetXHRSuppr = creationXHR();
	objetXHRSuppr.open("post", "../blog/edit.php", true);
	objetXHRSuppr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var parametres = "action=supprBillet&id=" + idBillet;
	objetXHRSuppr.send(parametres);
	objetXHRSuppr.onreadystatechange = SupprimeRetour;
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

    function SupprimeRetour(){
	if(objetXHRSuppr.readyState == 4)
	{
	    if(objetXHRSuppr.status == 200)
	    {
		var nomSite = recuperationNomSite();
		document.location="./blog.php?action=edit&site=" + nomSite;
	    }
	}
    }

function supprimerCategorie()
{
    var confirmation = confirm("Voulez-vous vraiment supprimer cette catégorie ?");
    if(confirmation)
    {
	objetXHRSuppr = creationXHR();
	objetXHRSuppr.open("post", "../blog/edit.php", true);
	objetXHRSuppr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	var parametres = "action=supprCategorie&id=" + document.getElementById("idcat").innerHTML;
	objetXHRSuppr.send(parametres);
	objetXHRSuppr.onreadystatechange = SupprimeRetour;
    }
}

function afficheSauvCate(){
    document.getElementById("saveCate").style.visibility='visible';
    document.getElementById("annulCate").style.visibility='visible';
}
function cacherSauvCate(){
    document.getElementById("saveCate").style.visibility='hidden';
    document.getElementById("annulCate").style.visibility='hidden';
}

function saveCate()
{
    saveContenu();
    cacherSauvCate();
}

function reloadMenu(id_billet)
{
    objetXHRSave = creationXHR();
    objetXHRSave.open("post", "blog.php", true);
    objetXHRSave.onreadystatechange = afficheMenuBlog;
    objetXHRSave.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var parametres = "id_billet=" + id_billet + "&ac=2";
    objetXHRSave.send(parametres);
}


function afficheMenuBlog()
{
    if(objetXHRSave.readyState == 4)
    {
	if(objetXHRSave.status == 200)
	{
	    var resultat = objetXHRSave.responseText;
	    document.getElementById("droite").innerHTML = resultat;
	}
    }
}
