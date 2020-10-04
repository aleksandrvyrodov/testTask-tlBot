<?php

namespace App;

use PDO;

class DbModel
{
    private $dbh = null;

    function __construct() {
        // try {
        //     $dbh = new PDO('mysql:host=localhost;dbname=hystory', 'root', '');
        //     $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        //     $this->dbh = $dbh;
        // } catch (PDOException $e) {
        //     print "Error!: " . $e->getMessage() . "<br/>";
        //     die();
        // }

        try {
            $dbh = new PDO('mysql:host=localhost;dbname=u1165283_hystory;charset=utf8;', 'u1165283_me', '1290as!@');
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->dbh = $dbh;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function addNewSession($chat_id){
        $dbh = $this->dbh;
        $sth = $dbh->prepare("DELETE FROM `user` WHERE `id_user` = :user; INSERT INTO user (`id_user`) VALUES (:user);");
        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->execute();
    }
    public function saveMessage($chat_id, $text, $r_u){
        $dbh = $this->dbh;
       
        $px = ((int)$r_u['count'] > 1) ? "(:count-1)" : "10";

        $sth = $dbh->prepare("INSERT INTO message (`id_messege`,`id_user`,`text`) VALUES (:count, :user, :text)
                    ON DUPLICATE KEY 
                UPDATE `text`=:text; 
                UPDATE `user` SET `count`=".$px." WHERE id_user = :user;");

        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->bindParam(':count', $r_u['count'], PDO::PARAM_INT);
        $sth->bindParam(':text', $text, PDO::PARAM_STR);
        $sth->execute();
    }
    public function getUserInfo($chat_id){
        $dbh = $this->dbh;
        $sth = $dbh->prepare("SELECT `id_user`, `count`, `id_mode`, `mode`.`name` AS 'mode_name' FROM `user` INNER JOIN `mode` USING(`id_mode`) WHERE `id_user` = :user");
        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $r_u = $sth->fetch();
        return $r_u;
    }
    public function getMode($x = null){
        $dbh = $this->dbh;
        if(empty($x)){
            $sth = $dbh->prepare("SELECT * FROM mode");
        }else{
            $sth = $dbh->prepare("SELECT * FROM mode WHERE `id_mode` = :mode");
            $sth->bindParam(':mode', $x, PDO::PARAM_INT);
        }
        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $m = $sth->fetchAll();
        return $m;
    }
    public function updateMode($chat_id, $m){
        $dbh = $this->dbh;
        $sth = $dbh->prepare("UPDATE `user` SET `id_mode`= (SELECT `id_mode` FROM mode WHERE `name` = :mode) WHERE `id_user` = :user");
        $sth->bindParam(':mode', $m, PDO::PARAM_STR);
        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->execute();
    }
    public function getStatusMessage($chat_id){
        $dbh = $this->dbh;
        $sth = $dbh->prepare("SELECT * FROM `message` WHERE id_user = :user");
        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->execute();
        $r_m = $sth->fetchColumn();
        return $r_m;
    }
    public function getLastEntry($chat_id){
        $dbh = $this->dbh;
        $sth = $dbh->prepare("SELECT `text` FROM `message` INNER JOIN `user` USING(`id_user`) WHERE (`user`.`count` + 1) = `message`.`id_messege` AND `user`.`id_user` = :user");
        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->execute();
        $r = $sth->fetchAll(PDO::FETCH_COLUMN);
        return $r;
    }
    public function getAllEntry($chat_id){
        $dbh = $this->dbh;
        $sth = $dbh->prepare("SELECT `text` FROM `message` WHERE `message`.`id_user` = :user");
        $sth->bindParam(':user', $chat_id, PDO::PARAM_INT);
        $sth->execute();
        $r = $sth->fetchAll(PDO::FETCH_COLUMN);
        return $r;
    }
 }