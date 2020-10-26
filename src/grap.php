<?php

function fsplit($file,$buffer=1024){

    $file_handle = fopen($file,'r');

    $file_size = filesize($file);

    $parts = $file_size / $buffer;

    $file_parts = array();

    $store_path = "splits/";

    $file_name = basename($file);

    for($i=0;$i<$parts;$i++){
        //read buffer sized amount from file
        $file_part = fread($file_handle, $buffer);
        //the filename of the part
        $file_part_path = $store_path.$file_name.".part$i";
        //open the new file [create it] to write
        $file_new = fopen($file_part_path,'w+');
        //write the part of file
        fwrite($file_new, $file_part);
        //add the name of the file to part list [optional]
        array_push($file_parts, $file_part_path);
        //close the part file handle
        fclose($file_new);
    }    
    //close the main file handle

    fclose($file_handle);
    return $file_parts;
}

function split($chunkSize = 10*1024){

    if(\alive($this->rootFile)){

        return $this->fsplit($this->rootFile->getId());

    }

}



