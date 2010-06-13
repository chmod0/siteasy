<?php
include("action.php");
class Securite
{
	static function crypte($mot_pass){	
		$salt1 =$mot_pass[0];
		$salt2 =$mot_pass[1];
		$salt3 =$mot_pass[2];
		$mot_pass[2]=$mot_pass[1];
		$mot_pass = sha1(sha1($mot_pass));
		$mot_pass = substr_replace($mot_pass,$salt1,7,0);
		$mot_pass = substr_replace($mot_pass,$salt2,10,0);
		$mot_pass = substr_replace($mot_pass,$salt3,16,0);
		return $mot_pass;
	}
	static function random() {
		$text = "";
		$chaine = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMONPQRSTUVW1234567890";
		srand((double)microtime()*2000000);
		for($i=0; $i<30; $i++) {
			$text .= $chaine[rand()%strlen($chaine)];
		}
		return $text;
	}
	static function parseHack($text){
		 $Blacklist = array('applet', 'body', 'bgsound', 'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml','script:','vbscript:',"javascript:",'select','database()','::','->','perl','onload','@import','-moz-binding','mocha:','livescript:','žscriptualert');
		
		foreach ($Blacklist as $block){
			$text = str_ireplace($block,$block."_",$text);	
		}
		$text = strtr($text, "$", " ");
		$text = addslashes($text);
		return $text;
	}
	static function antiDOS(){
		$ip = ip2long($_SERVER["REMOTE_ADDR"]);
		$date_actuelle = time();
		$ac = action::findByIp($ip);
		if (!empty($ac)){
			$date_debut = $ac->getAttr('date_debut');
			$action = $ac->getAttr('nb_action');
			if ($date_actuelle-$date_debut <5){
				if ($action > 100){
					$ac->bannirIp();
				}else{
				$ac->setAttr('nb_action',$action+1);
				$ac->update();
				}

			}else{
				$ac->setAttr('nb_action',0);
				$ac->setAttr('date_debut',$date_actuelle);
				$ac->update();
			}
		}else{
			$ac = new action();
			$ac->setAttr('ip',$ip);
			$ac->setAttr('nb_action',0);
			$ac->setAttr('date_debut',$date_actuelle);
			$ac->insert();
		}
	}
	static function estBanni(){
		$ip = ip2long($_SERVER["REMOTE_ADDR"]);
		$banni =false;	
		if (isset($ip)){
			$res = action::estBanni($ip);
			if (!empty($res)){
				$banni =true;
			}	
		}
		return $banni;
	}
}
?>
