window.onload = init; // au chargement de la page, on appelle la fonction init()

/*
   fonction d'initialisation des evenements de la page
*/
function init()
{
	//envoi de la connexion en cliquant sur le bouton ou en soumettant le formulaire. appel à la fonction envoiConnexion
	document.getElementById("validConnex").onclick = envoiConnexion;
	document.getElementById("formConnex").onSubmit = envoiConnexion;
	// envoi de l'inscription en cliquant sur le bouton ou en soumettant le formulaire. appel à la fonction envoiInscription
	document.getElementById("validInscr").onclick = envoiInscription;
	document.getElementById("formInscr").onSubmit = envoiInscription;

	//appel de la fonction de vérification du mot de passe en cliquant sur le lien concerné
	document.getElementById("oubliPass").onclick = passwordOublie;

	/* -----------------------------------------------------
	   
	   GESTION DES CHAMPS LOGIN ET MOT DE PASSE DE CONNEXION

	   Un texte indicateur est mis en valeur quand l'utilisateur n'a rien tapé
	   il doit etre gris
	   dès que l'utilisateur tape qqc, ce texte est supprimé et le texte passe en noir

	   -----------------------------------------------------
	*/

	// champ login
	document.getElementById("login").onfocus = function (){
		if(document.getElementById("login").value == "Adresse e-mail")
			document.getElementById("login").value = "";
		document.getElementById("login").style.color = "black";
	};

	document.getElementById("login").onblur = function (){
		if(document.getElementById("login").value == "")
		{
			document.getElementById("login").value = "Adresse e-mail";
			document.getElementById("login").style.color = "grey";
		}
	};

	//champ mot de passe
	document.getElementById("pass").onfocus = function (){
		if(document.getElementById("pass").value == "Mot de passe")
			document.getElementById("pass").value = "";	
	
		document.getElementById("pass").style.color = "black";
	};
	document.getElementById("pass").onblur = function (){
		if(document.getElementById("pass").value == "")
		{
			document.getElementById("pass").value = "Mot de passe";
			document.getElementById("pass").style.color = "grey";
		}
	};


	/* ---------------------------------------------------------
	   
	   GESTION DES CHAMPS EMAIL ET MOT DE PASSE DE L'INSCRIPTION

	   on valide les données à l'aide de fonctions pour qu'elles
	   soient compatibles au fonctionnement du site

	   ---------------------------------------------------------
	*/

	document.getElementById("motpasse").onfocus = validationMotPasse;
	document.getElementById("confpass").onfocus = validationConfPass;
	document.getElementById("email").onfocus = validationEmail;
}

/*
   fonction qui valide le contenu des champs de connexion et qui créé un objet XHR vers un script de connexion
*/
function envoiConnexion()
{
	// chargement des données
	var email = document.getElementById("login").value;
	var password = document.getElementById("pass").value;

	// vidage des champs d'erreurs
	document.getElementById("emailErrorMessageConnex").innerHTML = "";
	document.getElementById("passErrorMessageConnex").innerHTML = "";

	// cette regexp teste le format de l'email
	var regexp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+[a-zA-Z0-9]{2,4}$/;
	var correct = regexp.test(email);

	// creation d'un objet XHR si les données sont valides
	if(correct && password != "")
	{
		objetXHRConnex = new creationXHR();
		// paramètres à envoyer au script : email, password et action à effectuer
		var parametres = "email=" + email + "&password=" + password + "&action=connect";
		//on ouvre une requete asyncrhone POST, vers le script UserController.php
		objetXHRConnex.open("post", "../user/UserController.php", "true");
		// appel de la fonction finaliserConnexion à chaque changement d'etat de la requete
		objetXHRConnex.onreadystatechange = finaliserConnexion;
		// on indique qu'on envoie des données POST
		objetXHRConnex.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		// envoi des paramètres !
		objetXHRConnex.send(parametres);
	}
	else
	{
		// affichage des messages d'erreur si les champs ne sont pas bons
		if( ! correct)
			document.getElementById("emailErrorMessageConnex").innerHTML = "Adresse email inconnue";
		else
			document.getElementById("passErrorMessageConnex").innerHTML = "Tapez ici votre mot de passe";
	}
}

/*
   fonction appelée à chaque changement d'état de la requete XHR
   on redirige l'utilisateur vers la page voulue s'il est connecté
   sinon, affichage d'un message d'erreur
*/
function finaliserConnexion()
{
	// teste si on a bien recu la requete de reponse
	if(objetXHRConnex.readyState == 4)
	{
		// teste si le statut de la requete est OK
		if(objetXHRConnex.status == 200)
		{
			var result = objetXHRConnex.responseText;
			// si la connexion s'est bien déroulée
			if(result == "true")
			{
				window.location = "index.php"; // redirection vers la page php de profil
			}
			else
			{
				// mauvaise adresse email : affichage d'un message d'erreur sous le champ
				if(result == "falseMail")
				{
					document.getElementById("emailErrorMessageConnex").innerHTML = "Email inexistant sur le portail";
				}
				// mauvais mot de passe: affichage d'un message d'erreur sous le champ
				else
				{
					document.getElementById("passErrorMessageConnex").innerHTML = "Mot de passe incorrect !";
				}
			}
		}
	}
}

/*
   fonction appelée au click sur le bouton de connexion
   on teste toutes les données pour vérifier si elles sont valides
   on créé un objet XMLHttpRequest si tout va bien
*/
function envoiInscription()
{
	// récupération du contenu des champs
	var pass1 = document.getElementById("motpasse").value;
	var pass2 = document.getElementById("confpass").value;
	var email = document.getElementById("email").value;

	// regexp qui teste si l'email est valide
	var regexp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+[a-zA-Z0-9]{2,4}$/;
	var adresse_correcte = regexp.test(email);

	var correct = (pass1 == pass2 && pass1.length >= 3 && adresse_correcte);

	// si tout va bien, creation d'un objet XHR
	if(correct)
	{
		objetXHRInscr = new creationXHR();
		// parametres : email, password, action à effectuer
		var parametres = "email=" + email + "&password=" + pass1 + "&action=addUser";
		// on ouvre une requete asynchrone POST vers le script UserController.php
		objetXHRInscr.open("post", "../user/UserController.php", "true");
		// la fonction finaliserInscription sera appelée à chaque changement d'etat de la requete
		objetXHRInscr.onreadystatechange = finaliserInscription;
		// indication au browser de l'envoi d'une requete
		objetXHRInscr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		// envoi de la requete !
		objetXHRInscr.send(parametres);
	}
	// sinon, on affiche des messages d'erreur
	else
	{
		if (!adresse_correcte){
			// l'adresse email est invalide, on affiche un message sous le champ et on applique le champ en rouge
			document.getElementById("emailErrorMessage").innerHTML = "Adresse email invalide";
			document.getElementById("emailErrorMessage").style.display = "block";

		}else{
			document.getElementById("emailErrorMessage").style.display = "none";
		}
		if (pass1 == ""){
			document.getElementById("passErrorMessage").innerHTML = "Mot de passe invalide";
			document.getElementById("passErrorMessage").style.display = "block";

		}else{
			document.getElementById("passErrorMessage").style.display = "none";
		}
		if (pass1 != pass2){
			// les 2 mots de passe sont différents, on affiche le mot de passe de confirmation en rouge
			document.getElementById("confPassErrorMessage").innerHTML = "Confirmation invalide";
			document.getElementById("confPassErrorMessage").style.display = "block";
		}else{
			document.getElementById("confPassErrorMessage").style.display = "none";
		}
	}
}

/*
   fonction de validation du bon format de l'email
   utilise une regexp et affiche des couleurs/messages en fonction du resultat
   fonction recursive qui s'appelle toutes les 500 ms
   elle est initialement appelée lors d'un focus sur le champ email
*/
function validationEmail()
{
	// on charge le contenu du champ
	var email = document.getElementById("email").value;
	// regexp qui contient le format d'un email
	var regexp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+[a-zA-Z0-9]{2,4}$/;
	var adresse_correcte = regexp.test(email);
	if(!adresse_correcte)
	{		
		document.getElementById("email").style.backgroundColor='#ffffff';
	}
	else
	{
		document.getElementById("email").style.backgroundColor='#d6ffbd';
		document.getElementById("emailErrorMessage").style.display = "none";
	}
	setTimeout("validationEmail()", 500);
}

function validationMotPasse()
{
	var pass = document.getElementById("motpasse").value;
	if(pass.length < 3)
	{
		document.getElementById("motpasse").style.backgroundColor='#ffffff';
	}
	else
	{
		document.getElementById("motpasse").style.backgroundColor='#d6ffbd';
		document.getElementById("passErrorMessage").style.display = "none";
	}
	setTimeout("validationMotPasse()", 500);
}

function validationConfPass()
{
	var pass1 = document.getElementById("motpasse").value;
	var pass2 = document.getElementById("confpass").value;
	if(pass2.length < 3 || pass1 != pass2)
	{
		document.getElementById("confpass").style.backgroundColor='#ffffff';
	}
	else
	{
		document.getElementById("confpass").style.backgroundColor='#d6ffbd';
		document.getElementById("confPassErrorMessage").style.display = "none";

	}
	setTimeout("validationConfPass()", 500);
}

function finaliserInscription()
{
	if(objetXHRInscr.readyState == 4)
	{
		if(objetXHRInscr.status == 200)
		{
			var inscriptionValide = objetXHRInscr.responseText;
			if(inscriptionValide == "true")
			{
				var html = "<div id=\"details\" style=\"text-align:center\"><h4>Votre inscription est validée.</h4><br />Un mail vous a été envoyé à l'adresse fournie.<br /><h4>Commencez à créer et à modifier vos sites !</h4><br /><a href=\"index.php\"><img border=\"none\" src=\"img/cliquez.png\" alt=\"Cliquez ici\" onmouseout=\"this.src='img/cliquez.png';\"  onmouseover=\"this.src='img/cliquez_over.png';\" value=\"Cliquez ici\" /></a></div><br />";
				document.getElementById("center").innerHTML = html;
			}
			else
			{
				document.getElementById("emailErrorMessage").innerHTML = "Cette adresse email est déjà utilisée sur le portail";
			}
		}
	}
}


function passwordOublie()
{
    /*
     * modification du code html pour afficher le formulaire requis
     */
    var elementTop = document.getElementById("top");
    elementTop.innerHTML = '<div style="text-align:center; font-size:0.9em;">Récupération du mot de passe</div><br />'+
							'<input type="text" id="mail" value="Adresse e-mail"/> <span id="imgPassOubli"></span>' +
							'<a href="#"><img style="border:none; float:right" src="img/recup.png" alt="Récupérer le mot de passe" onmouseout="this.src=\'img/recup.png\';" onmouseover="this.src=\'img/recup_over.png\';" id="envoiPass" /></a><br /><br/>' +
							'<div style="text-align:right; font-size:0.7em"><a href="index.php">annuler</a></style>';

				
				
    /**
     * ajout des évènements sur le champs email
     */
    document.getElementById("mail").onfocus = function (){
	if(document.getElementById("mail").value == "Adresse e-mail")
		document.getElementById("mail").value = "";
	document.getElementById("mail").style.color = "black";
	// évenement appelle une fonction qui vérifie si l'adresse est dans la BDD
	mailExists();
    };

    document.getElementById("mail").onblur = function (){
	if(document.getElementById("mail").value == "")
	{
        	document.getElementById("mail").value = "Adresse e-mail";
		document.getElementById("mail").style.color = "grey";
	}
    };

    // évenement qui appelle une fonction qui créé une requete XHR après clique sur le bouton
    document.getElementById("envoiPass").onclick = envoiPassOublie;
}

function envoiPassOublie()
{
	
    var emailChamp = document.getElementById("mail").value;
    if(emailChamp != null)
    {
	objetXHREnvoiPass = creationXHR();
	var parametres = "mail=" + emailChamp + "&action=passwordUser";
	objetXHREnvoiPass.open("post", "../user/UserController.php", "true");
	objetXHREnvoiPass.onreadystatechange = finaliserEnvoiPass;
	objetXHREnvoiPass.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	objetXHREnvoiPass.send(parametres);
    }
}

function finaliserEnvoiPass()
{
    if(objetXHREnvoiPass.readyState == 4)
    {
	if(objetXHREnvoiPass.status == 200)
	{
	    var response = objetXHREnvoiPass.responseText;
	    if(response == "true")
	    {
		alert("Envoyé");
	    }
	    else
	    {
		alert("Bug");
	    }
	}
    }
}

// fonction qui vérifie en permanence si l'utilisateur est dans la BDD
function mailExists()
{
    var emailChamp = document.getElementById("mail").value;
    if(emailChamp != "")
    {
	objetXHRPassOubli = creationXHR();

	var parametres = "email=" + emailChamp + "&action=userExists";
	objetXHRPassOubli.open("post", "../user/UserController.php", "true");
	objetXHRPassOubli.onreadystatechange = finaliserMailExists;
	objetXHRPassOubli.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	objetXHRPassOubli.send(parametres);
    }
    setTimeout("mailExists()", 1000);
}

// fonction qui affiche que l'utilisateur est dans la bdd
function finaliserMailExists()
{
    if(objetXHRPassOubli.readyState == 4)
    {
	if(objetXHRPassOubli.status == 200)
	{
	    var response = objetXHRPassOubli.responseText;
	    // si l'email est dans la bdd, on affiche un fond vert pour l'input et le bouton visible
	    if(response == "true")
	    {
		document.getElementById("mail").style.backgroundColor='#d6ffbd';
		document.getElementById("imgPassOubli").innerHTML='<img src="img/valide.gif" />';
	    }
	    // sinon, on affiche un fond bland pour l'input et on cache le bouton
	    else
	    {
		document.getElementById("mail").style.backgroundColor = '#ffffff';
		document.getElementById("imgPassOubli").innerHTML='<img src="img/loading.gif" />';
	    }
	}
    }
}
