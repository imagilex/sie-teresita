<?php require_once "../../includes/loader.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Descriptor de Bases de Datos</title>
        <style type="text/css">
            p.table-title {
                font-weight: bold;
                font-size: 1em;
            }
            table.tbl-campos {
                border-collapse: collapse;
                border: 1px solid silver;
            }
            table.tbl-campos td {
                padding: 5px;
                border: 1px solid silver;
                font-size: 0.75em;
            }
            table.tbl-campos thead td, table.tbl-campos tfoot td {
                font-weight: bold;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <?php
        $descriptor = new DB_Descriptor(
            MAIN_DB->host, MAIN_DB->usr, MAIN_DB->pass, MAIN_DB->bd);
        foreach($descriptor->tables() as $tabla) {
            $campos = $descriptor->describe($tabla);
            echo HTML_Helper::tag("p", array("class"=>"table-title"), $tabla);
            echo HTML_Table::table(
                $campos, array("class"=>"tbl-campos"));
        }
        ?>
    </body>
</html>
