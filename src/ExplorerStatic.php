<?php
namespace Explorer;

class ExplorerStatic{

    public static function get_size($file)
    {
        static $iswin;
        if (!isset($iswin)) {
            $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
        }

        static $exec_works;
        if (!isset($exec_works)) {
            $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
        }

        if ($exec_works) {
            $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : "stat -c%s \"$file\"";
            @exec($cmd, $output);
            if (is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
                return $size;
            }
        }

        if ($iswin && class_exists("COM")) {
            try {
                $fsobj = new COM('Scripting.FileSystemObject');
                $f = $fsobj->GetFile( realpath($file) );
                $size = $f->Size;
            } catch (Exception $e) {
                $size = null;
            }
            if (ctype_digit($size)) {
                return $size;
            }
        }

        return filesize($file);
    }


}