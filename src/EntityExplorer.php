<?php
namespace Explorer;

abstract class EntityExplorer{

        /**
    * @var string
    */
    protected $name;
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

    public function __construct($path,$parent = null){
        $path = trim($path,DIRECTORY_SEPARATOR);
        $this->id = $path;
        $this->path = $path;
        $this->pathinfo = \pathinfo($path);
        $this->parent = $parent;
        $this->init();
    }

    protected abstract function init();

        /**
    * Get the value of path
    *
    * @return  string
    */ 
    public function getFullPath()
    {
        return ($this->parent ? $this->getParent()->getFullPath().DIRECTORY_SEPARATOR : DIRECTORY_SEPARATOR).$this->getPath(); 
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

}