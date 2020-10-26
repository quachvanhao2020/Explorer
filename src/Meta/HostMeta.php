<?php
namespace Explorer\Meta;

class HostMeta{

    public $chunkSize;

    public $currentChunkSize;

    public $chunkNumber;

    public $totalSize;

    public function __construct($chunkSize,$currentChunkSize,$chunkNumber,$totalSize){

        $this->chunkSize = $chunkSize;
        $this->currentChunkSize = $currentChunkSize;
        $this->chunkNumber = $chunkNumber;
        $this->totalSize = $totalSize;

    }

    public function getPart(){

        return self::getPartIC("",$this->chunkNumber);

    }

    public static function getPartIC($filename,$chunkNumber){

        return $filename.".part.".$chunkNumber;

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