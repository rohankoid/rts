<?php

class RTS_Task {

    CONST STATUS_READY = 'READY';
    CONST STATUS_DONE = 'DONE';
    CONST STATUS_INTERRUPTED = 'INTERRUPTED';

    protected $processName;
    protected $period;
    protected $computationTime;
    protected $deadLine;
    protected $computedTime;
    protected $status;
    protected $utilization;
    protected $interrupted;
    protected $startTime;
    protected $endTime;
    
    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    
    public function getProcessName()
    {
        return $this->processName;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function getComputationTime()
    {
        return $this->computationTime;
    }

    public function getDeadLine()
    {
        return $this->deadLine;
    }

    public function getComputedTime()
    {
        return $this->computedTime;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUtilization()
    {
        return $this->utilization;
    }

    public function getInterrupted()
    {
        return $this->interrupted;
    }

    public function setProcessName($processName)
    {
        $this->processName = $processName;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
    }

    public function setComputationTime($computationTime)
    {
        $this->computationTime = $computationTime;
    }

    public function setDeadLine($deadLine)
    {
        $this->deadLine = $deadLine;
    }

    public function setComputedTime($computedTime)
    {
        $this->computedTime = $computedTime;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setUtilization($utilization)
    {
        $this->utilization = $utilization;
    }

    public function setInterrupted($interrupted)
    {
        $this->interrupted = $interrupted;
    }

    public function __construct($task)
    {
        $this->processName = $task[0];
        $this->period = $task[1];
        $this->computationTime = $task[2];
        if (isset($task[3]))
        {
            $this->deadLine = $task[3];
        }
        
        if (isset($task[5]))
        {
            $this->status = $task[5];
        }
        
        if (isset($task[4]))
        {
            $this->computedTime = $task[4];
        }
        
        if (isset($task[6]))
        {
            $this->utilization = $task[6];
        }
    }

}
