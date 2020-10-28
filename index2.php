$parent = $file->getParent();

$children = $parent->getChildren();
array_push($children,@(new Folder("new323/323")));
array_push($children,@new Folder("new2/new3/new4"));
$parent->setChildren($children);
//var_dump($parent->getChildren());
//$parent->save();

//var_dump($file);

//$file->copyTo($testF);
$testF->moveTo($test2);
var_dump($testF);