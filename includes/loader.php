<?php

include_once "database.php";
include_once "html_helpers.php";
include_once "dir_helpers.php";
include_once "get_vars_helpers.php";

define("MAIN_DIR", DirectoryHelper::get_site_root_directory(
    $_SERVER["DOCUMENT_ROOT"], $_SERVER["SCRIPT_FILENAME"]));

include_once MAIN_DIR . "/__settings.php";

define("MAIN_DB", new DB_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE));

date_default_timezone_set('America/Mexico_City');
?>
