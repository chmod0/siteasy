window.onload = init;

function init(){
	document.getElementById("nom_site").onfocus = validationNomSite;
	//document.getElementById("envoi-infos").onclick = soumettre_infos;

}
function soumettre_infos(){

	var name = document.getElementById("nom_site").value;
	var regexp = /^[a-zA-Z0-9_\-]+$/;
	var correct = regexp.test(name);
	if(correct && !prisparunputindautresite){
		document.formInfos.submit();
	}else {
		document.getElementById("pathSite").style.color = '#ad1b1b';
		document.getElementById("pathSite").innerHTML='Nom invalide';
		alert("Nom du site invalide ou déjà pris");
	}
}

function validationNomSite(){

	nomsite = document.getElementById("nom_site").value;
	var regexp = /^[a-zA-Z0-9_\-]+$/;
	var correct = regexp.test(nomsite);
	nomSiteExists();
	
	document.getElementById("pathSite").innerHTML='www.portail.com/<b>' + nomsite + '</b>';

	if(correct){
		document.getElementById("pathSite").style.color = '#535353';
		document.getElementById("nom_site").style.backgroundColor='#d6ffbd';
		
	}else{
		document.getElementById("nom_site").style.backgroundColor='#ffffff';
		document.getElementById("pathSite").style.color = '#ad1b1b';
		document.getElementById("pathSite").innerHTML='Nom invalide';
	}

	setTimeout("validationNomSite()", 500);
}

function nomSiteExists(){

	objetXHRNomSiteExists = creationXHR();
	var parametre = "nomSite="+nomsite+"&action=nomSiteExists";
	objetXHRNomSiteExists.open("post", "../sites/SiteController.php", true);
	
	objetXHRNomSiteExists.onreadystatechange = finaliserNomSiteExists;
	objetXHRNomSiteExists.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	objetXHRNomSiteExists.send(parametre);
	

}

function finaliserNomSiteExists()
{
	if(objetXHRNomSiteExists.readyState == 4)
	{
		if(objetXHRNomSiteExists.status == 200)
		{
			var result = objetXHRNomSiteExists.responseText;
			if(result == "true")
			{
				document.getElementById("nom_site").style.backgroundColor='#ffffff';
				document.getElementById("pathSite").style.color = '#ad1b1b';
				document.getElementById("pathSite").innerHTML = 'Nom deja pris !';	
					prisparunputindautresite =true;
				return true;
			
			}
			else
			{
				return false;
			}
		}
	}
	return false;
}
