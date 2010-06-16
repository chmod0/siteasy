<?php

class action
{
    private $ip;
    private $nb_action;
    private $date_debut;

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
	if(!isset($this->ip))
	{
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot update");
	}

	$save_query = "update action set nb_action = $this->nb_action ,
	    date_debut = $this->date_debut
	    where ip = $this->ip";

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
	if(!isset($this->ip))
	{
	    throw new Exception(__CLASS__ . ": Primary Key undefined : cannot delete");
	}

	$delete_query = "delete from action where ip=$this->ip";
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
	if($this->ip != null)
	{

	    $insert_query = "insert into action values( $this->ip , $this->nb_action , $this->date_debut)";

	    $connex = Base::getConnection();
	    $rep_query = mysql_query($insert_query, $connex) or die(mysql_error());

	    if(!$rep_query)
		throw new Exception("Mysql query error : " . $rep_query . " : " . mysql_error());
	    return $rep_query;
	}
	else
	{

	    return null;
	}
    }

    /**
     * fonction de recherche d'un site en l'identifiant par son attribut nom_site
     * @param nom_site du site à rechercher
     * @return site recherché ou null si inexistant
     */
    public static  function findByIp($ip)
    {
	$query = "select * from action where ip=$ip";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);

	if(!$resut)
	    return null;

	$row = mysql_fetch_assoc($resut);

	if($row)
	{
	    $ip = $row['ip'];
	    $nb_action = $row['nb_action'];
	    $date_debut = $row['date_debut'];
	    $si = new action();
	    $si->setAttr('ip', $ip);
	    $si->setAttr('nb_action', $nb_action);
	    $si->setAttr('date_debut', $date_debut);
	    return $si;
	}
	else
	{
	    return null;
	}
    }

    public function bannirIp(){
	$ip = $this->ip;
	$query = "insert into liste_noire values ($ip)";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);
    }
    public function deBannirIp($ip){
	$query = "delete from liste_noire where ip=$ip";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);
    }
    public function bannir($ip){
	$query = "insert into liste_noire values ($ip)";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);
    }
    public function estBanni($ip){
	$query = "select * from liste_noire where ip = $ip";
	$connex = Base::getConnection();
	$resut = mysql_query($query, $connex);
	if (!empty($resul))
	    $row = mysql_fetch_assoc($resut);
	return $row['ip'];
    }

    public function findBanni(){
	$query = "select * from liste_noire";
	$connex = Base::getConnection();
	$result = mysql_query($query, $connex);
	while($row = mysql_fetch_assoc($result)){
	    $res[]=$row['ip'];
	}
	return $res;

    }

}
