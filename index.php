<?php

require "vendor/autoload.php";
use Explorer\Folder;
use Explorer\File;

$root = new Folder(__DIR__);

$testF = new Folder("test",$root);

$children = $testF->getChildren();
$new = new Folder("new323/323",$testF);
array_push($children,$new);
$file = new File("text.text",$new);
$file->setContent("323432");
array_push($children,$file);
array_push($children,new Folder("new323/323/3233",$new));

$testF->setChildren($children);
//var_dump($parent->getChildren());
$testF->save();
var_dump($children);

return;


$test2 = new Folder("test2",$root);

$dir = __DIR__."/test/neww/ewe/file3.text";
//$dir = "folder/file";
$file = new File($dir);
$parent = $file->getParent();
$children = $parent->getChildren();
//array_push($children,@new Folder("new2/new3/new4"));

//var_dump($parent);
var_dump($children);
//$parent->setChildren($children);
//var_dump($parent->getChildren());
//$parent->save();

return;
$file->setContent("888");
$file->setName("text2222");
$file->setExtension("txt");
$file->setFileName("hao.xxx");
//$file->destroy();
var_dump($file);
$file->save();


