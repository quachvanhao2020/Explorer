<?php
namespace Explorer;

class EntityExplorer{

        /**
     * @var string
     */
    protected $id;

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

    public function __construct($path,$id = null){
        $this->id = $id;
        $this->path = $path;
        $this->pathinfo = \pathinfo($path);
    }
}