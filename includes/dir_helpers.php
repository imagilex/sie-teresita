<?php

if(!isset($__DIRECTORY_MODULE__)) {

    $__DIRECTORY_MODULE__ = true;

    class DirectoryHelper {

        static public function get_site_root_directory(
            string $document_root, string $script_filename) {
            $pos = str_contains($script_filename, $document_root)
                ? strpos($script_filename, '/', strlen($document_root) + 3)
                : 0;
            if(!$pos) {
                throw new Exception("No esp osible obtener la ruta raíz");
            }
            return substr($script_filename, 0, $pos) . "/";
        }

    }
}
?>
