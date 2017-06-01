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
            $config = $container->Get($_ENV["Config"]);
            $jobs = $config->getValue("CronJobs");
            list($queue, $error) = $this->cronRepository->Find();
            $queueOfArra=[];
            foreach($queue as $task){
                $taskArr = unserialize($task->Task);
                array_push($queueOfArra,$taskArr[0] );
                if($task->Date <= date("Y-m-d H:i:s")){
                    $instanceOfClass = $container->Get($taskArr[0]);
                    $method = $taskArr[1];
                    $params = unserialize($task->Params);
                    list($result, $error) = $instanceOfClass->$method(new \DateTime($task->Date));
                    if($error){
                        try{
                            $error = $error->GetMessage();
                        }catch(\Exception $e){
                            if(!is_scalar($error)){
                                $error = json_encode($error);
                            }
                        }

                        echo "ERROR: Executed job ".$taskArr[0]."->".$method." params ".json_encode($params)." Got error:".$error." \n";
                    }
                    else{
                        echo "Executed job ".$taskArr[0]."->".$method." params ".json_encode($params)."\n";
                        echo "RESULT: " . json_encode($result) . "\n\n";
                    }
                    //Only add to db if still in config
                    if (isset($jobs[$taskArr[0]])){
                        //TODO: Execute X times (if it was down, you still need data)
                        $datetime = new \DateTime($task->Date);
                        $datetime->add(new \DateInterval('P' . $jobs[$taskArr[0]]));
                        $task->Date = $datetime->format('Y-m-d H:i');
                        $this->cronRepository->Update($task);
                    }//else delte cron
                    else{
                        $this->cronRepository->Delete($task);
                    }
                    unset($jobs[$taskArr[0]]);
                    
                }
            }
            foreach($jobs as $jobInteration => $nextExecution){
                //If not in already in cron
                if(!in_array($jobInteration, $queueOfArra)){       
                    //add ron
                    $dateToExecute = new \DateTime(date('m/d/Y h:i:s a', time()));
                    $dateToExecute->add(new \DateInterval('P' . $nextExecution));
                    list($ok, $error) = $this->Add(array($jobInteration,"Handle"), null, $nextExecution );
                }
            }
            sleep(10);
        }  
    }
}


