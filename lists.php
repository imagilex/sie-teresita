<?php

function Add_to_list($list, $curr, $atrib)
{
	if(!Is_in_list($list, $curr, $atrib) && mysql_query("insert into lista_atributo (lista, producto, valor_atributo, atributo) values ('$list', '$curr', '$atrib','1')"))
		return true;
	return false;
}

function Del_to_list($list, $curr, $atrib)
{
	$query="delete from lista_atributo where lista = '$list' and producto like '%$curr' and valor_atributo like '%$atrib'";
	if(mysql_query($query))
		return true;
	return false;
}

function Copy_to_list($list_from, $list_to, $curr, $atrib)
{
	return Add_to_list($list_to, $curr, $atrib);
}

function Move_to_list($list_from, $list_to, $curr, $atrib)
{
	return (Add_to_list($list_to, $curr, $atrib) && Del_to_list($list_from, $curr, $atrib));
}

function Clear_list($list)
{
	if(mysql_query("delete from lista_atributo where lista='$list'"))
		return true;
	return false;
}

function Del_list($list)
{
	$x=0;
	if(mysql_query("delete from lista where lista='$list'"))
		$x++;
	if(mysql_query("delete from lista_asociada where lista='$list' or lista_asociada='$list'"))
		$x++;
	if(mysql_query("delete from lista_atributo where lista='$list'"))
		$x++;
	if(mysql_query("delete from lista_usuario where lista='$list'"))
		$x++;
	if(Clear_list($list))
		$x++;
	if($x==4)
		return true;
	return false;
}

function Is_in_list($list, $curr, $atrib)
{
	$cuantos=@mysql_fetch_array(mysql_query("select count(*) as n from lista_atributo where lista='$list' and producto='$curr' and valor_atributo='$atrib'"));
	return (intval($cuantos["n"])>0);
}

function List_exists($list_name, $usr="")
{
	if($usr!="")
		$q="select count(*) as n from lista where nombre='$list_name' and usuario='$usr'";
	else
		$q="select count(*) as n from lista where nombre='$list_name'";
	$cuantos=@mysql_fetch_array(mysql_query($q));
	return (intval($cuantos["n"])>0);
}

function Change_name_list($list, $new_name)
{
	$cuantos=@mysql_fetch_array(mysql_query("select count(*) as n from lista where nombre = '$new_name'"));
	if(intval($cuantos["n"])==0 && mysql_query("update lista set nombre='$new_name' where lista='$list'"))
		return true;
	return false;
}

?>