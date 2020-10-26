<?php
namespace Explorer;
use Yes\SerializationInterface;


class File extends EntityExplorer{

    public $size;

    public $perms;

    public $extension;

    public $content;

    public $dirname;

    public $date;

    public function getParent(){

        return parent;
    }

    public function canExists(){

        return is_file($this->id) && file_exists($this->id);

    }

    public function readExit(){

        return \readfile($this->getId());

    }

    public function save(){

        $this->content && file_put_contents($this->getId(),$this->content);
        $this->name && rename($this->getId(),$this->getNewId());

    }

    public function getNewId(){

        return $this->getDirname().DIRECTORY_SEPARATOR.$this->name;

    }

    public function getDate(){

        !$this->date && $this->date = filemtime($this->getId());

        return $this->date;

    }

    public function getName(){

        !$this->name && $this->name = basename($this->id);

        return $this->name;

    }

    public function setName($name){

        $this->name = $name;
    }

    public function getContent(){

        !$this->content && $this->content = file_get_contents($this->id);

        return $this->content;

    }

    public function setContent($content){

        if($content instanceof SerializationInterface){

            $content = $content->serialize();

        }else if(\is_array($content)){

            $content = \json_encode($content);

        }

        $this->content = $content;

    }

    public function getExtension(){

        !$this->extension && $this->extension = pathinfo($this->id)["extension"];

        return $this->extension;
        
    }

    public function getDirname(){

        !$this->dirname && $this->dirname = pathinfo($this->id)["dirname"];

        return $this->dirname;
        
    }

    public function setDirname($dirname){

        if($dirname instanceof Folder){

            $dirname = $dirname->getId();

        }

        $this->dirname = $dirname;

    }

    public function getPerms(){

        !$this->perms && $this->perms = substr(decoct(fileperms($this->id)), -4);

        return $this->perms;

    }

    public function getSize(){

        !$this->size && $this->size = ExplorerStatic::get_size($this->id);

        return $this->size;

    }


    public function setSize($size){

        $this->size = $size;

    }

    public function getInfo(){

        $this->getSize();
        $this->getPerms();
        $this->getName();
        $this->getExtension();
        $this->getDirname();
        $this->getDate();
        return $this;

    }

    public function canArouse(){

        touch($this->id);

    }

    public function copyTo(Folder $folder){

        if(alive($folder) || $folder = arouse($folder)){

            $path = $folder->getId().DIRECTORY_SEPARATOR.$this->getName();

            return copy($this->getId(),$path);

        }

    }

    public function destroy(){

        unlink($this->getId());

    }

}