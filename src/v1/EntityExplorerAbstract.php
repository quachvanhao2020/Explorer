<?php
namespace Explorer;

abstract class EntityExplorerAbstract{

    const HIDDEN = "HIDDEN";
    const SHOW = "SHOW";
    public $status;
    public $type;
    public const NOERROR = 0;
    public static $in;
    public $id;
    public $alive;
    public $name;
    private $error = self::NOERROR;
    public function __construct($id){
        $this->id = $id;
        $this->getAlive();
    }

    public function setName($name){
        $this->name = $name;
    }

    public function toHidden(){
        if($this->getStatus() == self::SHOW){
            $this->setName(".".$this->name);
        }
    }

    public function toShow(){
        if($this->getStatus() == self::HIDDEN){
            $name = \substr($this->name,1);
            $this->setName($name);  
        }
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getStatus(){
        if(!$this->status){
            $name = $this->getName();
            if ($name[0] != '.'){
                $this->status  = self::SHOW;
            }else {
                $this->status  = self::HIDDEN;
            }
        }
        return $this->status;
    }

    public function getError(){
        return $this->error;
    }

    public function setError($error){
        $this->error = $error;
    }

    public function getAlive(){
        $this->alive = $this->canExists();
        return $this->alive;
    }

    public function save(){
        self::$in = $this;
    }

    public function getInfo(){
        $this->name = $id;
    }

    public function isAlive(){
        return $this->alive;
    }

    public function canExists(){
        return $this->id !== null;
    }

    public function arouse(){
        $this->canArouse();
        $this->getAlive();
        return $this;
    }

    public function canArouse(){
        $this->id = "x";
    }

    public function getId(){

        return $this->id;
    }

}