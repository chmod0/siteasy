<?php

include_once '../bdd/Base.php';

class Page {

    private $num_page ;
    private $nom_site;
    private $id_bloc;
    private $titre_page;
    private $contenu_page;

    public function __construct() {
    }

    /**
     *  Magic pour imprimer
     *
     *  Fonction Magic retournant une chaine de caracteres imprimable
     *  pour imprimer facilement un Ouvrage
     *
     *  @return String
     */
    public function __toString() {
	return "[". __CLASS__ . "] id : ". $this->id . ":
	    titre  ". $this->titre  .":
	    description ". $this->body  ;
    }

    /**
     *   Getter generique
     *
     *   fonction d'acces aux attributs d'un objet.
     *   Recoit en parametre le nom de l'attribut accede
     *   et retourne sa valeur.
     *
     *   @param String $attr_name attribute name
     *   @return mixed
     */

    public function getAttr($attr_name) {
	if (property_exists( __CLASS__, $attr_name)) {
	    return $this->$attr_name;
	}
	$emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
	throw new Exception($emess, 45);
    }
    /**
     *   Setter generique
     *
     *   fonction de modification des attributs d'un objet.
     *   Recoit en parametre le nom de l'attribut modifie et la nouvelle valeur
     *
     *   @param String $attr_name attribute name
     *   @param mixed $attr_val attribute value
     *   @return mixed new attribute value
     */
    public function setAttr($attr_name, $attr_val) {
	if (property_exists( __CLASS__, $attr_name)) {
	    $this->$attr_name=$attr_val;
	    return $this->$attr_name;
	}
	$emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
	throw new Exception($emess, 45);

    }

    /**
     *   Sauvegarde dans la base
     *
     *   Enregistre l'etat de l'objet dans la table
     *   Si l'objet possede un identifiant : mise à jour de l aligne correspondante
     *   sinon : insertion dans une nouvelle ligne
     *
     *   @return int le nombre de lignes touchees
     */
    public function save() {
	if (!isset($this->num_page)) {
	    return $this->insert();
	} else {
	    return $this->update();
	}
    }
    /**
     *   mise a jour de la ligne courante
     *
     *   Sauvegarde l'objet courant dans la base en faisant un update
     *   l'identifiant de l'objet doit exister (insert obligatoire auparavant)
     *   méthode privée - la méthode publique s'appelle save
     *   @acess public
     *   @return int nombre de lignes mises à jour
     */
    public function update() {

	if (!isset($this->num_page)) {
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	}
	$titre = addslashes($this->titre_page);
	$contenu = addslashes($this->contenu_page);
	$nom_site = $this->nom_site;

	$save_query = "update page set nom_site='$nom_site',
	    id_bloc= ".(isset($this->id_bloc)  ? " $this->id_bloc ":" 'null'")." , ". 
	    "titre_page =".(isset($this->titre_page) ?  "'$titre' ":" 'null' ")." , ".
	    "contenu_page =". (isset($this->contenu_page) ? " '$contenu'" :" 'null'").
	    "where num_page=$this->num_page";
	$c = Base::getConnection();
	$q = mysql_query($save_query,$c)or die ( $save_query . mysql_error());
	if (! $q)
	    throw new Exception('Mysql query error: '. $save_query . ' : ' . mysql_error() ) ;
	return mysql_affected_rows($c);

    }
    /**
     *   Suppression dans la base
     *
     *   Supprime la ligne dans la table corrsepondant à l'objet courant
     *   L'objet doit posséder un OID
     */
    public function delete() {
	if (isset($this->num_page)) {
	    $c = Base::getConnection();
	    $rq = 'DELETE from page where num_page ='.$this->num_page;
	    mysql_query($rq,$c);
	}
    }
    /**
     *   Insertion dans la base
     *
     *   Insère lobjet comme une nouvelle ligne dans la table
     *   l'objet doit posséder  un code_rayon
     *
     *   @return int nombre de lignes insérées
     */
    public function insert() {
	if (isset($this->nom_site))  {

	    $titre = addslashes($this->titre_page);
	    $contenu = addslashes($this->contenu_page);


	    // on recupere la connexion
	    $c = Base::getConnection();
	    //on crée la requete d'insertion
	    $rq =  "INSERT INTO page VALUES('null','$this->nom_site' , ".
		(isset($this->id_bloc)  ? " $this->id_bloc ":" 'null'")." , ". 
		(isset($this->titre_page) ?  "'$titre' ":" 'null' ")." , ".
		(isset($this->contenu_page) ? " '$contenu'" :" 'null'").')';

	    mysql_query($rq,$c) or die("Insertion impossible de $this->titre_page dans la base de donnée <bd> :".mysql_error());
	    $this->num_page = mysql_insert_id();

	    return mysql_insert_id();
	}else{

	    return 0;
	}
    }
    /**
     *   Finder sur ID
     *
     *   Retrouve la ligne de la table correspondant au ID passé en paramètre,
     *   retourne un objet
     *
     *   @static
     *   @param integer $id OID to find
     *   @return billet renvoie un objet de type billet
     */
    public static function findByNum($num) {
	$query = "select * from page where num_page= $num ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres)
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() ) ;
	$row=mysql_fetch_assoc($dbres) ;
	$p = new Page();
	// on met les valeurs grace au setter
	$p->setAttr('num_page',$row['num_page']);
	$p->setAttr('nom_site',$row['nom_site']);
	$p->setAttr('id_bloc',$row['id_bloc']);

	$p->setAttr('titre_page',stripslashes($row['titre_page']));
	$p->setAttr('contenu_page',stripslashes($row['contenu_page']));

	return $p;
    }

    /**
     *   Finder All
     *
     *   Renvoie toutes les lignes de la table billet
     *   sous la forme d'un tableau d'objet
     *
     *   @static
     *   @return Array renvoie un tableau de billet
     */

    public static function findAll() {
	$tab = array();
	// on recupere la conneion à la bd
	$c = Base::getConnection();
	$page = mysql_query("select * from page ORDER BY num_page",$c) or die(mysql_error());
	while ($pages = mysql_fetch_array($page) ){
	    $p = new Page();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$pages['num_page']);
	    $p->setAttr('nom_site',$pages['nom_site']);
	    $p->setAttr('id_bloc',$pages['id_bloc']);

	    $titre = stripslashes($pages['titre_page']);
	    $contenu = stripslashes($pages['contenu_page']);

	    $p->setAttr('titre_page',$titre);
	    $p->setAttr('contenu_page',$contenu);

	    $tab[] = $p;
	}
	return $tab;
    }

    public static function findByTitre($chaine,$site) {	
	if(isset($chaine)){
	    $c = Base::getConnection();
	    $chaine = addslashes($chaine);
	    $query= "Select * from page where titre_page ='$chaine' and nom_site = '$site'";
	    $dbres = mysql_query($query,$c);
	    if (! $dbres)
		throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() ) ;
	    $row=mysql_fetch_assoc($dbres) ;
	    $p = new Page();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$row['num_page']);
	    $p->setAttr('nom_site',$row['nom_site']);
	    $p->setAttr('id_bloc',$row['id_bloc']);

	    $titre = stripslashes($row['titre_page']);
	    $contenu = stripslashes($row['contenu_page']);

	    $p->setAttr('titre_page',$titre);
	    $p->setAttr('contenu_page',$contenu);

	    return $p;

	}
    }

    public static function findBySite($chaine) {
	if(isset($chaine)){
	    $c = Base::getConnection();
	    $rq= "Select * from page where nom_site ='$chaine' ORDER BY num_page DESC";
	    $billets = mysql_query($rq,$c) or die(mysql_error());
	    while ($pages = mysql_fetch_array($billets) ){
		$p = new Page();
		// on met les valeurs grace au setter
		$p->setAttr('num_page',$pages['num_page']);
		$p->setAttr('nom_site',$pages['nom_site']);
		$p->setAttr('id_bloc',$pages['id_bloc']);

		$titre = stripslashes($pages['titre_page']);
		$contenu = stripslashes($pages['contenu_page']);

		$p->setAttr('titre_page',$titre);
		$p->setAttr('contenu_page',$contenu);
		$tab[] = $p;
	    }

	    return $tab;
	}return array();

    }

}
?>
