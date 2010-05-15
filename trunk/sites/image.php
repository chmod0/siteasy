<?php
session_start();
include_once '../bdd/Base.php';
class image {



    private $id ;
    private $mail ;
    private $nom_image ;

    public function toArray() {
        return array("id"=>$this->id, "mail" => $this->mail, "nom_image"=> $this->nom_image);
    }

    public function getAttr($attr_name) {
        if (property_exists( __CLASS__, $attr_name)) {
            return $this->$attr_name;
        }
        $emess = __CLASS__ . ": unknown member $attr_name (getAttr)";
        throw new Exception($emess, 45);
    }

    public function setAttr($attr_name, $attr_val) {
        if (property_exists( __CLASS__, $attr_name)) {
            $this->$attr_name=$attr_val;
            return $this->$attr_name;
        }
        $emess = __CLASS__ . ": unknown member $attr_name (setAttr)";
        throw new Exception($emess, 45);
    }

    public function delete() {
        if (isset($this->id)) {
            $delete_query = 'delete from image where id = '.$this->id;
            $c = Base::getConnection();
            $q = mysql_query($delete_query,$c);
            if (! $q) {
                throw new Exception('Mysql query error: '. $delete_query . ' : ' . mysql_error() );
            }
            return mysql_affected_rows();
        }
    }

    public function insert() {
        $insert_query = "insert into image values(null,".(isset($this->mail) ? "'$this->mail'" : "null").
            ", '$this->nom_image')";
        $c = Base::getConnection();
        $q = mysql_query($insert_query,$c);
        if (! $q) {
            throw new Exception('Mysql query error: '. $insert_query . ' : ' . mysql_error() );
        }
        $this->id = mysql_insert_id();

        return $this->id;
    }

    public static function findById($id) {
        $query = "select * from image where id=$id ";
        $c = Base::getConnection();
        $dbres = mysql_query($query,$c);
        if (! $dbres) {
            throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
        }
        $row = mysql_fetch_assoc($dbres);
        $billet = new image();
        $billet->setAttr('id',$row['id']);
        $billet->setAttr('mail',$row['mail']);
        $billet->setAttr('nom_image',$row['nom_image']);
        return $billet;
    }
    public static function nombre() {
        $query = "select * from image";
        $c = Base::getConnection();
        $dbres = mysql_query($query,$c);
        $num_rows = mysql_num_rows($dbres);
        return $num_rows;
    }

    public static function findAll() {

        $query = "select * from image  order by id DESC";
        $c = Base::getConnection();
        $dbres = mysql_query($query,$c);
        if (! $dbres) {
            throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
        }

        $tab = array();
        while($row = mysql_fetch_array($dbres)) {
            $billet = new image();
            $billet->setAttr('id',$row['id']);
            $billet->setAttr('mail',$row['mail']);
            $billet->setAttr('nom_image',$row['nom_image']);
            $tab[]=$billet;
        }
        return $tab;
    }

    public static function findMail($mail) {
        $query = "select * from image where mail = '$mail' order by id DESC";
        $c = Base::getConnection();
        $dbres = mysql_query($query,$c);
        if (! $dbres) {
            throw new Exception('Mysql query error: '. $query . ' : ' . mysql_error() );
        }

        $tab = array();
        while($row = mysql_fetch_array($dbres)) {
            $billet = new image();
            $billet->setAttr('id',$row['id']);
            $billet->setAttr('mail',$row['mail']);
            $billet->setAttr('nom_image',$row['nom_image']);
            $tab[]=$billet;
        }
        return $tab;
    }



    static function upload() {

        if(!empty($_POST['posted']) || !empty($_REQUEST['posted']) ) {

            $target     = '../image/image/';  // Repertoire cible
            $max_size   = 5000000;     // Taille max en octets du fichier                           5M ?
            $width_max  = 800;        // Largeur max de l'image en pixels
            $height_max = 800;        // Hauteur max de l'image en pixels
            $nom_file   = $_FILES['fichier']['name'];
            $taille     = $_FILES['fichier']['size'];
            $tmp        = $_FILES['fichier']['tmp_name'];

            // On vérifie si le champ est rempli
            if(!empty($_FILES['fichier']['name'])) {
            // on verifie l'extention
                $nom =  ($_FILES['fichier']['name']);
                if(image::veriExt($nom)) {

                // On récupère les dimensions du fichier
                    $infos_img = getimagesize($_FILES['fichier']['tmp_name']);

                    // On vérifie les dimensions et taille de l'image
                    if(($infos_img[0] <= $width_max) && ($infos_img[1] <= $height_max) && ($_FILES['fichier']['size'] <= $max_size)) {
                    // Si c'est OK, on teste l'upload

                    // on crée un identifiant unique pour l'image
                        $image = new image();
                        $image->setAttr('nom_image',$_FILES['fichier']['name'] );

                        $image->setAttr('mail',$_SESSION['mail']);
                        $id = $image->insert();
                        $new_nom = $id.'.'.substr($nom_file, -3);

                        if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target.$new_nom)) {
                            if ($_GET['txt']!=0) {
                            // Si upload OK alors on affiche le message de réussite
                                $res = '<b>Image uploadée avec succès !</b>';
                                $res .= '<hr />';
                                $res .= "<img src=\"../image/image/$new_nom\" /> <br>";
                                $res .= '<b>Fichier :</b> '. $_FILES['fichier']['name']. '<br />';
                                $res .= '<b>Taille :</b> '. $_FILES['fichier']['size']. ' Octets<br />';
                                $res .= '<b>Largeur :</b> '. $infos_img[0]. ' px<br />';
                                $res .= '<b>Hauteur :</b> '. $infos_img[1]. ' px<br />';
                                $res .= '<hr />';
                                $res .= '<br /><br />';
                            }
                        } else {
                        // Sinon on affiche une erreur système
                            $res = '<b>Problème lors de l\'upload !</b><br /><br /><b>'. $_FILES['fichier']['error']. '</b><br /><br />';
                        }
                    } else {
                    // Sinon on affiche une erreur pour les dimensions et taille de l'image
                        $res = '<b>Problème dans les dimensions ou taille de l\'image !</b><br /><br />';
                    }
                }else {
                // Sinon on affiche une erreur pour les dimensions et taille de l'image
                    $res = '<b>Extension incorrecte</b><br /><br />';
                }
            } else {
            // Sinon on affiche une erreur pour le champ vide
                $res = '<b>Le champ du formulaire est vide !</b><br /><br />';
            }
        }
        echo $res;
    }

    static function veriExt($nom) {
        $tab = explode('.',$nom);
        $ext = $tab[1];
        if ((strcmp($ext,'gif')==0)||(strcmp($ext,'png')==0)||(strcmp($ext,'jpg')==0)||(strcmp($ext,'JPG')==0)) {
            return true;
        }else {
            return false;
        }
    }











}

?>
