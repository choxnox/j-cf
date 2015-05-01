<?php

class Library_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function getModulePath()
    {
        return Zend_Controller_Front::getInstance()->getControllerDirectory(strtolower($this->getModuleName())) . '/..';
    }
    
	protected function _initNavigation()
	{
		$moduleNavigation = $this->getModulePath() . '/Config/Navigation.php';
		
		if (file_exists($moduleNavigation))
		{
			$navigation = include $moduleNavigation;

			foreach ($navigation as $key => &$value)
			{
				$value['id'] = sprintf('%s-%s', strtolower($this->getModuleName()), $value['id']);
			}

			$config = new Zend_Config($navigation);
			
			Zend_Layout::getMvcInstance()->getView()->navigation()->getContainer()->addPages($config);
		}		
	}
	
    protected function _initRoutes()
    {
		$router = Zend_Controller_Front::getInstance()->getRouter();

		$moduleRoutes = $this->getModulePath() . '/Config/Routes.php';

		if (file_exists($moduleRoutes))
		{
			$config = new Zend_Config(include $moduleRoutes);

			$router->addConfig($config, 'routes');

			$hostnameRoute = new Zend_Controller_Router_Route_Hostname($_SERVER['HTTP_HOST']);
			$router->addDefaultRoutes();

			foreach ($router->getRoutes() as $key => $route) 
			{
				if (substr($key, 0, 8) != 'hostname-')
				{
					$router->addRoute('hostname-' . $key, $hostnameRoute->chain($route));
				}
			}
		}
    }
}