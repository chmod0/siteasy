<?php

/**
 * classe qui gère la connexion à la base de données
 * les informations de connexion sont gérées dans le fichier config.php
 *
 */
class Base
{
	/**
	 * connexion à la base de données
	 */
	private static $connexion;

	/**
	 * fonction qui récupère la connexion si elle existe, ou la créée si elle n'existe pas
	 * @return connexion à la base de données
	 */
	public static function getConnection()
	{
		if(self::$connexion == null)
		{
			Base::connect();
		}
		return self::$connexion;
	}

	/**
	 * fonction qui créé une connexion à la base de données
	 * elle charge les informations depuis le fichier config.php
	 */
	private static function connect()
	{
		include("config.php");
		$db = mysql_connect($host, $user, $pass) or die(mysql_error());
		mysql_select_db($base, $db);
		self::$connexion = $db;
	}
}

?>
