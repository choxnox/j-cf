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

        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();

        $this->initLayout($moduleName, $layout);
        $this->initNavigation($moduleName, $view);
        $this->initViewHelpers($moduleName, $view);
        $this->initViewScripts($moduleName, $view);
    }
    
    protected function initLayout($moduleName, Zend_Layout $layout)
    {
        if ($layout->getMvcEnabled())
        {
            $layout->setLayoutPath(sprintf(APPLICATION_PATH . '/Modules/%s/Views/Layouts', $moduleName));
            $layout->setLayout('default');
        }	    
    }

    protected function initNavigation($moduleName, $view)
    {
        $moduleNavigation = sprintf(APPLICATION_PATH . '/Modules/%s/Config/Navigation.txml', $moduleName);

        if (!file_exists($moduleNavigation))
            $moduleNavigation = sprintf(APPLICATION_PATH . '/Modules/%s/Config/Navigation.xml', $moduleName);
        
        if (file_exists($moduleNavigation))
        {
            $config = Model_Cache::get($moduleNavigation);
            
            if (!$config)
            {
                $config = new Zend_Config_Xml($moduleNavigation, 'nav');
                Model_Cache::set($moduleNavigation, $config);
            }
            
            $nav = new Zend_Navigation($config);
            $view->navigation()->addPages($nav);
        }
    }

    protected function initViewHelpers($moduleName, $view)
    {
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->addHelperPath(
            APPLICATION_PATH . 
            sprintf('/Modules/%s/Views/Helpers', $moduleName), 
            sprintf('%s_View_Helper', $moduleName)
        );    
    }

    protected function initViewScripts($moduleName, $view)
    {
        $view->setScriptPath(APPLICATION_PATH . '/Views/Scripts');
        $view->addScriptPath(array(
            APPLICATION_PATH . sprintf('/Modules/%s/Views/Scripts', $moduleName),
            LIBRARY_PATH . '/ViewScripts' 
        ));    
    }
}