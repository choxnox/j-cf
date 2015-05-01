<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->setFallbackAutoloader(true);
		$autoLoader->suppressNotFoundWarnings(false);
		
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH
        ));
        $resourceLoader->addResourceType('models', 'Models', 'Model');
        $resourceLoader->addResourceType('dbtable', 'Models/Db/Tables', 'Model_Db_Table');
        $resourceLoader->addResourceType('dbentity', 'Models/Db/Entities', 'Model_Db_Entity');
        $resourceLoader->addResourceType('plugins', 'Plugins', 'Plugin');
        $autoLoader->pushAutoloader($resourceLoader);
    }
	
    protected function _initErrorHandler()
    {
        register_shutdown_function(function() {
            $error = error_get_last();

            if (is_array($error))
            {
                switch ($error['type'])
                {
                    case E_ERROR:

                        break;
                }
            }
        });
    }

	protected function _initFrontControllerFix()
    {
        /*
         * Converting lower-case module controllers directory path to support *nix like OS with case-sensitive path
         *
         * @author Amir Ahmetovic
         */

        $frontController = Zend_Controller_Front::getInstance();
        $frontController->setModuleControllerDirectoryName('Controllers');

        $this->bootstrap('frontController');

        $controllerDirectories = $frontController->getControllerDirectory();
        $controllerDirectories = array_change_key_case($controllerDirectories);

        $frontController->setControllerDirectory($controllerDirectories);
    }
	
    protected function _initPlugins()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Plugin_ModuleManager());
    }
}	