<?php
namespace Explorer;

class EntityExplorer extends EntityExplorerAbstract{

    public $dirname;

    public function save(){

        $this->name && rename($this->getId(),$this->getNewId());

    }

    public function getNewId(){

        return $this->getDirname().DIRECTORY_SEPARATOR.$this->name;

    }

    public function getDirname(){

        !$this->dirname && $this->dirname = pathinfo($this->id)["dirname"];

        return $this->dirname;
        
    }

    public function setDirname($dirname){

        $this->dirname = $dirname;

    }
}