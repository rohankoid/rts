<?php

class RTS_Scheduling {

    const CRITERIA_DEADLINE = 3;
    const CRITERIA_PERIOD = 1;

    protected $tasks = array();
    protected $majorCycle;
    protected $taskCollection;
    protected $task;
    protected $totalUtilization;
    protected $rtaFlag;
    protected $simplifiedRtaFlag;

    public function getSimplifiedRtaFlag()
    {
        return $this->simplifiedRtaFlag;
    }

    public function setSimplifiedRtaFlag($simplifiedRtaFlag)
    {
        $this->simplifiedRtaFlag = $simplifiedRtaFlag;
    }

    public function getRtaFlag()
    {
        return $this->rtaFlag;
    }

    public function setRtaFlag($rtaFlag)
    {
        $this->rtaFlag = $rtaFlag;
    }

    public function getTotalUtilization()
    {
        return $this->totalUtilization;
    }

    public function setTotalUtilization($totalUtilization)
    {
        $this->totalUtilization = $totalUtilization;
    }

    /**
     * 
     * @return RTS_TaskCollection
     */
    public function getTaskCollection()
    {
        return $this->taskCollection;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setTaskCollection($taskCollection)
    {
        $this->taskCollection = $taskCollection;
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function getMajorCycle()
    {
        return $this->majorCycle;
    }

    public function setMajorCycle($majorCycle)
    {
        $this->majorCycle = $majorCycle;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    public function __construct($tasks)
    {
        $this->setTasks($tasks);
        $taskCollection = new RTS_TaskCollection();
        $this->setTaskCollection($taskCollection);
    }

    protected function getCriteria()
    {
        $tasks = $this->getTasks();
        if (isset($tasks[0]) && isset($tasks[0][3]))
        {
            return self::CRITERIA_DEADLINE;
        } else
        {
            return self::CRITERIA_PERIOD;
        }
    }

    public function hasDeadLine()
    {
        if ($this->getCriteria() == 3)
        {
            return true;
        }
        return false;
    }

    public function orderByPriority()
    {
        $tasks = $this->getTasks();
        $critera = $this->getCriteria();
        $priority = array();
        foreach ($tasks as $key => $row) {
            $priority[$key] = $row[$critera];
        }
        array_multisort($priority, SORT_ASC, $tasks);
        $this->setTasks($tasks);
    }

    public function calculateMajorCycle()
    {
        $tasks = $this->getTasks();
        foreach ($tasks as $task) {
            $ar[] = $task[1];
        }
        $majorCycle = $this->lcmNums($ar);
        $this->setMajorCycle($majorCycle);
    }

    public function lcmNums($ar)
    {
        if (count($ar) > 1)
        {
            $ar[] = $this->lcm(array_shift($ar), array_shift($ar));
            return $this->lcmNums($ar);
        } else
        {
            return $ar[0];
        }
    }

    public function lcm($a, $b)
    {
        return ( $a / $this->gcf($a, $b) ) * $b;
    }

    public function gcf($a, $b)
    {
        return ( $b == 0 ) ? ($a) : ( $this->gcf($b, $a % $b) );
    }

    public function processTask()
    {
        $string = '';
        $interruptStack = $this->getTasks();
        $taskCollection = $this->getTaskCollection();
        $majorCycle = $this->getMajorCycle();
        $size = count($interruptStack);
        $flag = -1;
        for ($index = 0; $index < $majorCycle; $index++) {
            for ($i = 0; $i < $size; $i++) {
                if ($interruptStack[$i][5] == RTS_Task::STATUS_READY)
                {
                    $taskObj = new RTS_Task($interruptStack[$i]);
                    $taskObj->setStartTime($index);
                    $taskObj->setEndTime($index + 1);
                    $taskCollection->append($taskObj);
                    $string .= $interruptStack[$i][0];
                    $flag = $i;
                    break;
                } else
                {
                    $flag = -1;
                }
            }

            //check for interrupt and reassign values ready and not ready values
            if ($flag != -1)
            {
                $interruptStack[$flag][4] = $interruptStack[$flag][4] - 1;
                if ($interruptStack[$flag][4] == 0)
                {
                    $interruptStack[$flag][5] = RTS_Task::STATUS_DONE;
                }
            }
            for ($i = 0; $i < $size; $i++) {
                if (($index + 1) % $interruptStack[$i][1] == 0)
                {
                    $interruptStack[$i][5] = RTS_Task::STATUS_READY;
                    $interruptStack[$i][4] = (int) ($interruptStack[$i][4]) + (int) ($interruptStack[$i][2]);
                }
            }
        }

        return $string;
    }

    public function formatJSONOutput()
    {
        $data = array();
        $count = 0;
        $taskCollection = $this->getTaskCollection();
        foreach ($taskCollection as $task) {
            $data[$count][0] = $task->getProcessName();
            $data[$count][1] = $task->getStartTime() * 1000;
            $data[$count][2] = $task->getEndTime() * 1000;
            $count++;
        }

        return $data;
    }

    public function LuiLayland()
    {
        $count = count($this->getTasks());
        $luiLaylandUtilization = $count * (pow(2, (1 / $count)) - 1);
        return $luiLaylandUtilization;
    }

    /**
     * NOTE: also addes status_ready, calculated_time to the task array.
     * @return float total utilization
     */
    public function addUtilization()
    {
        $totalUtilization = 0;
        $tasks = $this->getTasks();
        foreach ($tasks as $task) {
            $utilization = round(($task[2] / $task[1]), 2);
            $task[5] = RTS_Task::STATUS_READY;
            $task[4] = $task[2];
            $task[6] = $utilization;
            $arr[] = $task;
            $totalUtilization += $utilization;
        }
        $this->setTasks($arr);
        $this->setTotalUtilization($totalUtilization);
        return $totalUtilization;
    }

    public function simplifiedRTA()
    {
        $count = 0;
        $this->setSimplifiedRtaFlag(TRUE);
        $tasks = $this->getTasks();
        $responseTime = array();
        foreach ($tasks as $task) {            
            $computationTime = $task[2];
            $deadLine = $task[3];
            $processName = $task[0];            
            if ($count == 0)
            {
                $responseTime[$processName] = $computationTime;                
                $count++;
                continue;
            }

            $currentResponseTime = $computationTime;
            $hpTasks = $this->getHPTasks($count);
            foreach ($hpTasks as $hpTask) {
                $hpTaskPeriod = $hpTask[1];
                $hpTaskComputation = $hpTask[2];                  
                $currentResponseTime += ceil($deadLine / $hpTaskPeriod) * $hpTaskComputation;                
            }
            $responseTime[$processName] = $currentResponseTime;
            if ($currentResponseTime > $deadLine)
            {                                
                $this->setSimplifiedRtaFlag(FALSE);
            }                       
            $count++;
        }

        return $responseTime;
    }

    public function exactRTA()
    {
        $count = 0;
        $this->setRtaFlag(TRUE);
        $tasks = $this->getTasks();
        $responseTime = array();
        foreach ($tasks as $task) {
            $rtaFlag = 0;
            $computationTime = $task[2];
            $processName = $task[0];
            $lastReponseTime = 0;
            if ($count == 0)
            {
                $responseTime[$processName][0] = $computationTime;
                $rtaFlag = 1;
                $count++;
                continue;
            }
            for ($index = 0; $index < 10; $index++) {
                $currentResponseTime = $computationTime;
                $hpTasks = $this->getHPTasks($count);
                foreach ($hpTasks as $hpTask) {
                    $hpTaskPeriod = $hpTask[1];
                    $currentResponseTime += ceil($lastReponseTime / $hpTaskPeriod);
                }
                $responseTime[$processName][$index] = $currentResponseTime;
                if ($currentResponseTime < $lastReponseTime || $currentResponseTime == $lastReponseTime)
                {
                    $rtaFlag = 1;
                    break;
                }
                $lastReponseTime = $currentResponseTime;
            }
            if (!$rtaFlag)
            {
                $this->setRtaFlag(FALSE);
            }
            $count++;
        }

        return $responseTime;
    }

    public function getHPTasks($index)
    {
        $tasks = $this->getTasks();
        for ($i = $index; $i > 0; $i--) {
            $hpTaks[] = $tasks[$i - 1];
        }
        return $hpTaks;
    }

}
