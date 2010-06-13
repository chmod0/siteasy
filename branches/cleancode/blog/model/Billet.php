<?php

session_start();

/**
 *  La Classe Billet realise un Active Record sur la table billets
 */
class Billet{
    /**
     *  identifiant du billet
     *  @access private
     *  @var integer
     */
    private $id_billet ;

    /**
     *  titre du billet
     *  @access private
     *  @var String
     */
    private $titre_billet;

    /**
     *  auteur du billet
     *  @access private
     *  @var String
     */
    private $auteur_billet;

    /**
     *  contenu du billet
     *  @access private
     *  @var String
     */
    private $contenu_billet;

    /**
     *  categorie du billet
     *  @access private
     *  @var integer
     */
    private $id_categ;

    /**
     *  date du billet
     *  @access private
     *  @var integer
     */
    private $date_billet;

    /**
     * nom du site auquel appartient le billet
     * @access private
     * @var String
     */
    private $nom_site;

    private $image;

    /**
     *  Constructeur de Billet
     *  fabrique un nouveau billet 
     */
    public function __construct(){
	$this->date_billet = date('Y-m-d H:i:s', time());
	$this->auteur_billet = $_SESSION['mail'];
	$this->nom_site = $_GET['site'];
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
	if (!isset($this->id_billet)){
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
	if (!isset($this->id_billet)){
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	} 
	$this->date_billet = date('Y-m-d H:i:s', time());
	$titre = addslashes($this->titre_billet);
	$auteur = addslashes($this->auteur_billet);
	$contenu = addslashes($this->contenu_billet);
	$idCateg = addslashes($this->id_categ);
	$image = addslashes($this->image);
	$date = addslashes($this->date_billet);

	$save_query = "update billet set titre_billet=".(isset($this->titre_billet) ? "'$titre'" : "null").
	    ",auteur_billet=".(isset($this->auteur_billet) ? "'$auteur'" : "null").
	    ",contenu_billet=".(isset($this->contenu_billet) ? "'$contenu'" : "null").
	    ",id_categ=".(isset($this->id_categ) ? "'$idCateg'" : "null").
	",image=".(isset($this->image) ? "'$image'" : "0").
	    ",date_billet=".(isset($this->date_billet) ? "'$date'" : "null").
	    ",nom_site=".(isset($this->nom_site) ? "'$this->nom_site'" : "null").
	    "where id_billet=$this->id_billet";
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
	if (isset($this->id_billet)){
	    $delete_query = 'delete from billet where id_billet = '.$this->id_billet;
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
	$insert_query = "insert into billet values(null,".(isset($this->id_categ) ? "'$this->id_categ'" : "null").
	    ",".(isset($this->nom_site) ? "'$this->nom_site'" : "null").
	    ",".(isset($this->titre_billet) ? "'$this->titre_billet'" : "null").
	    ",".(isset($this->auteur_billet) ? "'$this->auteur_billet'" : "null").
	    ",".(isset($this->contenu_billet) ? "'$this->contenu_billet'" : "null").
	    ",'$this->date_billet',0)";
	$c = Base::getConnection();
	$q = mysql_query($insert_query,$c);
	if (! $q){
	    throw new Exception('Mysql query error: '. $insert_query . ' : ' . mysql_error() );
	}
	$this->id_billet = mysql_insert_id();

	return $this->id_billet;
    }


    /**
     *   Finder sur id_billet
     *
     *   Retrouve la ligne de la table correspondant au id passé en paramètre,
     *   retourne un objet
     *  
     *   @static
     *   @param integer $id_billet to find
     *   @return Billet renvoie un objet de type Billet
     */
    public static function findById($id) {
	$query = "select * from billet where id_billet=". " $id ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$row = mysql_fetch_assoc($dbres);
	if($row)
	{
	    $billet = new Billet();
	    $billet->setAttr('id_billet',stripslashes($row['id_billet']));
	    $billet->setAttr('titre_billet',stripslashes($row['titre_billet']));
	    $billet->setAttr('auteur_billet',stripslashes($row['auteur_billet']));
	    $billet->setAttr('contenu_billet',stripslashes($row['contenu_billet']));
	    $billet->setAttr('id_categ',stripslashes($row['id_categ']));
	    $billet->setAttr('date_billet',stripslashes($row['date_billet']));
	    $billet->setAttr('nom_site',stripslashes($row['nom_site']));
	    $billet->setAttr('image',stripslashes($row['image']));
	    return $billet;
	}
	else
	{
	    return null;
	}
    }

    /**
     *   Finder sur categorie
     *
     *   Retrouve les lignes de la table correspondant a la catégorie passé en paramètre,
     *   retourne un tableau d'objet
     *  
     *   @static
     *   @param integer $id_billet to find
     *   @return Billet renvoie un objet de type Billet
     */
    public static function findByCat($cat) {
	$query = "select * from billet where id_categ=". " $cat ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $billet = new Billet();
	    $billet->setAttr('id_billet',stripslashes($row['id_billet']));
	    $billet->setAttr('titre_billet',stripslashes($row['titre_billet']));
	    $billet->setAttr('auteur_billet',stripslashes($row['auteur_billet']));
	    $billet->setAttr('contenu_billet',stripslashes($row['contenu_billet']));
	    $billet->setAttr('id_categ',stripslashes($row['id_categ']));
	    $billet->setAttr('date_billet',stripslashes($row['date_billet']));
	    $billet->setAttr('nom_site',stripslashes($row['nom_site']));
	$billet->setAttr('image',stripslashes($row['image']));
	    $tab[]=$billet;
	}
	return $tab;
    }

    /**
     *   Finder All
     *
     *   Renvoie toutes les lignes de la table billet
     *   sous la forme d'un tableau d'objets
     *  
     *   @static
     *   @return Array renvoie un tableau de billets
     */
    public static function findAll() {
	$query = "select * from billet";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}

	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $bil = new Billet();
	    $bil->setAttr('id_billet',stripslashes($row['id_billet']));
	    $bil->setAttr('titre_billet',stripslashes($row['titre_billet']));
	    $bil->setAttr('auteur_billet',stripslashes($row['auteur_billet']));
	    $bil->setAttr('contenu_billet',stripslashes($row['contenu_billet']));
	    $bil->setAttr('id_categ',stripslashes($row['id_categ']));
	    $bil->setAttr('date_billet',stripslashes($row['date_billet']));
	    $bil->setAttr('nom_site',stripslashes($row['nom_site']));
	$bil->setAttr('image',stripslashes($row['image']));
	    $tab[]=$bil;		
	}
	return $tab;
    }

    /**
     *
     * Fonction Finder nom_site
     * 
     * Renvoie tous les billets d'un nom_site donné
     *
     */
    public static function findByNomSite($nomSite)
    {
	$query = "select * from billet where nom_site='" . $nomSite . "'";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}

	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $bil = new Billet();
	    $bil->setAttr('id_billet',stripslashes($row['id_billet']));
	    $bil->setAttr('titre_billet',stripslashes($row['titre_billet']));
	    $bil->setAttr('auteur_billet',stripslashes($row['auteur_billet']));
	    $bil->setAttr('contenu_billet',stripslashes($row['contenu_billet']));
	    $bil->setAttr('id_categ',stripslashes($row['id_categ']));
	    $bil->setAttr('date_billet',stripslashes($row['date_billet']));
	    $bil->setAttr('nom_site',stripslashes($row['nom_site']));
		$bil->setAttr('image',stripslashes($row['image']));
	    $tab[]=$bil;		
	}
	return $tab;
    }

public static function findByImage($image) {
	$query = "select * from billet where image=". " $image ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $billet = new Billet();
	    $billet->setAttr('id_billet',stripslashes($row['id_billet']));
	    $billet->setAttr('titre_billet',stripslashes($row['titre_billet']));
	    $billet->setAttr('auteur_billet',stripslashes($row['auteur_billet']));
	    $billet->setAttr('contenu_billet',stripslashes($row['contenu_billet']));
	    $billet->setAttr('id_categ',stripslashes($row['id_categ']));
	    $billet->setAttr('date_billet',stripslashes($row['date_billet']));
	    $billet->setAttr('nom_site',stripslashes($row['nom_site']));
	$billet->setAttr('image',stripslashes($row['image']));
	    $tab[]=$billet;
	}
	return $tab;
    }




}

?>
