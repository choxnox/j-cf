<?php

class Plugin_ModuleManager extends Zend_Controller_Plugin_Abstract 
{
    private $_lastModuleName = null;
    
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $this->initModule($request);
    }

    protected function initModule($request)
    {
        $this->_lastModuleName = $moduleName = ucfirst($request->getModuleName());
    }
}