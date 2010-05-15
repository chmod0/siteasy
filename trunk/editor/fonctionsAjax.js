/*
  fonction Ajax permettant de créer un objet XMLHttpRequest
  compatible avec tous les navigateurs supérieurs à IE5
 */
function creationXHR()
{
	var resultat=null;
	try
	{ //test pour les navigateurs Mozilla, Opera...
		resultat = new XMLHttpRequest();
	} catch(Error)
	{
		try
		{ // test pour les navigateurs IE > 5.0
			resultat = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(Error)
		{
			try
			{ // test pour IE 5.0
				resultat = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(Error)
			{
				resultat = null;
			}
		}
	}
	return resultat; // retourne l'objet XHR créé
}

/*
   fonction permettant de supprimer du contenu positionné dans le bloc "element"
   cette fonction respecte les normes W3C et est plus propre que innerHTML
*/
function supprimerContenu(element)
{
	if(element != null)
	{
		while(element.firstChild)
		{
			element.removeChild(element.firstChild);
		}
	}
}

/**
  fonction permettant de remplacer le contenu d'un bloc "id"
  il affecte uniquement du texte
  respecte les normes W3C (mieux que innerHTML)
*/
function remplacerContenu(id, texte)
{
	var element = document.getElementById(id);
	if(element != null)
	{
		supprimerContenu(element);
		var nouveauContenu = document.createTextNode(texte);
		element.appendChild(nouveauContenu);
	}
}

/**
  fonction qui retourne le contenu d'un bloc "id"
  appelle getElementById(id).value et encode ce resultat
*/
function codeContenu(id)
{
	var content = document.getElementById(id).value;
	return encodeURIComponent(content);
}
