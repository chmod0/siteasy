<?php
/**
 *  La Classe commentaire realise un Active Record sur la table commentaires
 */
class Commentaire{
    /**
     *  identifiant du commentaire
     *  @access private
     *  @var integer
     */
    private $id_com ;

    /**
     *  titre du commentaire
     *  @access private
     *  @var String
     */
    private $titre_com;

    /**
     *  auteur du commentaire
     *  @access private
     *  @var String
     */
    private $auteur_com;

    /**
     *  mail de l'auteur
     *  @access private
     *  @var String
     */
    private $mail_auteur_com;

    /**
     *  contenu du commentaire
     *  @access private
     *  @var String
     */
    private $contenu_com;

    /**
     *  billet commenté
     *  @access private
     *  @var integer
     */
    private $id_billet;

    /**
     *  date du commentaire
     *  @access private
     *  @var integer
     */
    private $date_com;

    /**
     *  Constructeur de commentaire
     *  fabrique un nouveau commentaire 
     */
    public function __construct(){
	$this->date_com = date('d/m/Y \à H\hi');
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
	if (!isset($this->id_com)){
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
	if (!isset($this->id_com)){
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	} 
	$this->date_com = date('Y-m-d H:i:s', time());
	$save_query = "update commentaire set titre_com=".(isset($this->titre_com) ? "'$this->titre_com'" : "null").
	    "auteur_com=".(isset($this->auteur_com) ? "'$this->auteur_com'" : "null").
	    "mail_auteur_com=".(isset($this->mail_auteur_com) ? "'$this->mail_auteur_com'" : "null").
	    ",contenu_com=".(isset($this->contenu_com) ? "'$this->contenu_com'" : "null").
	    ",id_billet=".(isset($this->id_billet) ? "'$this->id_billet'" : "null").
	    ",date_com=".(isset($this->date_com) ? "'$this->date_com'" : "null").
	    "where id_com=$this->id_com";
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
	if (isset($this->id_com)){
	    $delete_query = 'delete from commentaire where id_com = '.$this->id_com;
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
	$content = nl2br($this->contenu_com);
	$insert_query = "insert into commentaire values(null,".$this->id_billet.
	    ",".(isset($this->titre_com) ? "'$this->titre_com'" : "null").
	    ",".(isset($this->contenu_com) ? "'$content'" : "null").
	    ",".(isset($this->auteur_com) ? "'$this->auteur_com'" : "null").
	    ",".(isset($this->mail_auteur_com) ? "'$this->mail_auteur_com'" : "null").
	    ",'$this->date_com')";
	$c = Base::getConnection();
	$q = mysql_query($insert_query,$c);
	if (! $q){
	    throw new Exception('Mysql query error: '. $insert_query . ' : ' . mysql_error() );
	}
	$this->id_com = mysql_insert_id();

	return mysql_affected_rows();
    }


    /**
     *   Finder sur id_com
     *
     *   Retrouve la ligne de la table correspondant au id passé en paramètre,
     *   retourne un objet
     *  
     *   @static
     *   @param integer $id_com to find
     *   @return commentaire renvoie un objet de type commentaire
     */
    public static function findById($id) {
	$query = "select * from commentaire where id_com=". " $id ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$row = mysql_fetch_assoc($dbres);
	$commentaire = new commentaire();
	$commentaire->setAttr('id_com',$row['id_com']);
	$commentaire->setAttr('titre_com',$row['titre_com']);
	$commentaire->setAttr('auteur_com',$row['auteur_com']);
	$commentaire->setAttr('mail_auteur_com',$row['mail_auteur_com']);
	$commentaire->setAttr('contenu_com',$row['contenu_com']);
	$commentaire->setAttr('id_billet',$row['id_billet']);
	$commentaire->setAttr('date_com',$row['date_com']);
	return $commentaire;
    }

    /**
     *   Finder sur categorie
     *
     *   Retrouve les lignes de la table correspondant a la catégorie passé en paramètre,
     *   retourne un tableau d'objet
     *  
     *   @static
     *   @param integer $id_com to find
     *   @return commentaire renvoie un objet de type commentaire
     */
    public static function findByBillet($bil) {
	$query = "select * from commentaire where id_billet=". " $bil ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}
	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $commentaire = new commentaire();
	    $commentaire->setAttr('id_com',$row['id_com']);
	    $commentaire->setAttr('titre_com',$row['titre_com']);
	    $commentaire->setAttr('auteur_com',$row['auteur_com']);
	    $commentaire->setAttr('mail_auteur_com',$row['mail_auteur_com']);
	    $commentaire->setAttr('contenu_com',$row['contenu_com']);
	    $commentaire->setAttr('id_billet',$row['id_billet']);
	    $commentaire->setAttr('date_com',$row['date_com']);
	    $tab[]=$commentaire;
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
	$query = "select * from commentaire";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres){
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
	}

	$tab = array();
	while($row = mysql_fetch_array($dbres)){
	    $bil = new commentaire();
	    $bil->setAttr('id_com',$row['id_com']);
	    $bil->setAttr('titre_com',$row['titre_com']);
	    $bil->setAttr('auteur_com',$row['auteur_com']);
	    $bil->setAttr('mail_auteur_com',$row['mail_auteur_com']);
	    $bil->setAttr('contenu_com',$row['contenu_com']);
	    $bil->setAttr('id_billet',$row['id_billet']);
	    $bil->setAttr('date_com',$row['date_com']);
	    $tab[]=$bil;		
	}
	return $tab;
    }
}

?>
