<?php
namespace Explorer\File;
use Explorer\FileRelative;
use Explorer\File;
use Explorer\Meta\HostMeta;

class FileMultiple extends FileRelative{

    public $hostTotalSize;

    public $hostMeta;

    public $rootFile;

    public function __construct($id,HostMeta $hostMeta){

        $this->hostMeta = $hostMeta;

        $this->hostTotalSize = $hostMeta->totalSize;

        $this->rootFile = new File($id);

        parent::__construct($this->getHostId($id));

    }

    public function getInfo(){

        parent::getInfo();

        $this->getChilds();

        $hostMeta = $this->getContent();

        if(!$hostMeta){


        }else{

            $this->eat(HostMeta::unserialize($hostMeta));

        }

        $this->setContent($this->hostMeta);

    }

    public function isRelative($unit){

        if($this->rootFile && \is_a($unit,File::class)){

            $ns = \str_replace($this->rootFile->getId().".part.","",$unit->getId());

            if(\is_numeric($ns)){
        
                return (int) $ns;
        
            }
        }
    
        return false;
    }

    public static function getHostId($id){

        return $id.".host";

    }

    public function eat(HostMeta $hostMeta){

        $this->currentChunkSize += $hostMeta->currentChunkSize;

    }


    function join($clear = false,$newname = null){

        if(!$this->rootFile) return;
    
        $content = null;
    
        foreach ($this->getChilds() as $file) {
            
            $content .= $file->getContent();
            
            if($clear){

                \destroy($file);

            }
    
        }
    
        $this->rootFile->setContent($content);

        $newname && $this->setName($newname);

        return $this->rootFile;
    
    }

    public function save(){

        parent::save();
        $this->rootFile->save();

    }

    public function canJoin(){

        if(
        $this->totalSize >= $this->hostTotalSize && 
        isRealArrayIndex($this->childs)){

            return true;

        }

        return false;

    }

    public function canMultipleFile(){

        return $this->totalSize > $this->currentChunkSize;

    }

    public function serialize(){

        return serialize($this);

    }
    public static function unserialize($data){

        return unserialize($data);

    }

}