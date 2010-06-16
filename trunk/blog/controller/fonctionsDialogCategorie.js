function showCategDialog()
{
    dialogCateg = new goog.ui.Dialog();
    var contenuFormCateg = document.getElementById("categ").innerHTML;
    dialogCateg.setContent(contenuFormCateg);
    document.getElementById("categ").innerHTML = "";
    dialogCateg.setTitle("Ajouter une catégorie");
    dialogCateg.setVisible(true);

    goog.events.listen(dialogCateg, goog.ui.Dialog.EventType.SELECT, function(e){
	    insertCategSite(e.key, contenuFormCateg);
	    });

    goog.events.listen(window, 'unload', function(){
	    goog.events.removeAll();
	    });
}

function insertCategSite(action, contenuFormCateg)
{
    // si l'utilisateur a cliqué sur OK
    if(action == "ok")
    {
	// on créé un objet XHR qui va envoyer la requete d'insertion au script PHP
	objetXHRInsertCateg = creationXHR();
	objetXHRInsertCateg.open("post","edit.php", true);
	objetXHRInsertCateg.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	// on charge le nouveau titre
	var titre = document.getElementById("insert_titre_categ").value;

	// on charge le nouveau libelle
	var description = document.getElementById("insert_libelle_categ").value;

	// on charge le nomSite (input caché) pour connaitre le site à modifier
	var nom_site = document.getElementById("nomSite").value;

	// on concatène tous ces paramètres
	var parametres = "action=insertCategorie&nom_site=" + nom_site  + "&titre_categ=" + titre + "&description_categ=" + description;

	// on envoie la requete au script PHP
	objetXHRInsertCateg.send(parametres);
	// on recharge la page pour éventuellement charger la nouvelle liste de catégories
	location.reload();
    }
    // on remet le code du formulaire dans la zone de la page html
    document.getElementById("categ").innerHTML = contenuFormCateg;
}
