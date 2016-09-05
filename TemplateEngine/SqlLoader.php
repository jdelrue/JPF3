<?php
namespace JPF\TemplateEngine;
/* we can add template stuff here later */
class SqlLoader {
    var $content;
    
    public function read_sql_file($file){
        $this->content = file_get_contents ($file);
        return $this->content;
    }
    
    
    function SetVar($name, $value){
        $this->vars[$name] = $value;

    }
    private function RemoveVars($string){
      
        do{
        $beginOfVar = strpos($string,"{{");
        $endOfVar = strpos($string,"}}");
        if($beginOfVar > 0){
            $string = substr($string,0, $beginOfVar).substr($string,$endOfVar+2);
        }
        if ( $endOfVar-$beginOfVar > 25) {
 
            break;
        }
       } while ($beginOfVar > 0);   
       return $string;
    }
    function ParseQuery(){
		//echo $this->content;
        $numberOfVars = Count($this->vars);
        $tempContent="";
        if(isset($this->content)){
            $tempContent = $this->content;
        }
        $vars = $this->vars;
        if(isset($vars)){
            foreach($vars as $toReplace => $value){
                $tempContent = str_replace("{{".$toReplace."}}",$value,$tempContent);
   
            }
        }
        
        return $this->RemoveVars($tempContent);
        //return $tempContent;
    }
    
    
}

?>