<?php

namespace JPF3\Utils;

class Error{
    private $code;
    private $message;
    private $stacktrace;

    public function __construct($code, $message, $stacktrace = ""){
        global $errorHappened;
        global $container;
        $errorHappened = true;
        $this->message = $message;
        $this->code = $code;
        $this->stacktrace = $stacktrace;

     
    }
    public function GetMessage(){
        $message =  $this->code." ".$this->message;

      
            $message .= " ".$this->stacktrace;

        return $message;
    }
    public function Redirect() {

       
        global $message;
        $message = $this->message;
  

        


            $this->stacktrace = "";
            $errorUrl = "http://" . $_SERVER["HTTP_HOST"] . "/error.html?message=".$message;
            header('Location: ' . $errorUrl);

    }
    public function Response(){
        global $container;
        global $message;
  
        $message = $this->message;

        
        $error;
 
        if($this->code == 400){
            header("HTTP/1.0 400 Bad Request");
            $this->stacktrace .= "bad request";
        }else if($this->code == 404){
            header("HTTP/1.0 404 Not Found");
            $this->stacktrace .= "Not found";
        }else if($this->code == 403){
            header('HTTP/1.0 403 Forbidden');
            $this->stacktrace .= "Forbidden";
        }else if($this->code == 409){
            header('HTTP/1.0 409 Conflict');
            $this->stacktrace .= "Conflict";
        }else if ($this->code == 500){
            header('HTTP/1.0 500 Internal server error'); //@todo log this
            $this->stacktrace .= "Internal server error";
        }else{
            header('HTTP/1.0 500 Internal server error'); //@todo log this
            $this->stacktrace .= "Internal server error";
        }

        if ($this->configService->GetValue("EnableStackTrace")){ // Stack trace not enabled
            $this->stacktrace .= json_encode(debug_backtrace());
            global $stacktrace;
            $stacktrace = $this->stacktrace;
           
        }

        die(" "); // this makes sure global_shutdown is called with last_error known
    }
}