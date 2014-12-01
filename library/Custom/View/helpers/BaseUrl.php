<?php

class Custom_View_Helper_BaseUrl
{

    function baseUrl()
    {
        $fc = Zend_Controller_Front::getInstance();
        if (defined('RUNNING_FROM_ROOT'))
        {

            $baseUrl .= '/rts/public';

            $fc->setBaseUrl($baseUrl);
        }

        return $fc->getBaseUrl();
    }

}