<?php

class Reports_Bootstrap extends Library_Application_Module_Bootstrap
{
    protected function _initAutoload()
    {
        $modelLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Reports',
            'basePath' => APPLICATION_PATH . '/Modules/Reports'
        ));
    }
}