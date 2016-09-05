<?php
namespace JPF\TemplateEngine;

class Block {
    var $content;
    var $vars; //array of vars
    function Block($content){
        $this->content = $content;
        
    }
}
?>
