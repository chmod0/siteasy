<?php
require_once("../bdd/Base.php");
class recuperation
{
	private $id;

	private $mail;






	/**
	 * constructeur d'un site
	 */
	public function __construct()
	{
	}





	/**
	 * fonction getter qui permet d'accéder à n'importe quel attribut de manière générique en passant le nom de l'attribut souhaité en paramètre de la fonction
	 * cette fonction est nécessaire car tous les attributs de l'objet sont privés
	 * @param nom de l'attribut
	 * @return valeur de l'attribut
	 */
	public function getAttr($attr_name)
	{
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
	public function setAttr($attr_name, $attr_val)
	{
		if(property_exists(__CLASS__, $attr_name))
		{
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
	public function save()
	{
		if(!isset($this->id))
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
		if(!isset($this->id))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
		}
		
		$save_query = "update recuperation set 
				mail = '$this->mail'
				where id = $this->id";

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
		if(!isset($this->id))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot delete");
		}

		$delete_query = "delete from recuperation where id='$this->id'";
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
		if(isset($this->id))
		{

		$insert_query = "insert into recuperation values( '$this->id' , '$this->mail')";

		$connex = Base::getConnection();
		$rep_query = mysql_query($insert_query, $connex) or die(mysql_error());

		if(!$rep_query)
			throw new Exception("Mysql query error : " . $rep_query . " : " . mysql_error());
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
	public static  function findByid($id)
	{
	if (isset($id)){

		$query = "select * from recuperation where id='$id'";
		$connex = Base::getConnection();
		$resut = mysql_query($query, $connex);
		$row = mysql_fetch_assoc($resut);
			if($row)
			{
				$id = $row['id'];
				$mail = $row['mail'];
				$si = new recuperation();
				$si->setAttr('id', $id);
				$si->setAttr('mail', $mail);
				return $si;
			}
		}
		return null;
	}
}
