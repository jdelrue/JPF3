<?php
namespace JPF\TemplateEngine;

require ("Block.php");

class Template {

    var $blocks; //Array with blocks
    var $content; //Content of the file read

    function Template($file){
       $this->content=file_get_contents(Globals::$project_path.$file);
    }
    function AddFile($file){
        $this->content .= file_get_contents(Globals::$project_path.$file);
    }
    function DefineBlock($blockName){

        $beginOfBlockPos = strpos($this->content,"<!-- BEGIN ".$blockName." -->");
        $beginOfBlockPos += strlen("<!-- BEGIN ".$blockName." -->");
        $endOfBlockPos = strpos($this->content,"<!-- END ".$blockName." -->");
        $length = $endOfBlockPos - $beginOfBlockPos;
        $block = substr($this->content,$beginOfBlockPos, $length);
        $this->blocks["$blockName"] = new Block($block);
    }

    function setVars($blockName, $name, $value){
        $this->blocks["$blockName"]->vars[$name] = $value;

    }
    function ParseBlock($blockName){

        $numberOfVars = Count($this->blocks["$blockName"]->vars);
        $tempContent="";
        if(isset($this->blocks["$blockName"]->content)){
        $tempContent = $this->blocks["$blockName"]->content;
        }
        $vars = $this->blocks["$blockName"]->vars;
        if(isset($vars)){
            foreach($vars as $toReplace => $value){
                $tempContent = str_replace("{{".$toReplace."}}",$value,$tempContent);
   
            }
        }
        
        return $this->RemoveVars($tempContent);
        //return $tempContent;
    }
   private function RemoveVars($string){
      
        do{
        $beginOfVar = strpos($string,"{{");
        $endOfVar = strpos($string,"}}");
        if($beginOfVar > 0){
            $string = substr($string,0, $beginOfVar).substr($string,$endOfVar+2);
        }
        if ( $endOfVar-$beginOfVar > 25) {
  /*          echo "<br><br><br>";
            echo $string;
             echo "<br><br><br>";
            echo "NO MORE";
            echo $beginOfVar."<br>";
            echo $endOfVar;*/
            break;
        }
       } while ($beginOfVar > 0);   
       return $string;
    }
    function Show($parsed){
        echo $parsed;
    }
}
?>