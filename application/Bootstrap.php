<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Register Namespace
     */
    protected function _initRegisterNamespace() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        
        $autoloader->registerNamespace('Custom_');
        /**
         * Register RTS
         */
        $autoloader->registerNamespace('RTS_');

    }

    

}

