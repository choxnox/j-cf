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
        $resourceLoader->addResourceType('dblist', 'Models/Db/Lists', 'Model_Db_List');
        $resourceLoader->addResourceType('helper', 'Models/Helpers', 'Model_Helper');
        $resourceLoader->addResourceType('plugins', 'Plugins', 'Plugin');
        $resourceLoader->addResourceType('service', 'Models/Services', 'Model_Service');
        $resourceLoader->addResourceType('view/helper', 'Views/Helpers', 'View_Helper_');
        $autoLoader->pushAutoloader($resourceLoader);
    }
	
    protected function _initAuth()
    {
		$this->bootstrap('db');
		
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $userId = Zend_Auth::getInstance()->getStorage()->read()->id;

			$user = (new Model_Db_Table_Users)->getOneById($userId);
			
            Zend_Registry::set('loggedUser', $user);
        }
        else
            Zend_Registry::set('loggedUser', null);
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

		$frontController->setBaseUrl('/currencyfair/dashboard');
	}
	
    protected function _initSessions()
    {
        $this->bootstrap('session');

        $options = $this->getOption('resources');
        Zend_Session::setOptions($options['session']);

        Zend_Session::start();
    }

    protected function _initViewSettings()
	{
        $view = $this->bootstrap('view')->getResource('view');
		
        $view->addHelperPath(APPLICATION_PATH . '/Views/Helpers', 'View_Helper');
		
		$view->headLink()
			->appendStylesheet('assets/3rd-party/bootstrap/css/bootstrap.min.css')
			->appendStylesheet('assets/dashboard/css/style.css')
		;
		
		$view->headScript()
			->appendFile('assets/3rd-party/jquery/js/jquery-2.1.3.min.js')
			->appendFile('assets/3rd-party/underscore/js/underscore-min.js')
			->appendFile('assets/3rd-party/bootstrap/js/bootstrap.min.js')
			->appendFile('assets/3rd-party/socket.io/js/socket.io.js')
			->appendFile('assets/3rd-party/highcharts/js/highcharts.js')
			->appendFile('assets/3rd-party/highmaps/js/map.js')
			->appendFile('//code.highcharts.com/maps/modules/data.js')
			->appendFile('//code.highcharts.com/mapdata/custom/europe.js')
		;
	}	
}	