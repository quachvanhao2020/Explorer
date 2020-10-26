<?php
namespace Explorer;

class FileUpload extends File{

    public $type;

    public $_allowedExtensions;

    public function __construct($id,$name = null){
        if (empty($_FILES)) {
            throw new \Exception('$_FILES array is empty');
        }
        $this->setName($name);
        parent::__construct($id);
    }


    public function getName(){
        //!$this->name && $this->name = basename($this->id);
        return $this->name;
    }

    public function getExtension(){
        !$this->extension && $this->extension = pathinfo($this->name)["extension"];
        return $this->extension; 
    }

    public function canExists(){
        return parent::canExists() && $this->validation();
    }

    protected function _moveFile($tmpPath, $destPath)
    {
        if (is_uploaded_file($tmpPath)) {
            return move_uploaded_file($tmpPath, $destPath);
        } elseif (is_file($tmpPath)) {
            return rename($tmpPath, $destPath);
        }
    }

    public function checkAllowedExtension($extension)
    {
        if (!is_array($this->_allowedExtensions) || empty($this->_allowedExtensions)) {
            return true;
        }
        return in_array(strtolower($extension), $this->_allowedExtensions);
    }

    public function validation(){
        if($this->getError() == self::NOERROR && $this->checkAllowedExtension($this->getExtension())){
            return true;
        }
        return false;
    }

    public function save(){
        if(\alive($this)){
            return $this->_moveFile($this->getId(),$this->getNewId());
        }
    }

    public function setType($type){

        $this->type = $type;
    }

    public static function arrayTo($data){
        return self::makeMe($data["tmp_name"],$data["name"],$data["type"],$data["error"],$data["size"]);
    }

    public static function makeMe($id,$name,$type,$error,$size){
        $me = new FileUpload($id,$name);
        $me->setType($type);
        $me->setError($error);
        $me->setSize($size);
        return $me;
    }

}

?>