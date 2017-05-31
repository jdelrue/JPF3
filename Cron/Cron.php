<?php

namespace JPF\Cron;
require_once("Repositories.php");

use JPF\Cron\Repositories\CronRepository;
use JPF\Cron\Repositories\Cron as CronData;

class Cron {
    private $cronRepository;
    private $running = true;

    public function __construct(CronRepository $cronRepository){
        $this->cronRepository = $cronRepository;


    }
    public function Initialize($arrayOfVars){
        foreach($arrayOfVars as $var){
            echo $var[0];

            $$var[0] = $var[1];
            echo "HOST: ".$_SERVER['HTTP_HOST'];

        }
        exit(0);
    }

    public function Add($task, $params, $NextExcIn, $date = null){
        if($date == null){
            $date = date("Y-m-d H:i:s");
        }
         if(is_array($task)){ //(class, method)

            if(is_object($task[0])){
                $task[0] = get_class($task[0]);
            }
            $task = serialize($task);
         }else{
             return array(null, "Task should be array(classname/object, methodname)");
         }

        $params = serialize($params);

        $cronEntry = new CronData(0, $date, $task, $params, $NextExcIn);
        list($result, $error) = $this->cronRepository->put($cronEntry);
        if(isset($error)){
            return array(null, $error);
        }
        return array($result, null);

    }

    public function Start(){
        global $container;
        while($this->running){
            list($queue, $error) = $this->cronRepository->Find();
            foreach($queue as $task){
                    if($task->Date <= date("Y-m-d H:i:s")){
                        $taskArr = unserialize($task->Task);
                        $instanceOfClass = $container->Get($taskArr[0]);
                        $method = $taskArr[1];
                        $params = unserialize($task->Params);
                        list($result, $error) = $instanceOfClass->$method($params);
                        // list($result, $error) = call_user_func_array(array($instanceOfClass, $method), $params);

                        if($error){
                            try{
                                $error = $error->GetMessage();
                            }catch(\Exception $e){
                                if(!is_scalar($error)){
                                    $error = json_encode($error);
                                }
                            }

                            echo "ERROR: Executed job ".$taskArr[0]."->".$method." params ".json_encode($params)." Got error:".$error." \n";
                        }else{
                            echo "Executed job ".$taskArr[0]."->".$method." params ".json_encode($params)."\n";
                            echo "RESULT: " . json_encode($result) . "\n\n";
                        }
                        $datetime = new \DateTime(date('m/d/Y h:i:s a', time()));
                        $datetime->add(new \DateInterval('PT' . $task->NextExcIn));
                        $task->Date = $datetime->format('Y-m-d H:i');
                        $this->cronRepository->Update($task);

                    }
            }
            sleep(10);
        }  
    }
}


