<?php
namespace Explorer;

class File extends EntityExplorer{

        /**
    * @var string
    */
    protected $dirname;

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

    public function __destruct()
    {
    if(!$this->_exists){
        return;
    }
    if($this->getLastModified() > @filemtime($this->getFullPath())){
        //$this->save();
    }
    }

    public function save(){
    if(!$this->_exists){
        if($this->parent){
            $this->getParent()->save();
        }   
    }
    if($this->content){
        file_put_contents($this->getFullPath(),$this->getContent());
    }else{
        touch($this->getFullPath());  
    }
    }

    public function destroy(){
    unlink($this->getFullPath());
    $this->_exists = false;
    }

    public function exists(){
    $this->_exists = is_file($this->getFullPath()) && file_exists($this->getFullPath());
    return $this->_exists;
    }

    protected function init(){
        $pathinfo = \pathinfo($this->path);
        $dirname = $pathinfo["dirname"];
        $basename = $pathinfo["basename"];
        $filename = $pathinfo["filename"];
        $extension = isset($pathinfo["extension"]) ? $pathinfo["extension"] : "";
        if(!($dirname == ".")){
            $folder = new Folder($dirname,$this->getParent());
            $this->setParent($folder);
            $this->setPath($basename);
        }
        $this->dirname = $dirname;
        $this->name = $filename;
        $this->extension = $extension;
        $this->fileName = $basename;
        if(!$this->exists()){
            $this->save();
            //throw new FileNotFound($this->getFullPath());
        }
        $this->lastModified = filemtime($this->getFullPath());
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
    $this->clear();
    return $this;
    }

    public function clear(){
    $pathinfo = \pathinfo($this->fileName);
    $this->setName($pathinfo["basename"]);
    $this->setExtension($pathinfo["extension"]);
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

    public function copyTo(Folder $folder){
        return copy($this->getFullPath(),$folder->getFullPath().DIRECTORY_SEPARATOR.$this->getFileName());
    }

    public function moveTo(Folder $folder){
        if($this->copyTo($folder)){
            $this->destroy();
        }
    }

        /**
    * Get the value of path
    *
    * @return  string
    */ 
    public function getPath()
    {
        return $this->getName().".".$this->getExtension();
    }

    /**
    * Get the value of dirname
    *
    * @return  string
    */ 
    public function getDirname()
    {
    return $this->dirname;
    }

    /**
    * Set the value of dirname
    *
    * @param  string  $dirname
    *
    * @return  self
    */ 
    public function setDirname(string $dirname)
    {
    $this->dirname = $dirname;

    return $this;
    }
}
