<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<link rel="stylesheet" type="text/css" href="../style/Style_01.css" />

<?php

include("tabla.php");

$enc = array("primero","campo2","campo3","campo4","campo5","campo6");
$cells = array(
array("cherries",    "1999/11/02",        "124",        "3",        "edgar",    "http://www.yahoo.com"),
array("cherries",    "02/01/2006",        "12.3",        "35.12",    "ann",        "http://www.yahoo.com"),
array("bananas",    "02/01/2007",        "111",        "23.4",        "bob",        "http://www.yahoo.com"),
array("bananas",    "02/11/2007",        "1112",        "03",        "diane",    "http://www.yahoo.com"),
array("apples",        "12/01/2007",        "1",        "34.12",    "charlie",    "http://www.yahoo.com"),
array("bananas",    "1/11/05",            "10.02",    "345.654",    "hannah",    "http://www.yahoo.com"),
array("cherries",    "1/11/2005",        "109",        "23.456",    "igor",        "http://www.yahoo.com"),
array("apples",        "January 1, 2005",    "19.1",        "234.5",    "george",    "http://www.yahoo.com"),
array("bananas",    "January 10, 2005",    "12",        "34",        "francine",    "http://www.yahoo.com"),
array("bananas",    "November 1, 2005",    "11111",    "23.0123",    "julie",    "http://www.yahoo.com")
);


$tbl = new tabla();

$tbl->set("ruta_yui","../u_yui");
$tbl->set("alto","10em");
$tbl->set("ancho","30em");
$tbl->set("div","xyscrolling");
$tbl->set("encabezados",$enc);
$tbl->set("celdas",$cells);

$tbl->show();

?>
</head>

<body class="yui-skin-sam">
<div id="xyscrolling" ></div>

</body>
</html>
