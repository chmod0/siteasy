<?php

class image_page {
    private $num_page;

    private $num_image;

    /**
     * constructeur d'un site
     */
    public function __construct() {
    }

    /**
     * fonction getter qui permet d'accéder à n'importe quel attribut de manière générique en passant le nom de l'attribut souhaité en paramètre de la fonction
     * cette fonction est nécessaire car tous les attributs de l'objet sont privés
     * @param nom de l'attribut
     * @return valeur de l'attribut
     */
    public function getAttr($attr_name) {
	if(property_exists(__CLASS__, $attr_name))
	    return $this->$attr_name;

	$emess = __CLASS__ . " : unknown member $attr_name (getAttr)";
	throw new Exception($emess, 45);
    }

    /**
     * fonction setter générique pour n'importe quel attribut de l'objet
     * cette fonction est nécessaire car tous les attributs sont privés
     * @param nom de l'attribut à modifier
     * @param valeur de l'attribut à affecter
     */
    public function setAttr($attr_name, $attr_val) {
	if(property_exists(__CLASS__, $attr_name)) {
	    $this->$attr_name = $attr_val;
	    return $this->$attr_name;
	}
	$emess = __CLASS__ . " : unknown member $attr_name (setAttr)";
	throw new Exception($emess, 45);
    }

    /**
     * fonction de sauvegarde du site dans la BDD
     * si le site n'existe pas encore, il est inséré
     * sinon, il est modifié
     * @return valeur retournée par la requete
     */
    public function save() {
	if(!isset($this->num_page)) {
	    return $this->insert();
	}
	else {
	    return $this->update();
	}
    }

    /**
     * fonction de suppression dans la BDD
     * @return nombre de lignes affectées par la requete de suppression
     */
    public function delete() {
	// exception si le nom_site est nul
	if(!isset($this->num_page) || (!isset($this->num_image))) {
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot delete");
	}

	$delete_query = "delete from image_page where num_page=$this->num_page and num_image = $this->num_image";
	$connex = Base::getConnection();
	$query = mysql_query($delete_query, $connex);
	if(!$query) {
	    throw new Exception ("Mysql query error :" . $delete_query . "  : " . mysql_error());
	}
	return mysql_affected_rows($connex);
    }

    /**
     * fonction d'insertion d'un site dans la BDD
     * @return valeur retournée par la requete
     */
    public function insert() {
	if($this->num_page != null) {

	    $insert_query = "insert into image_page values( $this->num_page , $this->num_image )";

	    $connex = Base::getConnection();
	    $rep_query = mysql_query($insert_query, $connex) or die(mysql_error());

	    if(!$rep_query)
		throw new Exception("Mysql query error : " . $rep_query . " : " . mysql_error());
	    return $rep_query;
	}
	else {

	    return null;
	}
    }

    /**
     * fonction de recherche d'un site en l'identifiant par son attribut nom_site
     * @param nom_site du site à rechercher
     * @return site recherché ou null si inexistant
     */
    public static  function findByPage($num_page) {
	$query = "select * from image_page where num_page=$num_page";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);

	if(!$resut)
	    return array();

	while( $row = mysql_fetch_assoc($resut)) {

	    $page = $row['num_page'];
	    $image = $row['num_image'];
	    $si = new image_page();

	    $si->setAttr('num_page', $page);
	    $si->setAttr('num_image', $image);
	    $tab[]=$si;

	}
	return $tab;
    }

    /**
     * fonction de recherche d'un site en l'identifiant par son attribut nom_site
     * @param nom_site du site à rechercher
     * @return site recherché ou null si inexistant
     */
    public static  function findByImage($num_im) {
	$query = "select * from image_page where num_image=$num_im";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);

	if(!$resut)
	    return array();



	while( $row = mysql_fetch_assoc($resut)) {

	    $page = $row['num_page'];
	    $image = $row['num_image'];
	    $si = new image_page();

	    $si->setAttr('num_page', $page);
	    $si->setAttr('num_image', $image);
	    $tab[]=$si;

	}
	return $tab;
    }
}
