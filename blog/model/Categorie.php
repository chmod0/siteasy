<?php
/**
 *  La Classe Categorie  realise un Active Record sur la table categorie
 */
class Categorie{
    /**
     *  Identifiant de categorie
     *  @access private
     *  @var integer
     */
    private $id_categ ;

    /**
     *  libelle de categorie
     *  @access private
     *  @var String
     */
    private $titre_categ;

    /**
     *  description de categorie
     *  @access private
     *  @var String
     */
    private $libelle_categ;

    private $nom_site;



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
	if (!isset($this->id_categ)){
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
	if (!isset($this->id_categ)){
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	} 
	$save_query = "update categorie set titre_categ=".(isset($this->titre_categ) ? "'$this->titre_categ'" : "null").",libelle_categ=".(isset($this->libelle_categ) ? "'$this->libelle_categ'" : "null").", nom_site=".(isset($this->nom_site) ? "'$this->nom_site'" : "null").",where id_categ=$this->id_categ";
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
	if (isset($this->id_categ)){
	    $delete_query = 'delete from categorie where id_categ = '.$this->id_categ;
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
	$insert_query = "insert into categorie values(null,".(isset($this->titre_categ) ? "'$this->titre_categ'" : "null").",".(isset($this->libelle_categ) ? "'$this->libelle_categ'" : "null").",".(isset($this->nom_site) ? "'$this->nom_site'" : "null").")";
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
    public static function findById($id) {
	$query = "select * from categorie where id_categ=". " $id ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$row = mysql_fetch_assoc($dbres);
	$cat = new Categorie();
	$cat->setAttr('id_categ',$row['id_categ']);
	$cat->setAttr('titre_categ',$row['titre_categ']);
	$cat->setAttr('libelle_categ',$row['libelle_categ']);
	$cat->setAttr('nom_site',$row['nom_site']);
	return $cat;
    }

    public static function findBySite($nom_site) {
	$query = "select * from categorie where nom_site=". "'$nom_site'";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}

	while($row = mysql_fetch_assoc($dbres))
	{
	    $cat = new Categorie();
	    $cat->setAttr('id_categ',$row['id_categ']);
	    $cat->setAttr('titre_categ',$row['titre_categ']);
	    $cat->setAttr('libelle_categ',$row['libelle_categ']);
	    $cat->setAttr('nom_site',$row['nom_site']);
	    $tab[]=$cat;
	}
	return $tab;
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
	$query = "select * from categorie";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}

	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $cat = new Categorie();
	    $cat->setAttr('id_categ',$row['id_categ']);
	    $cat->setAttr('titre_categ',$row['titre_categ']);
	    $cat->setAttr('libelle_categ',$row['libelle_categ']);
	    $cat->setAttr('nom_site',$row['nom_site']);
	    $tab[]=$cat;		
	}
	return $tab;
    }
}

?>
