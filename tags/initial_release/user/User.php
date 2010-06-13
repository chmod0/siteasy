<?php

/**
 * classe qui gère les utilisateurs
 *
 */

class User
{

	/**
	 * identifiant unique de l'utilisateur(mail)
	 */
	private $mail;
	/*
	 * mot de passe de l'utilisateur
	 */
	private $password;
	/*
	 * admin
	 */
	private $admin;

	/**
	 * constructeur d'un utilisateur
	 */
	public function __construct()
	{
	}

	/**
	 * imprime les attributs d'un objet utilisateur
	 */
	public function __toString()
	{
		return "[" . __CLASS__ . "] mail : " . $this->mail . ", password : " . $this->password;
	}

	/**
	 * fonction getter qui permet d'accéder à n'importe quel attribut de manière générique en passant le nom de l'attribut souhaité en paramètre de la fonction
	 * cette fonctione est nécessaire car tous les attributs de l'objet sont privés
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
	 * fonction de sauvegarde de l'utilisateur dans la BDD
	 * si l'utilisateur n'existe pas encore, il est inséré
	 * sinon, il est modifié
	 * @return valeur retournée par la requete
	 */
	public function save()
	{
		if(!isset($this->userid))
		{
			return $this->insert();
		}
		else
		{
			return $this->update();
		}
	}

	/**
	 * fonction de mise à jour des valeurs de l'utilisateur dans la BDD par rapport aux valeurs des attributs de l'objet
	 * @return nombre de lignes affectées par la mise à jour
	 */
	public function update()
	{
		if(!isset($this->mail))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
		}
		$save_query = "update utilisateur set mail=" . (isset($this->mail) ? "'$this->mail'" : "null") . ", password=".(isset($this->password) ? "'$this->password'" : "null") . ", admin=".(isset($this->admin) ? "'$this->admin'" : "0") . " where mail='" .$this->mail . "'";
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
		// exception si l'id est nul
		if(!isset($this->mail))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot delete");
		}

		$delete_query = "delete from utilisateur where mail='" . $this->mail . "'";
		$connex = Base::getConnection();
		$query = mysql_query($delete_query, $connex);
		if(!$query)
		{
			throw new Exception ("Mysql query error :" . $delete_query . "  : " . mysql_error());
		}
		return mysql_affected_rows($connex);
	}

	/**
	 * fonction d'insertion d'un utilisateur dans la BDD
	 * @return valeur retournée par la requete
	 */
	public function insert()
	{
		if(!isset($this->mail))
		{
			throw new Exception(__CLASS__ . ": Primary Key undefined : cannot insert");
			return null;
		}
		
		if(User::findById($this->mail) != null)
		{
			throw new Exception(__CLASS__ . ": Primary Key already used : cannot insert");
			return null;
		}
		else
		{

		$insert_query = "insert into utilisateur values(" . (isset($this->mail) ? "'$this->mail'" : "null") . ", " . (isset($this->password) ? "'$this->password'" : "null") . ", ".(isset($this->admin) ? "'$this->admin'" : "0") . ")";

		$connex = Base::getConnection();
		$rep_query = mysql_query($insert_query, $connex) or die(mysql_error());

		if(!$rep_query)
			throw new Exception("Mysql query error : " . $rep_query . " : " . mysql_error());
		return $rep_query;
		}
	}

	/**
	 * fonction de recherche d'un utilisateur en l'identifiant par son attribut mail
	 * @param mail de l'utilisateur à rechercher
	 * @return utilisateur recherché ou null si inexistant
	 */
	public static function findById($mail)
	{
		$query = "select * from utilisateur where mail='" . $mail . "'";
		$connex = Base::getConnection();
		$resut = mysql_query($query, $connex);

		if(!$resut)
			throw new Exception("Mysql query error : " . $query . " : " . mysql_error());

		$row = mysql_fetch_assoc($resut);

		if($row)
		{
			$password = $row['password'];
			$mail = $row['mail'];
			$admin = $row['admin'];
			$user = new User();
			$user->setAttr('mail', $mail);
			$user->setAttr('password', $password);
			$user->setAttr('admin', $admin);
			return $user;
		}
		else
		{
			return null;
		}
	}

	/**
	 *   Finder All
	 *
	 *   Renvoie toutes les lignes de la table utilisateur
	 *   sous la forme d'un tableau d'objet
	 *  
	 *   @static
	 *   @return Array renvoie un tableau de utilisateur
	 */

	public static function findAll() {

		$query_find_all = "select * from utilisateur order by mail";
		$connec = Base::getConnection();
		$result = mysql_query($query_find_all, $connec);
		if(! $result)
		{
			throw new Exception('Mysql query error : ' . $query_select_all . ' : ' . mysql_error());
		}

		while($row = mysql_fetch_assoc($result))
		{
			$mail = $row['mail'];
			$password = $row['password'];
			$admin = $row['admin'];
			$user = new User();
			$user->setAttr('mail', $mail);
			$user->setAttr('password', $password);
			$user->setAttr('admin', $admin);
			$array_user[] = $user;
		}
		return $array_user;
	}
}
