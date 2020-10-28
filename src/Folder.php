<?php
namespace Explorer;

class Folder extends EntityExplorer{



    private function _getChildren(){
        $path = $this->getFullPath();
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                //if (!self::$SHOW_HIDDEN && substr($file, 0, 1) === '.') {continue;}
                $new_path = $path . '/' . $file;
                if (@is_file($new_path)) {
                    $file = new File($file,$this);
                    array_push($this->children,$file);
                } elseif (@is_dir($new_path) && $file != '.' && $file != '..') {
                    $folder = new Folder($file,$this);
                    array_push($this->children,$folder);
                }
            }
        }
        return $this->children;
    }

    public function exists(){
        $this->_exists = \is_dir($this->getFullPath());
        return $this->_exists;
    }

    protected function init(){
    $pathinfo = \pathinfo($this->path);
    $dirname = $pathinfo["dirname"];
    $basename = $pathinfo["basename"];
    $this->name = $basename;
    //var_dump($dirname);

    if(!($dirname == "." || $dirname == DIRECTORY_SEPARATOR)){
        //var_dump($dirname);
        $folder = new Folder($dirname,$this->getParent());
        $this->setParent($folder);
        $this->setPath($basename);
    }
    if(!$this->exists()){
        //throw new FolderNotFound($this->getFullPath());
    }
    }

    public function save(){
    $path = $this->getFullPath();
    if($this->children){
        foreach ($this->getChildren() as $entity) {
            if(!$entity->getParent()){
                $entity->setParent($this);
            }
            $entity->save();
        }
    }
    if (!file_exists($path)) {
        return mkdir($path,0777,true);
    }
    }

    /**
    * Get the value of children
    *
    * @return  mixed
    */ 
    public function getChildren()
    {
        if(!$this->children) $this->_getChildren();
        return $this->children;
    }

    /**
    * Set the value of children
    *
    * @param  mixed  $children
    *
    * @return  self
    */ 
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    public function destroy(){
    \fm_rdelete($this->getFullPath());
    }

    public function copyTo(Folder $folder){
    $fileSystem = new \Symfony\Component\Filesystem\Filesystem();
    try {
        $fileSystem->mirror($this->getFullPath(), $folder->getFullPath().DIRECTORY_SEPARATOR.$this->getName());
        return true;
    } catch (\Throwable $th) {
    }
    }

    public function moveTo(Folder $folder){
    if($this->copyTo($folder)){
        $this->destroy();
    }
    }

            /**
    * @var mixed
    */
    protected $children = [];

}