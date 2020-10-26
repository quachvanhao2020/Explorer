<?php
namespace Explorer;

class FileRelative extends File{

    public $childs = [];

    public $totalSize = 0;

    public function __construct($id){

        parent::__construct($id);

    }

    public function isRelative($unit){

        if($unit->getDirname()== $this->getDirname()) return true;

    }

    public function getChilds(){

        if(!$this->childs){

            $folder = new Folder($this->getDirName());

            foreach ($folder->getChilds() as $unit) {
                $part = $this->isRelative($unit);

                if($part !== false){

                    $this->childs[$part] = $unit;

                    $this->totalSize += $unit->getSize();

                }
            
            }

        }

        return $this->childs;

    }

}