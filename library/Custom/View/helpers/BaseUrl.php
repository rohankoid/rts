<?php

class Custom_View_Helper_BaseUrl
{

    function baseUrl()
    {
        $fc = Zend_Controller_Front::getInstance();
        if (defined('RUNNING_FROM_ROOT'))
        {

            $baseUrl .= '/minsurance/public';

            $fc->setBaseUrl($baseUrl);
        }

        return $fc->getBaseUrl();
    }

}