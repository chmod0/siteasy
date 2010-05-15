<?php
include '../page/Page.php';
class Site
{
	private $nom_site;

	private $mail;

	private $id_modele;

	private $id_design;

	private $titre_site;

	private $desc_site;

	private $mots_cle;

	private $categ_site;



	/**
	 * constructeur d'un site
	 */
	public function __construct()
	{
	}

	/**
	 * fonction qui permet d'imprimer un site avec un simple appel à la fonction "echo"
	 * c'est une fonction qui sert au debuggage
	 */
	public function __toString()
	{
		return "[" . __CLASS__ . "] nom_site : " . $this->nom_site . " : titre_site : " . stripslashes($this->titre_site) . " : desc_site : " . stripslashes($this->desc_site) . " : categ_site : " . $this->categ_site . " : id_modele : " . $this->id_modele . " : mail : " . $this->mail . " mots cle : " . stripslashes($this->mots_cle);
	}

	/**
	    fonction qui retourne un array qui représente l'objet (utile pour les transferts JSON)
	 */
	public function toArray()
	{
	    return array("nom_site"=>$this->nom_site, "mail" => $this->mail, "id_modele" => $this->id_modele, "titre_site" => $this->titre_site, "desc_site" => $this->desc_site, "mots_cle" => $this->mots_cle, "categ_site" => $this->categ_site , "id_design"=> $this->id_design);
	}

	/**
	 * fonction getter qui permet d'accéder à n'importe quel attribut de manière générique en passant le nom de l'attribut souhaité en paramètre de la fonction
	 * cette fonction est nécessaire car tous les attributs de l'objet sont privés
	 * @param nom de l'attribut
	 * @return valeur de l'attribut
	 */
	public function getAttr($attr_name)
	{
		if(property_exists(__CLASS__, $attr_name)){
			return $this->$attr_name;
		}else{
			$emess = __CLASS__ . " : unknown member $attr_name (getAttr)";
			throw new Exception($emess, 45);
		}
	}

	/**
	 * fonction setter générique pour n'importe quel attribut de l'objet
	 * cette fonction est nécessaire car tous les attributs sont privés
	 * @param nom de l'attribut à modifier
	 * @param valeur de l'attribut à affecter
	 */
	public function setAttr($attr_name, $attr_val)
	{
		if(property_exists(__CLASS__, $attr_name))
		{
			$this->$attr_name = $attr_val;
			return $this->$attr_name;
		}else{
			$emess = __CLASS__ . " : unknown member $attr_name (setAttr)";
			throw new Exception($emess, 45);
		}
	}

	/**
	 * fonction de sauvegarde du site dans la BDD
	 * si le site n'existe pas encore, il est inséré
	 * sinon, il est modifié
	 * @return valeur retournée par la requete
	 */
	public function save()
	{
		if(!isset($this->nom_site))
		{
			return $this->insert();
		}
		else
		{
			return $this->update();
		}
	}

	/**
	 * fonction de mise à jour des valeurs du site dans la BDD par rapport aux valeurs des attributs de l'objet
	 * @return nombre de lignes affectées par la mise à jour
	 */
	public function update()
	{
		if(!isset($this->nom_site))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
		}
		
		$tit = addslashes($this->titre_site);
		$desc = addslashes($this->desc_site);
		$clef = addslashes($this->mots_cle);
		
		$save_query = "update site set titre_site=" . (isset($this->titre_site) ? "'$tit'" : "null") . ", desc_site=".(isset($this->desc_site) ? "'$desc'" : "null") . ", categ_site=" . (isset($this->categ_site) ? "'$this->categ_site'" : "null") . ", id_modele=" . (isset($this->id_modele) ? "'$this->id_modele'" : "null") . ", id_design =" . (isset($this->id_design) ? "'$this->id_design'" : "null"). ", mail=" . (isset($this->mail) ? "'$this->mail'" : "0") . ", mots_cle=" . (isset($this->mots_cle) ? "'$clef'" : "null") . " where nom_site='" . $this->nom_site . "'";
		
		$conn = Base::getConnection();
		$result = mysql_query($save_query, $conn) or die(mysql_error());
		if(!$result)
			throw new Exception("Mysql query error : " . $save_query . " : " . mysql_error());
		return mysql_affected_rows($conn);
	}

	/**
	 * fonction de suppression dans la BDD
	 * @return nombre de lignes affectées par la requete de suppression
	 */
	public function delete()
	{
		// exception si le nom_site est nul
		if(!isset($this->nom_site))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot delete");
		}

		$delete_query = "delete from site where nom_site='" . $this->nom_site . "'";
		$connex = Base::getConnection();
		$query = mysql_query($delete_query, $connex);
		if(!$query)
		{
			throw new Exception ("Mysql query error :" . $delete_query . "  : " . mysql_error());
		}
		return mysql_affected_rows($connex);
	}

	/**
	 * fonction d'insertion d'un site dans la BDD
	 * @return valeur retournée par la requete
	 */
	public function insert()
	{
		if($this->nom_site != null)
		{

		$tit = addslashes($this->titre_site);
		$desc = addslashes($this->desc_site);
		$clef = addslashes($this->mots_cle);

		$insert_query = "insert into site values(" . (isset($this->nom_site) ? "'$this->nom_site'" : "null") . ", " . (isset($this->mail) ? "'$this->mail'" : "null") . ", " . (isset($this->id_modele) ? "'$this->id_modele'" : "null") . ", ". (isset($this->id_design) ? "'$this->id_design'" : "null") .", " . (isset($this->titre_site)? "'$tit'" : "null") . ", " . (isset($this->desc_site) ? "'$desc'" : "null") . ", " . (isset($this->mots_cle) ? "'$clef'" : "null") . ", " . (isset($this->categ_site) ? "'$this->categ_site'" : "null" ) . ")";
		
		$connex = Base::getConnection();
		$rep_query = mysql_query($insert_query, $connex) or die(mysql_error());

		


			if(!$rep_query){
				throw new Exception("Mysql query error : " . $rep_query . " : " . mysql_error());
			}else{
				if ($this->id_modele == 2){
					$this->insertPageDefaut();
				}
			}
		return $rep_query;
		}
		else
		{
			throw new Exception("Mysql error : unable to found the primary key");
			return null;
		}
	}

	/**
	 * fonction de recherche d'un site en l'identifiant par son attribut nom_site
	 * @param nom_site du site à rechercher
	 * @return site recherché ou null si inexistant
	 */
	public function findByNomSite($nom_site)
	{
		$query = "select * from site where nom_site='" . $nom_site . "'";
		$connex = Base::getConnection();
		$resut = mysql_query($query, $connex);

		if(!$resut)
			throw new Exception("Mysql query error : " . $query . " : " . mysql_error());

		$row = mysql_fetch_assoc($resut);

		if($row)
		{
			$nom_site = $row['nom_site'];
			$titre_site = $row['titre_site'];
			$desc_site = $row['desc_site'];
			$categ_site = $row['categ_site'];
			$id_modele = $row['id_modele'];
			$mots_cle = $row['mots_cle'];
			$id_design = $row['id_design'];
			$mail = $row['mail'];
			$si = new Site();
			$si->setAttr('nom_site', $nom_site);
			$si->setAttr('titre_site', stripslashes($titre_site));
			$si->setAttr('desc_site', stripslashes($desc_site));
			$si->setAttr('categ_site', $categ_site);
			$si->setAttr('mots_cle', stripslashes($mots_cle));
			$si->setAttr('mail', $mail);
			$si->setAttr('id_modele', $id_modele);
			$si->setAttr('id_design', $id_design);
			return $si;
		}
		else
		{
			return null;
		}
	}

	/**
	 * fonction de recherche de site par la categorie
	 * @param catégorie à rechercher
	 * @return liste des sites qui sont dans la catégorie recherchée
	 */
	public static function findByCat($categ_site)
	{
		$query = "select * from site where categ_site='" . $categ_site . "'";
		$connex = Base::getConnection();
		$result = mysql_query($query, $connex);
		if(!$result)
			throw new Exception('Mysql query error: ' . $query . ' : ' . mysql_error());

		while($row = mysql_fetch_assoc($result))
		{
			$nom_site = $row['nom_site'];			
			$titre_site = $row['titre_site'];
			$desc_site = $row['desc_site'];
			$categ_site = $row['categ_site'];
			$id_modele = $row['id_modele'];
			$mots_cle = $row['mots_cle'];
			$id_design = $row['id_design'];
			$mail = $row['mail'];
			$si = new Site();
			$si->setAttr('nom_site', $nom_site);
			$si->setAttr('titre_site', stripslashes($titre_site));
			$si->setAttr('desc_site', stripslashes($desc_site));
			$si->setAttr('categ_site', $categ_site);
			$si->setAttr('mots_cle', stripslashes($mots_cle));
			$si->setAttr('mail', $mail);
			$si->setAttr('id_modele', $id_modele);
			$si->setAttr('id_design', $id_design);
			$array_sites[] = $si;
		}
		return $array_sites;
	}
	
	/**
	 * fonction de recherche des sites d'un utilisateur
	 * @param email de l'utilisateur
	 * @return liste des sites qui ont pour mail l'email recherché
	 */
	public static function findByUser($mail)
	{
		$query = "select * from site where mail='" . $mail . "'";
		$connex = Base::getConnection();
		$result = mysql_query($query, $connex);
		if(!$result)
			throw new Exception('Mysql query error: ' . $query . ' : ' . mysql_error());

		while($row = mysql_fetch_assoc($result))
		{
			$nom_site = $row['nom_site'];
			$titre_site = $row['titre_site'];
			$desc_site = $row['desc_site'];
			$categ_site = $row['categ_site'];
			$id_modele = $row['id_modele'];
			$mots_cle = $row['mots_cle'];
			$id_design = $row['id_design'];
			$mail = $row['mail'];
			$si = new Site();
			$si->setAttr('nom_site', $nom_site);
			$si->setAttr('titre_site', stripslashes($titre_site));
			$si->setAttr('desc_site', stripslashes($desc_site));
			$si->setAttr('categ_site', $categ_site);
			$si->setAttr('mots_cle', stripslashes($mots_cle));
			$si->setAttr('mail', $mail);
			$si->setAttr('id_modele', $id_modele);
			$si->setAttr('id_design', $id_design);
			$array_sites[] = $si;
		}
		return $array_sites;
	}

	/**
	 *   Finder All
	 *
	 *   Renvoie toutes les lignes de la table site
	 *   sous la forme d'un tableau d'objets
	 *  
	 *   @static
	 *   @return Array renvoie un tableau de sites
	 */

	public static function findAll() {

		$query_find_all = "select * from site order by mail,id_modele";
		$connec = Base::getConnection();
		$result = mysql_query($query_find_all, $connec);
		if(! $result)
		{
			throw new Exception('Mysql query error : ' . $query_select_all . ' : ' . mysql_error());
		}

		while($row = mysql_fetch_assoc($result))
		{
			$nom_site = $row['nom_site'];
			$titre_site = $row['titre_site'];
			$desc_site = $row['desc_site'];
			$categ_site = $row['categ_site'];
			$id_modele = $row['id_modele'];
			$mots_cle = $row['mots_cle'];
			$id_design = $row['id_design'];
			$mail = $row['mail'];
			$si = new Site();
			$si->setAttr('nom_site', $nom_site);
			$si->setAttr('titre_site', stripslashes($titre_site));
			$si->setAttr('desc_site', stripslashes($desc_site));
			$si->setAttr('categ_site', $categ_site);
			$si->setAttr('mots_cle', stripslashes($mots_cle));
			$si->setAttr('mail', $mail);
			$si->setAttr('id_modele', $id_modele);
			$si->setAttr('id_design', $id_design);
			$array_sites[] = $si;
		}
		return $array_sites;
	}

	public  function insertPageDefaut(){
		$page = new Page();
		$nom_site = $this->nom_site;
		$id_bloc = 0;
		$titre_page = 'Exemple';
		$contenu_page = " Voici une page par defaut";
		$page->setAttr('nom_site',$nom_site);
		$page->setAttr('id_bloc',$id_bloc);
		$page->setAttr('titre_page',stripslashes($titre_page));
		$page->setAttr('contenu_page',$contenu_page);
		$page->insert();
	}
}
