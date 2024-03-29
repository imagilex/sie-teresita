<?php

if(!isset($__HTML_HPR_MODULE__)) {

    $__HTML_HPR_MODULE__ = true;

    class HTML_Helper {

        static public function tag(
            string $tag, iterable $attributes=array(), string $content="",
            bool $autoclose=false) {
            $attrs = array();
            foreach($attributes as $k => $v) {
                $k = strtolower($k);
                if($v === true) {
                    $v = $k;
                }
                $attrs[] = "$k=\"$v\"";
            }
            $closer = $autoclose ? " />" : ">$content</$tag>";
            return "<$tag " . implode(" ", $attrs) . $closer;
        }

        static public function JSBlock(string $codigo) {
            return HTML_Helper::tag(
                "script", array("language"=>"javascript"), $codigo);
        }

        static public function JSAlert(string $mensaje) {
            return HTML_Helper::JSBlock("alert(\"{$mensaje}\");");
        }

        static public function form_fecha(string $variable) {
            $var_d = $variable . "_d";
            $var_m = $variable . "_m";
            $var_a = $variable . "_a";
            $dia = HTML_Select::select_numeric($var_d, 1, 31);
            $mes = HTML_Select::create(
                $var_m,
                array(
                    "", "Ene", "Feb", "Mar", "Abr", "May", "Jun",
                    "Jul", "Ago", "Sep", "Oct", "Nov", "Dic", ),
                id:$variable, with_empty_opc:false);
            $anio = HTML_Input::text(
                $var_a, array(
                    "maxlength"=>"4", "size"=>"4",
                    "onblur"=>
                        "javascript: if(document.getElementById('{$var_a}').value.length!=4) alert('El año debe ser de cuatro digitos');"
                    ));
            return $dia." / ".$mes." / ".$anio;
        }

    }

    class HTML_Table {

        static public function td(
            iterable $attributes=array(), string $content="") {
            return HTML_Helper::tag("td", $attributes, $content);
        }

        static public function th(
            iterable $attributes=array(), string $content="") {
            return HTML_Helper::tag("th", $attributes, $content);
        }

        static public function tr_tag(
            iterable $attributes=array(), string $content="") {
            return HTML_Helper::tag("tr", $attributes, $content);
        }

        static public function tr(
            iterable $attributes=array(), iterable $cells_content=array(),
            iterable $cell_attributtes=array(), bool $is_th=false) {
            $cells = array();
            foreach($cells_content as $content) {
                $content = $content ? $content : "";
                $cells[] = $is_th
                    ? HTML_Table::th($cell_attributtes, $content)
                    : HTML_Table::td($cell_attributtes, $content);
            }
            return HTML_Table::tr_tag($attributes, implode($cells));
        }

        static public function tr_container(
            $tag, iterable $attributes=array(),
            iterable $tr_attributes=array(), iterable $rows_content=array(),
            iterable $cell_attributtes=array(), bool $is_th=false) {
            $rows = array();
            foreach($rows_content as $row) {
                $rows[] = HTML_Table::tr(
                    $tr_attributes, $row, $cell_attributtes, $is_th);
            }
            return HTML_Helper::tag(
                $tag, $attributes, implode($rows));
        }

        static public function tbody(
            iterable $attributes=array(), iterable $tr_attributes=array(),
            iterable $rows_content=array(), iterable $cell_attributtes=array(),
            bool $is_th=false) {
            return HTML_Table::tr_container(
                "tbody", $attributes, $tr_attributes, $rows_content,
                $cell_attributtes, $is_th);
        }

        static public function thead(
            iterable $attributes=array(), iterable $tr_attributes=array(),
            iterable $rows_content=array(), iterable $cell_attributtes=array(),
            bool $is_th=true) {
            return HTML_Table::tr_container(
                "thead", $attributes, $tr_attributes, $rows_content,
                $cell_attributtes, $is_th);
        }

        static public function tfoot(
            iterable $attributes=array(), iterable $tr_attributes=array(),
            iterable $rows_content=array(), iterable $cell_attributtes=array(),
            bool $is_th=true) {
            return HTML_Table::tr_container(
                "tfoot", $attributes, $tr_attributes, $rows_content,
                $cell_attributtes, $is_th);
        }

        static public function table(
            iterable $cells, iterable $attributes=array(),
            iterable $tr_attributes=array(),
            iterable $tr_attributes_head=array(),
            iterable $tr_attributes_foot=array(),
            iterable $cell_attributtes=array(),
            bool $head_as_th=false, bool $foot_as_th=false,
            bool $add_foot=false, mixed $headers="keys") {
            $hdrs = array();
            if($headers == "keys") {
                foreach($cells[0] as $k => $v) {
                    $hdrs[] = $k;
                }
            } else {
                $hdrs = $headers;
            }
            $htag = HTML_Table::thead(
                array(), $tr_attributes_head, array($hdrs, ),
                $cell_attributtes, $head_as_th);
            $ftag = $add_foot
                ? HTML_Table::tfoot(
                    array(), $tr_attributes_foot, array($hdrs, ),
                    $cell_attributtes, $foot_as_th)
                : "";
            $btag = HTML_Table::tbody(
                array(), $tr_attributes, $cells, $cell_attributtes);
            return HTML_Helper::tag(
                "table", $attributes, "{$htag}{$btag}{$ftag}");
        }

    }

    class HTML_Select {

        static public function create(
            string $name, iterable $options=array(),
            iterable $options_group=array(), string $id="",
            iterable $attributes=array(), bool $with_empty_opc=true) {
            return HTML_Select::select(
                $name, $options, $options_group,
                $id, $attributes, $with_empty_opc);
        }

        static public function select(
            string $name, iterable $options=array(),
            iterable $options_group=array(), string $id="",
            iterable $attributes=array(), bool $with_empty_opc=true) {
            $opcs = "";
            $opcs .= HTML_Select::options($options, $with_empty_opc);
            foreach($options_group as $g => $options) {
                $opcs .= HTML_Select::option_group($g, $options);
            }
            $attributes = array_merge($attributes, array("name"=>$name));
            if($id) {
                $attributes["id"] = $id;
            }
            return HTML_Helper::tag("select", $attributes, $opcs);
        }

        static public function select_numeric(
            string $name, int $start, int $end, int $step=1) {
            $opcs = array();
            foreach(range($start, $end, $step) as $opc) {
                $opcs[strval($opc)] = strval($opc);
            }
            return HTML_Select::create(
                $name, $opcs);
        }

        static public function option(string $value, string $label) {
            return HTML_Helper::tag("option", array("value"=>$value), $label);
        }

        static public function options(
            iterable $options, bool $with_empty_opc=true) {
            $opcs = $with_empty_opc ? HTML_Helper::tag("option") : "";
            foreach($options as $k => $v) {
                $opcs .= HTML_Select::option($k, $v);
            }
            return $opcs;
        }

        static public function option_group(
            string $label, iterable $options, bool $with_empty_opc=false) {
            $opcs = $with_empty_opc ? HTML_Helper::tag("option") : "";
            $opcs .= HTML_Select::options($options, $with_empty_opc);
            return HTML_Helper::tag("optgroup", array("label"=>$label), $opcs);
        }

    }

    class HTML_Input {

        static public function create(string $name, iterable $attributes=array(), string $id="") {
            return HTML_Input::input($name, $attributes, $id);
        }

        static public function input(string $name, iterable $attributes=array(), string $id="") {
            $attributes = array_merge($attributes, array("name"=>$name));
            if($id) {
                $attributes["id"] = $id;
            }
            return HTML_Helper::tag('input', $attributes, autoclose:true);
        }

        static public function text(string $name, iterable $attributes=array(), string $id="") {
            $attributes = array_merge($attributes, array("type"=>"text"));
            return HTML_Input::input($name, $attributes, $id);
        }

    }
}
?>
