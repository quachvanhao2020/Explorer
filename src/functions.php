<?php

use Explorer\EntityExplorerAbstract;

function isRealArrayIndex($array){

    $index = 0;

    foreach ($array as $key => $value) {

        if(!array_key_exists($index,$array)) return false;

        $index++;

    }

    return $array;

}

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
 }

function convert($obj,$class,$option = []){

    $currentClass = clean(get_class($obj));

    return $class::$currentClass($obj);

}

function converts($objs,$class,$option = []){

    foreach ($objs as $key => $value) {
        
        $objs[$key] = convert($value,$class);
    }

    return $objs;

}

function alive($obj,$arouse = false){

    if($obj && $obj->isAlive()){

        return $obj;

    }else if($arouse){

        return arouse($obj);

    }

}

function arrayLazy($obj){

    if(is_array($obj)) return $obj;

    return [$obj];

}

function save($obj){

    $save = $obj->save();

    /*

    $saves = arrayLazy($save);

    foreach ($saves as $value) {

        if($value instanceof EntityExplorerAbstract){

            $save = save($value);
     
        }

    }

    */

    return $save;

}

function destroy($obj){

    return $obj->destroy();
}

function arouse($obj){

    //$obj = $class::arouse();

    $obj->arouse();

    if(alive($obj)) return $obj;

}



function size_convert($size)
{
    if ($size < 1000) {
        return sprintf('%s B', $size);
    } elseif (($size / 1024) < 1000) {
        return sprintf('%s KB', round(($size / 1024), 2));
    } elseif (($size / 1024 / 1024) < 1000) {
        return sprintf('%s MB', round(($size / 1024 / 1024), 2));
    } elseif (($size / 1024 / 1024 / 1024) < 1000) {
        return sprintf('%s GB', round(($size / 1024 / 1024 / 1024), 2));
    } else {
        return sprintf('%s TB', round(($size / 1024 / 1024 / 1024 / 1024), 2));
    }
}