<?php

namespace App;

use App\DbModel;

class Potato
{  
    function __construct($chat_id){
        $a = new DbModel();        
        $this->r_u = $a -> getUserInfo($chat_id);
    }

    private $r_u = null;
    private $mode_G = array(
        'Z' => 1,
        'A' => 2,
        'B' => 2,
        'A-1' => 3,
        'B-1' => 3,
        'Z-1' => 3,
        'A-10' => 3,
        'B-10' => 3,
        'Z-10' => 3    
    );

    public function getUserCh(){
        return $this->r_u;
    }
    public function message($text)
    {
        $a = new DbModel();
        $a -> saveMessage($this->r_u['id_user'], $text, $this->r_u);
    }
    public function changeMode($m_u)
    {
        $a = new DbModel();
        $a -> updateMode($this->r_u['id_user'], $m_u);
    }
    public function checkMode($m_u, $jn = false)
    {
        $a = new DbModel();
        $r_m = $a -> getStatusMessage($this->r_u['id_user']);
        $m_n = $this->r_u['mode_name'];
        
        if(empty($r_m)){
            return "Z-0";
        }

        if($this->mode_G[$m_u] === 3 and $this->mode_G[$m_n] === 1){
            return "Z";
        }elseif($this->mode_G[$m_u] === 3){
            return "Q";
        }elseif($this->mode_G[$m_u] < $this->mode_G[$m_n]){
           return "X";
        }else{
            if(($this->mode_G[$m_u] - $this->mode_G[$m_n]) === 1){
                return "AB";
            }else{
                return "Z";
            }
        }
    }
    public function happyEnd($q)
    {
        $v = array(
            "A" => '[ауоыиэяюёе]',
            "B" => '[бвгджзйклмнпрстфхцчшщ]'
        );
        $q = explode("-", $q);
        $a = new DbModel();
        
        if((int)$q[1] === 1){
            $b = $a->getLastEntry($this->r_u['id_user']);
        }elseif((int)$q[1] === 10){
            $b = $a->getAllEntry($this->r_u['id_user']);
        }

        $b = implode('', $b);        
        $r = preg_match_all('/'.$v[$q[0]].'/iUu', $b);
        
        $a = new DbModel();
        $a -> updateMode($this->r_u['id_user'], 'Z');

        return $r;
    }
    public function smile($str){
        $replace = array(
            "0" => "0️⃣",
            "1" => "1️⃣",
            "2" => "2️⃣",
            "3" => "3️⃣",
            "4" => "4️⃣",
            "5" => "5️⃣",
            "6" => "6️⃣",
            "7" => "7️⃣",
            "8" => "8️⃣",
            "9" => "9️⃣",
        );          
        return iconv("UTF-8","UTF-8//IGNORE",strtr($str,$replace));
    }

    private function setMessage($text){
        $a = new DbModel();
        $a -> saveMessage($this->r_u['id_user'], $text, $this->r_u);
    }
 }