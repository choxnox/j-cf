<?php

abstract class Library_Rest_Controller extends Zend_Rest_Controller
{
	public function init()
	{
		parent::init();
		
		// Disable view script
		$this->_helper->viewRenderer->setNoRender(true);
		
		// Let's allow everyone to use our REST
		header('Access-Control-Allow-Origin: *');
	}
	
	protected function _checkAuth()
	{
		$isAuthValid = false;
		
		try
		{
			// Let's check if there is an "Authorization" header in the request
			$authorization = $this->getRequest()->getHeader('Authorization');

			if ($authorization)
			{
				// Separate "clientId" and "accessToken"
				$api = explode(':', $authorization);
				
				if (count($api) == 2)
				{
					$clientId = $api[0];
					$accessToken = $api[1];
					
					// Let's see if access token is registered and valid
					$value = (new Model_Redis)->getValue($clientId);
					
					if ($value === $accessToken)
						$isAuthValid = true;
				}
				
				if (!$isAuthValid)
					throw new Exception('Provided authorization is not valid.', 403);
			}
			else
				throw new Exception('An authorization is required to access this resource.', 401);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e, $e->getCode());
		}
	}
	
	protected function _displayError($exception, $statusCode)
	{
		$this->getResponse()->setHttpResponseCode($statusCode);
		
		$error = array(
			'error' => array(
				'message' => $exception->getMessage(),
				'type' => get_class($exception),
				'code' => $exception->getCode()
			)
		);
		
		$this->_helper->json($error);
		die();
	}
}
