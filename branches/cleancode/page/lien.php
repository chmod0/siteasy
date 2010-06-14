<?php

include_once '../bdd/Base.php';

class lien {

    private $id_lien ;
    private $num_page;
    private $num_page_est_reference_par;
    private $lien_cible;
    private $lien_source;

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
	if (!isset($this->id)) {
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

	if (!isset($this->id_lien)) {
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	}
	$save_query = "update lien set num_page='$this->num_page',
	    num_page_est_reference_par=$this->num_page_est_reference_par, 
	    lien_cible ='$this->lien_cible', 
	    lien_source = '$this->lien_source' 
	    where id_lien=$this->id_lien";
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
	if (isset($this->id_lien)) {
	    $c = Base::getConnection();
	    $rq = 'DELETE from lien where id_lien ='.$this->id_lien;
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
	if (isset($this->num_page_est_reference_par))  {

	    // on roecupere la connexion
	    $c = Base::getConnection();
	    //on crée la requete d'insertion'
	    $rq =  "INSERT INTO lien VALUES('null','$this->num_page',$this->num_page_est_reference_par,'$this->lien_cible','$this->lien_source')";
	    mysql_query($rq,$c) or die("Insertion impossible de $this->num_page dans la base de donnée <bd> :".mysql_error());
	    $this->id_lien = mysql_insert_id();
	    return 1;
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
	$query = "select * from lien where id_lien= $num ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if (! $dbres)
	    throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() ) ;

	if($row=mysql_fetch_assoc($dbres))
	{

	    $p = new lien();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$row['num_page']);
	    $p->setAttr('id_lien',$row['id_lien']);
	    $p->setAttr('num_page_est_reference_par',$row['num_page_est_reference_par']);
	    $p->setAttr('lien_cible',$row['lien_cible']);
	    $p->setAttr('lien_source',$row['lien_source']);
	    return $p;
	}
	else
	{
	    return null;
	}
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
	$page = mysql_query("select * from lien",$c) or die(mysql_error());
	while ($pages = mysql_fetch_array($page) ){
	    $p = new lien();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$pages['num_page']);
	    $p->setAttr('id_lien',$pages['id_lien']);
	    $p->setAttr('num_page_est_reference_par',$pages['num_page_est_reference_par']);
	    $p->setAttr('lien_cible',$pages['lien_cible']);
	    $p->setAttr('lien_source',$pages['lien_source']);
	    $tab[] = $p;
	}
	return $tab;
    }

    public static function findByPage($num) {
	$tab = array();
	// on recupere la conneion à la bd
	$c = Base::getConnection();
	$page = mysql_query("select * from lien where num_page_est_reference_par = $num",$c) or die(mysql_error());
	while ($pages = mysql_fetch_array($page) ){
	    $p = new lien();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$pages['num_page']);
	    $p->setAttr('id_lien',$pages['id_lien']);
	    $p->setAttr('num_page_est_reference_par',$pages['num_page_est_reference_par']);
	    $p->setAttr('lien_cible',$pages['lien_cible']);
	    $p->setAttr('lien_source',$pages['lien_source']);
	    $tab[] = $p;
	}
	return $tab;
    }

    public static function findByPageCible($num) {
	$tab = array();
	// on recupere la conneion à la bd
	$c = Base::getConnection();
	$page = mysql_query("select * from lien where num_page = $num",$c) or die(mysql_error());
	while ($pages = mysql_fetch_array($page) ){
	    $p = new lien();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$pages['num_page']);
	    $p->setAttr('id_lien',$pages['id_lien']);
	    $p->setAttr('num_page_est_reference_par',$pages['num_page_est_reference_par']);
	    $p->setAttr('lien_cible',$pages['lien_cible']);
	    $p->setAttr('lien_source',$pages['lien_source']);
	    $tab[] = $p;
	}
	return $tab;
    }


    public static function findByCible($url) {
	$query = "select * from lien where lien_cible= $url ";
	$c = Base::getConnection();
	$dbres = mysql_query($query,$c);
	if ($dbres){
	    $row=mysql_fetch_assoc($dbres) ;
	    $p = new lien();
	    // on met les valeurs grace au setter
	    $p->setAttr('num_page',$row['num_page']);
	    $p->setAttr('id_lien',$row['id_lien']);
	    $p->setAttr('num_page_est_reference_par',$row['num_page_est_reference_par']);
	    $p->setAttr('lien_cible',$row['lien_cible']);
	    $p->setAttr('lien_source',$row['lien_source']);
	    return $p;
	}
	return null;
    }

}
?>
