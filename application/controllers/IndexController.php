<?php

class IndexController extends Zend_Controller_Action
{
    // processName, period, computation_time, dead_line, computed_time, status, utilization
    protected $tasks = array(
            0 => array(
                        array('a',25,10),
                        array('b',25,8),
                        array('c',50,5),
                        array('d',50,4),
                        array('e',100,2),                        
                        ),
            1 => array(
                        array('a',80,40),
                        array('b',40,10),
                        array('c',20,5),
                ),
            2 => array(
                        array('a',3,1),
                        array('b',6,1),
                        array('c',5,1),
                        array('d',10,2),
                ),
            3 => array(
                        array('a',3,1, 3),
                        array('b',6,1, 6),
                        array('c',5,1, 5),
                        array('d',10,2, 10), 
                ),
            4 => array(
                        array('a',50,12),
                        array('b',40,10),
                        array('c',30,10),
                ),
            5 => array(
                        array('a',6,3, 5),
                        array('b',12,3, 6),                        
                ),
        );
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $tasks = $this->tasks[0];        
        $scheduler = new RTS_Scheduling($tasks);                        
        $scheduler->orderByPriority();
        $scheduler->calculateMajorCycle();        
        $this->view->totalUtilization = $scheduler->addUtilization();
        $this->view->utilization = $scheduler->LuiLayland();        
        $this->view->taskString = $scheduler->processTask();                
        $this->view->timeLineData = $scheduler->formatJSONOutput();
        $this->view->tasks = $scheduler->getTasks();   
        $this->view->withDeadLine = $scheduler->hasDeadLine();
        $this->view->exactResponseTime = $scheduler->exactRTA();
        $this->view->exactResponseTimeFlag = $scheduler->getRtaFlag();
        if($scheduler->hasDeadLine())
        {
            $this->view->simplifiedResponseTime = $scheduler->simplifiedRTA();
            $this->view->simplifiedResponseTimeFlag = $scheduler->getSimplifiedRtaFlag();
        }
    }


}

