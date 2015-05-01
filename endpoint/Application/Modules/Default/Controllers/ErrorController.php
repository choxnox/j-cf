<?php

class ErrorController extends Zend_Controller_Action
{
	public function errorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		
		$errors = $this->getParam('error_handler');
		
		Zend_Debug::dump($errors);
		die('Error!');
	}
}

