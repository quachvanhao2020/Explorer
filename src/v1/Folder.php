<?php
namespace Explorer;

class Folder extends EntityExplorer{

    public $dirname;

    public $size;

    public $perms;

    public $childs = [];

    public $date;

    public function save(){

        $this->name && !self::icanExists($this->getNewId()) && rename($this->getId(),$this->getNewId());
    }

    public function setDirname($dirname){

        if($dirname instanceof Folder){

            $dirname = $dirname->getId();

        }

        $this->dirname = $dirname;

    }

    public function canExists(){

        return self::icanExists($this->id);

    }

    public static function icanExists($id){

        return \is_dir($id);

    }

    public function getDate(){

        !$this->date && $this->date = filemtime($this->getId());

        return $this->date;

    }

    public function getDirname(){

        !$this->dirname && $this->dirname = pathinfo($this->id)["dirname"];

        return $this->dirname;
        
    }

    public function getName(){

        !$this->name && $this->name = pathinfo($this->id)["basename"];

        return $this->name;
        
    }

    public function getPerms(){

        !$this->perms && $this->perms = substr(decoct(fileperms($this->id)), -4);

        return $this->perms;

    }

    public function getSize(){

        !$this->size && $this->size = ExplorerStatic::get_size($this->id);

        return $this->size;

    }

    private function _getChilds(){

        $objects = scandir($this->getId());
        
        if (is_array($objects)) {

            foreach ($objects as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                //if (!self::$SHOW_HIDDEN && substr($file, 0, 1) === '.') {continue;}
                $new_path = $this->getId() . '/' . $file;
                if (@is_file($new_path)) {

                    array_push($this->childs,new File($new_path));

                } elseif (@is_dir($new_path) && $file != '.' && $file != '..') {

                    array_push($this->childs,new Folder($new_path));

                }
            }
        }

        return $this->childs;

    }

    public function getChilds($zoom = false){

        !$this->childs && $this->childs = $this->_getChilds();

        if($zoom){

            foreach ($this->childs as $value) {

                if(\method_exists($value,__FUNCTION__)){

                    $value->{__FUNCTION__}($zoom);

                }

            }

        }

        return $this->childs;

    }

    public function setChilds($childs){

        $this->childs = $childs;

    }

    public function addChild($child){

        if($child instanceof Folder){

            $unit = new Folder($this->getId()."/".$child->getName());

            $unit = \alive($unit,true);

            $unit -> addChilds($child->getChilds());

        }else if($child instanceof File){

            $child->copyTo($this);

            $unit = $child;

        }

        \array_push($this->childs,$unit);

    }

    public function addChilds($childs){

        $this->getChilds();

        foreach ($childs as $value) {

            $this->addChild($value);

        }

    }

    public function getInfo(){

        $this->getSize();
        $this->getPerms();
        $this->getDirname();
        $this->getName();

        return $this;
    }

    public function canArouse(){

        mkdir($this->id);

    }

    public function destroy(){

       return self::fm_rdelete($this->getId());

    }

    public static function fm_rdelete($path)
    {
        if (is_link($path)) {
            return unlink($path);
        } elseif (is_dir($path)) {
            $objects = scandir($path);
            $ok = true;
            if (is_array($objects)) {
                foreach ($objects as $file) {
                    if ($file != '.' && $file != '..') {
                        if (!self::fm_rdelete($path . '/' . $file)) {
                            $ok = false;
                        }
                    }
                }
            }
            return ($ok) ? rmdir($path) : false;
        } elseif (is_file($path)) {
            return unlink($path);
        }
        return false;
    }

    public function copyTo(Folder $folder){

        if(alive($folder) || $folder = arouse($folder)){

            $this->getChilds(true);

            $folder->addChilds(arrayLazy($this));

        }

    }

}