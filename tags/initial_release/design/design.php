<?php

include_once '../bdd/Base.php';

class design {

	private $id_design ;
	private $id_modele;
	private $libelle_design;
	private $path_design;

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

		if (!isset($this->id_design)) {
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
		}
		$save_query = "update page set id_modele=$id_modele,
			libelle_design ='$libelle_design', 
			path_design = '$path_design' 
			where id_design=$this->id_design";
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
			$rq = 'DELETE from design where id_design ='.$this->id_design;
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
		if (isset($this->id_design))  {

			// on recupere la connexion
			$c = Base::getConnection();
			//on crée la requete d'insertion'
			$rq =  "INSERT INTO page VALUES('null',$this->id_modele,'$this->libelle_design','$this->path_design')";
			mysql_query($rq,$c) or die("Insertion impossible de $this->libelle_design dans la base de donnée <bd> :".mysql_error());
			$this->id_design = mysql_insert_id();

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
	public static function findById($id) {
		$query = "select * from design where id_design= $id ";
		$c = Base::getConnection();
		$dbres = mysql_query($query,$c);
		if (! $dbres)
		throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() ) ;
		$row=mysql_fetch_assoc($dbres) ;
		$p = new design();
		// on met les valeurs grace au setter
		$p->setAttr('id_design',$row['id_design']);
		$p->setAttr('id_modele',$row['id_modele']);
		$p->setAttr('libelle_design',$row['libelle_design']);
		$p->setAttr('path_design',$row['path_design']);
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
		$design = mysql_query("select * from design ORDER BY id_design",$c) or die(mysql_error());
		while ($designs = mysql_fetch_array($design) ){
			$p = new design();
			// on met les valeurs grace au setter
			$p->setAttr('id_design',$designs ['id_design']);
			$p->setAttr('id_modele',$designs ['id_modele']);
			$p->setAttr('libelle_design',$designs ['libelle_design']);
			$p->setAttr('path_design',$designs ['path_design']);
			$tab[] = $p;
		}
		return $tab;
	}

	public static function findByIdModele($id_modele) {
		$query = "select * from design where id_modele= $id_modele ";
		$c = Base::getConnection();
		$dbres = mysql_query($query,$c);
		if (! $dbres){
			throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
		}
		
		$tab = array();
		while($row = mysql_fetch_array($dbres)){
			$p = new design();
			// on met les valeurs grace au setter
			$p->setAttr('id_design',$row['id_design']);
			$p->setAttr('id_modele',$row['id_modele']);
			$p->setAttr('libelle_design',$row['libelle_design']);
			$p->setAttr('path_design',$row['path_design']);
			$tab[]=$p;		
		}
		return $tab;
	}


}
?>
