<?php

/**
 *  La Classe SiteCateg realise un Active Record sur la table sitecateg
 */
class SiteCateg{
    /**
     *  Identifiant de categorie
     *  @access private
     *  @var integer
     */
    private $titre_site_categ ;

    /**
     *  description de categorie
     *  @access private
     *  @var String
     */
    private $desc_site_categ;

    /**
     *  Constructeur de Categorie
     *  fabrique une nouvelle categorie vide
     */
    public function __construct(){
    }

    /**
     *   fonction d'acces aux attributs d'un objet.
     *   Recoit en parametre le nom de l'attribut accede et retourne sa valeur.
     *   @param String $attr_name attribute name 
     *   @return mixed
     */
    public function getAttr($attr_name) {
	if (property_exists( __CLASS__, $attr_name)){ 
	    return $this->$attr_name;
	} 
	$emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
	throw new Exception($emess, 45);
    }

    /**
     *   fonction de modification des attributs d'un objet.
     *   Recoit en parametre le nom de l'attribut modifie et la nouvelle valeur
     *   @param String $attr_name attribute name 
     *   @param mixed $attr_val attribute value
     *   @return mixed new attribute value
     */
    public function setAttr($attr_name, $attr_val){
	if (property_exists( __CLASS__, $attr_name)){
	    $this->$attr_name=$attr_val; 
	    return $this->$attr_name;
	} 
	$emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
	throw new Exception($emess, 45);
    }

    /**
     *   fonction qui enregistre l'etat de l'objet dans la table
     *   Si l'objet possede un identifiant : mise à jour de l aligne correspondante
     *   sinon : insertion dans une nouvelle ligne
     *   @return int le nombre de lignes touchees
     */
    public function save(){
	if (!isset($this->titre_site_categ)){
	    return $this->insert();
	}else{
	    return $this->update();
	}
    }	

    /**
     *   Sauvegarde l'objet courant dans la base en faisant un update
     *   l'identifiant de l'objet doit exister (insert obligatoire auparavant)
     *   @return int nombre de lignes mises à jour
     */
    public function update(){
	if (!isset($this->titre_site_categ)){
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	} 
	$save_query = "update sitecateg set desc_site_categ=".(isset($this->desc_site_categ) ? "'$this->desc_site_categ'" : "null")."where titre_site_categ=$this->titre_site_categ";
	$c = Base::getConnection();
	$q = mysql_query($save_query,$c);
	if (! $q){
	    throw new Exception('Mysql query error: '. $save_query . ' : ' . mysql_error() );
	}
	return mysql_affected_rows($c);

    }

    /**
     *   fonction qui supprime la ligne dans la table corrsepondant à l'objet courant
     *   L'objet doit posséder un OID
     */
    public function delete(){
	if (isset($this->titre_site_categ)){
	    $delete_query = 'delete from sitecateg where titre_site_categ = '.$this->titre_site_categ;
	    $c = Base::getConnection();
	    $q = mysql_query($delete_query,$c);
	    if (! $q){
		throw new Exception('Mysql query error: '. $delete_query . ' : ' . mysql_error() );
	    }
	    return mysql_affected_rows();
	}
    }

    /**
     *   Insertion dans la base
     *
     *   Insère l'objet comme une nouvelle ligne dans la table
     *   l'objet doit posséder  un code_rayon
     *
     *   @return int nombre de lignes insérées
     */									
    public function insert(){
	$insert_query = "insert into sitecateg values(".(isset($this->titre_site_categ) ? "'$this->titre_site_categ'" : "null").",".(isset($this->desc_site_categ) ? "'$this->desc_site_categ'" : "null").")";
	$c = Base::getConnection();
	$q = mysql_query($insert_query,$c);
	if (! $q){
	    throw new Exception('Mysql query error: '. $insert_query . ' : ' . mysql_error() );
	}
	$this->id_categ = mysql_insert_id();

	return mysql_affected_rows();
    }

    /**
     *   Finder sur ID
     *
     *   Retrouve la ligne de la table correspondant au ID passé en paramètre,
     *   retourne un objet
     *  
     *   @static
     *   @param integer $id OID to find
     *   @return Categorie renvoie un objet de type Categorie
     */
    public static function findById($titre) {
	$query = "select * from sitecateg where id_categ=". " $titre ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$row = mysql_fetch_assoc($dbres);
	$cat = new SiteCateg();
	$cat->setAttr('titre_site_categ',$row['titre_site_categ']);
	$cat->setAttr('desc_site_categ',$row['desc_site_categ']);
	return $cat;
    }

    /**
     *   Finder All
     *
     *   Renvoie toutes les lignes de la table categorie
     *   sous la forme d'un tableau d'objet
     *  
     *   @static
     *   @return Array renvoie un tableau de categorie
     */
    public static function findAll() {
	$query = "select * from sitecateg";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}

	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $cat = new SiteCateg();
	    $cat->setAttr('titre_site_categ',$row['titre_site_categ']);
	    $cat->setAttr('desc_site_categ',$row['desc_site_categ']);
	    $tab[]=$cat;		
	}
	return $tab;
    }
}

?>
