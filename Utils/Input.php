<?php

namespace JPF\Utils;

class Input{

    public function __construct(){

    }
    /* letters or numbers no special chars*/
    public static function CheckWord($values, $response = true){
        $regex = "/^\w+$/";
        if($response){
            return self::ChecknResponse($regex, $values);
        }else{
            return self::Check($regex , $values);
        }

    }
    public static function CheckWordOrWhiteSpace($values, $response = true){
        $regex = "/^(\w|\ )+$/";
        if($response){
            return self::ChecknResponse($regex, $values);
        }else{
            return self::Check($regex , $values);
        }

    }

    public static function CheckCity($values, $response = true){
        $regex = "/^[a-zA-Z]+(?:[\s-]+[a-zA-Z]+)*$/"; //"/^(\w|\ |-)+$/";
        if($response){
            return self::ChecknResponse($regex, $values);
        }else{
            return self::Check($regex , $values);
        }

    }

    public static function CheckMail($values, $response = true){
        $regex = "/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/";
        if($response){
            return self::ChecknResponse($regex, $values);
        }else{
            return self::Check($regex , $values);
        }

    }

    public static function ChecknResponse($pattern, $values){
        $match = self::Check($pattern, $values);
        if(!$match){
             $error = new Error(400, "Input does not pass match filter");
             $error->Response();
             return false;
        }
        return true;
    }
    public static function Check($pattern, $values){
        if(is_array($values)){
            foreach($values as $value){
                if(!preg_match($pattern, $value)){
                    return false;
                }
            }
        }else if(is_string($values)){
             if(!preg_match($pattern, $values)){
                    return false;
                }
        }
        return true;
    }

};