<?php

//use Explorer\EntityExplorer;

require "vendor/autoload.php";

class FolderNotFound extends Exception{

}

class FileNotFound extends Exception{

}

abstract class EntityExplorer{

    /**
     * @var string
     */
    protected $id;

        /**
     * @var bool
     */
    protected $_exists = false;

    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $dirname;

    /**
     * @var string
     */
    protected $pathinfo;

    /**
     * @var Folder
     */
    protected $parent;

    public function __construct($path,$id = null){
        $this->id = $id;
        $this->path = $path;
        $this->pathinfo = \pathinfo($path);
    }

            /**
     * Get the value of path
     *
     * @return  string
     */ 
    public function getFullPath()
    {
        return ($this->parent ? $this->getParent()->getFullPath()."/" : "").$this->getPath(); 
    }

        /**
     * Get the value of path
     *
     * @return  string
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @param  string  $path
     *
     * @return  self
     */ 
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }


        /**
     * Get the value of parent
     *
     * @return  Folder
     */ 
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the value of parent
     *
     * @param  Folder  $parent
     *
     * @return  self
     */ 
    public function setParent(Folder $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    public abstract function save();
}

class Folder extends EntityExplorer{

            /**
     * @var mixed
     */
    protected $children = [];

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
                    $file = new File($file);
                    $file->setParent($this);
                    array_push($this->children,$file);
                } elseif (@is_dir($new_path) && $file != '.' && $file != '..') {
                    $folder = new Folder($file);
                    //$folder->setParent($this);
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

    public function init(){
        if(!$this->exists()){
            throw new FolderNotFound($this->getFullPath());
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
}

class File extends EntityExplorer{


    /**
     * @var string
     */
    protected $name;

        /**
     * @var string
     */
    protected $lastModified;

        /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $extension;

        /**
     * @var string
     */
    protected $content;
    
    public function __construct($path,$id = null){
        $this->id = $id;
        $this->path = $path;
        $pathinfo = \pathinfo($path);
        $this->pathinfo = $pathinfo;
    }

    public function __destruct()
    {
        if(!$this->_exists){
            return;
        }
        if($this->getLastModified() > filemtime($this->getFullPath())){
            $this->save();
        }
    }

    public function save(){
        if(!$this->_exists){
            if($this->parent){
                $this->getParent()->save();
            }     
        }
        $this->content && file_put_contents($this->getFullPath(),$this->getContent());
    }

    public function destroy(){
        unlink($this->getFullPath());
        $this->_exists = false;
    }

    public function exists(){
        $this->_exists = is_file($this->getFullPath()) && file_exists($this->getFullPath());
        return $this->_exists;
    }

    public function init(){
        $pathinfo = \pathinfo($this->path);
        $dirname = $pathinfo["dirname"];
        $basename = $pathinfo["basename"];
        $filename = $pathinfo["filename"];
        $extension = isset($pathinfo["extension"]) ? $pathinfo["extension"] : "";
        if(!($dirname == ".")){
            $folder = new Folder($dirname);
            $folder->setParent($this->getParent());
            $this->setParent($folder);
            $this->setPath($basename);
        }
        $this->name = $filename;
        $this->extension = $extension;
        $this->fileName = $basename;
        if(!$this->exists()){
            throw new FileNotFound($this->getFullPath());
        }
        $this->lastModified = filemtime($this->getFullPath());
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of extension
     *
     * @return  string
     */ 
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of extension
     *
     * @param  string  $extension
     *
     * @return  self
     */ 
    public function setExtension(string $extension)
    {
        $this->extension = $extension;

        return $this;
    }


    /**
     * Get the value of fileName
     *
     * @return  string
     */ 
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the value of fileName
     *
     * @param  string  $fileName
     *
     * @return  self
     */ 
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return  string
     */ 
    public function getContent()
    {
        !$this->content && $this->content = file_get_contents($this->getFullPath());
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param  string  $content
     *
     * @return  self
     */ 
    public function setContent(string $content)
    {
        $this->content = $content;
        $this->setLastModified(time());
        return $this;
    }

        /**
     * Get the value of lastModified
     *
     * @return  string
     */ 
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set the value of lastModified
     *
     * @param  string  $lastModified
     *
     * @return  self
     */ 
    public function setLastModified(string $lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }
}

$root = new Folder(__DIR__);
$testF = new Folder("test");
$testF->setParent($root);
$testF->init();

$dir = __DIR__."/test/neww/ewe/file3.text";
$dir = "file";
try {
    $file = new File($dir);
    $file->setParent($testF);
    $file->init();
    //$file->destroy();
    var_dump($file);
} catch (\FileNotFound $ex) {
    $file->save();
    //throw $ex;
}
$parent = $file->getParent();

$children = $parent->getChildren();



array_push($children,new Folder("new323/323"));
array_push($children,new Folder("new2/new3/new4"));
$parent->setChildren($children);
var_dump($parent->getChildren());
$parent->save();