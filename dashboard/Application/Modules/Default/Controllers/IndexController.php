<?php

class IndexController extends Library_Controller_Action
{
	public function indexAction()
	{
		// If user is not logged in, redirect to the login page
		if (!Library_Registry::loggedUser())
			$this->_helper->redirector->gotoRouteAndExit(array(), 'auth-login');
		
		Zend_Layout::getMvcInstance()->setLayout('app');
		
		$this->setTitle('CurrencyFair - Dashboard');

		$session = new Zend_Session_Namespace();
		$this->view->api = $session->api;
	}
}

