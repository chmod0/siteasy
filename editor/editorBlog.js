function initFields(id)
{
    idBillet = id;
    fieldTitre = new goog.editor.SeamlessField("titre");
    fieldContenu = new goog.editor.SeamlessField("contenu");
    instancierToolbar();

    /**
     *
     * initialisation des évènements liés au titre
     *
     */
    var titre = document.getElementById("titre");
    // évnmt pour l'édition du titre
    goog.events.listen(titre, goog.events.EventType.CLICK, function() { activerChamp(fieldTitre);});
    // évnmt pour l'arret de l'édition
    document.getElementById("cancelTitreButton").onclick = function()
    {
	desactiverChamp(fieldTitre);
    };

    /**
     *
     * initialisation des évènements liés au contenu
     *
     */
    var contenu = document.getElementById("contenu");
    // évnmt pour l'édition du contenu
    goog.events.listen(contenu, goog.events.EventType.CLICK, function() { activerChamp(fieldContenu);});
    // évnmt pour l'arret de l'édition
    document.getElementById("cancelContenuButton").onclick = function()
    {
	desactiverChamp(fieldContenu);
    };

    // initialisation des evenements des boutons de sauvegarde
    // en fonction du type de page : insertion ou modif d'un billet
    if(idBillet != '')
    {
	document.getElementById("saveTitreButton").onclick = saveTitre;
	document.getElementById("saveContenuButton").onclick = saveContenu
    }
    else
    {
	document.getElementById("saveTitreButton").onclick = insertTitre;
	document.getElementById("saveContenuButton").onclick = insertContenu
    }
    /**
     * initialisation de l'évènement du bouton supprimer Billet 
     */

    document.getElementById("boutonSuppr").onclick = supprimerBillet;

}

function instancierToolbar()
{
    // Specify the buttons to add to the toolbar, using built in default buttons.
    var buttons = [
	goog.editor.Command.BOLD,
	goog.editor.Command.ITALIC,
	goog.editor.Command.UNDERLINE,
	goog.editor.Command.FONT_COLOR,
	goog.editor.Command.BACKGROUND_COLOR,
	goog.editor.Command.FONT_FACE,
	goog.editor.Command.FONT_SIZE,
	goog.editor.Command.LINK,
	goog.editor.Command.UNDO,
	goog.editor.Command.REDO,
	goog.editor.Command.UNORDERED_LIST,
	goog.editor.Command.ORDERED_LIST,
	goog.editor.Command.INDENT,
	goog.editor.Command.OUTDENT,
	goog.editor.Command.JUSTIFY_LEFT,
	goog.editor.Command.JUSTIFY_CENTER,
	goog.editor.Command.JUSTIFY_RIGHT,
	goog.editor.Command.SUBSCRIPT,
	goog.editor.Command.SUPERSCRIPT,
	goog.editor.Command.STRIKE_THROUGH,
	goog.editor.Command.REMOVE_FORMAT
	    ];
    myToolbar = goog.ui.editor.DefaultToolbar.makeToolbar(buttons,
	    goog.dom.$('outils'));
    myToolbar.setVisible(false);
}

function activerChamp(field)
{
    if(field.isUneditable())
    {
	// le field devient éditable
	field.makeEditable();
	// on place le curseur au début et on focus le champ
	field.focusAndPlaceCursorAtStart();

	// si le field est du contenu, on active la toolbar wysiwyg
	if(field == fieldContenu)
	{
	    // Create and register all of the editing plugins you want to use.
	    field.registerPlugin(new goog.editor.plugins.BasicTextFormatter());
	    field.registerPlugin(new goog.editor.plugins.RemoveFormatting());
	    field.registerPlugin(new goog.editor.plugins.UndoRedo());
	    field.registerPlugin(new goog.editor.plugins.ListTabHandler());
	    field.registerPlugin(new goog.editor.plugins.SpacesTabHandler());
	    field.registerPlugin(new goog.editor.plugins.EnterHandler());
	    field.registerPlugin(new goog.editor.plugins.HeaderFormatter());

	    // Activate the toolbar
	    myToolbar.setVisible(true);
	    // Hook the toolbar into the field.
	    myToolbarController =
		new goog.ui.editor.ToolbarController(field, myToolbar);

	    // on utilise un évènement au cas où l'utilisateur clique dans les deux Fields en meme temps, puis annule l'un
	    // la toolbar va etre desactivee, il faut donc la reactiver dès un clic dans idBloc
	    goog.events.listen(document.getElementById("contenu"), goog.events.EventType.CLICK, function(){ myToolbar.setVisible(true)});
	}
    }
    // si le field est le titre, on désactive la toolbar (on ne peut pas modifier le style du titre)
    if(field == fieldTitre)
    {
	myToolbar.setVisible(false);
    }

    if(field == fieldTitre)
    {
	idSave = "saveTitreButton";
	idCancel = "cancelTitreButton";
    }
    else
    {
	idSave = "saveContenuButton";
	idCancel = "cancelContenuButton";
    }

    // rend le bouton de sauvegarde visible
    document.getElementById(idSave).style.visibility = "visible";

    // rend le bouton d'annulation visible
    document.getElementById(idCancel).style.visibility = "visible";

    // activation d'un listener sur le SeamlessField : 
    //  si le contenu est modifié, on appelle la fonction qui active le bouton de sauvegarde
    goog.events.listen(field, goog.editor.Field.EventType.DELAYEDCHANGE, function(){ activeBoutonSave(idSave)});
}

function desactiverChamp(field)
{
    field.makeUneditable();

    if(field.isUneditable())
    {
	myToolbar.setVisible(false);
    }

    if(field == fieldTitre)
    {
	idSave = "saveTitreButton";
	idCancel = "cancelTitreButton";
    }
    else
    {
	idSave = "saveContenuButton";
	idCancel = "cancelContenuButton";
    }
    // rend le bouton de sauvegarde invisible
    document.getElementById(idSave).style.visibility = "hidden";
    // rend le bouton d'annulation invisible
    document.getElementById(idCancel).style.visibility = "hidden";
}

function activeBoutonSave(id)
{
    document.getElementById(id).disabled = false;
}
