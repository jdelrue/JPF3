<?php

namespace JPF\TaskQueue;
require_once("Repositories.php");

use JPF\TaskQueue\Repositories\TaskQueueRepositoryInterface;
use JPF\TaskQueue\Repositories\TaskQueue as TaskQueueData;

class TaskQueue {
    private $taskQueueRepository;
    private $running = true;

    public function __construct(TaskQueueRepositoryInterface $taskQueueRepository){
        $this->taskQueueRepository = $taskQueueRepository;


    }
    public function Initialize($arrayOfVars){
        foreach($arrayOfVars as $var){
            echo $var[0];

            $$var[0] = $var[1];
            echo "HOST: ".$_SERVER['HTTP_HOST'];

        }
        exit(0);
    }

    public function Add($task, $params, $maxRetries = 5, $date = null, $Fallback = null){
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

        $taskQueueEntry = new TaskQueueData(0, $date, $task, $params, 0,$maxRetries, 0, null, json_encode($Fallback));
        list($result, $error) = $this->taskQueueRepository->put($taskQueueEntry);
        if(isset($error)){
            return array(null, $error);
        }
        return array($result, null);

    }

    public function Start(){
        global $container;
        while($this->running){
            list($queue, $error) = $this->taskQueueRepository->Find();
            foreach($queue as $task){
                if(!$task->Done){
                    if($task->Retries >= $task->MaxRetries){
                        if(isset($task->Fallback)){
                            echo"Adding fallback to queue\n";
                            $fb = json_decode($task->Fallback);
                            $fbtask = $fb->Task;
                            $fbparams = $fb->Params;
                            $fbmaxRetries = $fb->MaxRetries;
                            $this->Add($fbtask, $fbparams, $fbmaxRetries, null);
                        }
                        $task->Done = true;
                        $this->taskQueueRepository->Update($task);
                        continue;
                    }
                    if($task->Date <= date("Y-m-d H:i:s")){
                        $taskArr = unserialize($task->Task);
                        $instanceOfClass = $container->Get($taskArr[0]);
                        $method = $taskArr[1];
                        $params = unserialize($task->Params);
                        list($result, $error) = call_user_func_array(array($instanceOfClass, $method), $params);

                        if($error){
                        try{
                            $error = $error->GetMessage();
                        }catch(\Exception $e){
                            if(!is_scalar($error)){
                                $error = json_encode($error);
                            }
                        }

                        echo "ERROR: Executed task ".$taskArr[0]."->".$method." params ".json_encode($params)." Got error:".$error." \n";
                        $task->Retries += 1;
                        $datetime = new \DateTime($task->Date);
                        $datetime->modify('+2 hours');
                        $task->Date = $datetime->format('Y-m-d H:i');
                        
                        $task->LastError = $error;
                        $this->taskQueueRepository->Update($task);

                        }else{
                            echo "Executed task ".$taskArr[0]."->".$method." params ".json_encode($params)."\n";
                            $task->Done = true;
                            $this->taskQueueRepository->Update($task);
                        }

                    }
                }
            }
            sleep(10);
        }  
    }
}


