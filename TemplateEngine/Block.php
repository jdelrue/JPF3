<?php
namespace JPF\TemplateEngine;

class Block {
    var $content;
    var $vars; //array of vars
    public function __construct($content){
        $this->content = $content;
        
    }
}
?>
