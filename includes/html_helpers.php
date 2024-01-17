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
}
?>
