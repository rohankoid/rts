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
            1 => array(),
            2 => array(),
            3 => array(),
            4 => array(),
            5 => array(),
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
        $scheduler->processTask();                
        $this->view->timeLineData = $scheduler->formatJSONOutput();
        $this->view->tasks = $scheduler->getTasks();   
        $this->view->withDeadLine = $scheduler->hasDeadLine();
    }


}

