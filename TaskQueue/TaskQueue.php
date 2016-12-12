<?php

namespace JPF3\TaskQueue;

class TaskQueue {
    private $taskQueueRepository;
    private $running = true;

    public function __construct(TaskQueueRepositoryInterface $taskQueueRepository){
        $this->taskQueueRepository = $taskQueueRepository;


    }

    public function Add($task, $params, $maxRetries = 5, $date = null){
        if($date == null){
            $date = date("Y-m-d H:i:s");
        }
        if(is_array($task)){ //(class, method)
            $task = serialize($task);
        }
        $taskQueueEntry = new TaskQueue();

        $taskQueueEntry->Task = $task;
        $taskQueueEntry->Params = $params;
        $taskQueueEntry->MaxRetries = $maxRetries;
        $taskQueueEntry->Done = false;

        $taskQueueRepository->put($taskQueueEntry);
    
        


    }

    public function Start(){
        
        while($this->running){
            $tasks = $this->taskQueueRepository->Find();
            foreach($tasks as $task){
                if($task->Date <= date("Y-m-d H:i:s")){
                    $taskArr = unserialize($task);

                    $instanceOfClass = $container->Get($taskArr[0]);
                    $method = $container->Get($taskArr[1]);

                    list($result, $error) = $instanceOfClass->$method;

                    if($error){
                        $task->Retries += 1;
                        $datetime = new \DateTime($task->Date);
                        $datetime->modify('+2 hours');
                        $task->Date = $datetime->format('Y-m-d H:i');
                        $task->LastError = $error;
                        $this->taskQueueRepository->Update($task);

                    }

                }
            }
            sleep(120);
        }  
    }
}


