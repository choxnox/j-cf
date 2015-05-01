<?php

class AuthController extends Library_Controller_Action
{
	public function loginAction()
	{
		// If user is logged in, redirect to the application page
		if (Library_Registry::loggedUser())
			$this->_helper->redirector->gotoRouteAndExit(array(), 'app');

		Zend_Layout::getMvcInstance()->setLayout('login');
		
		$this->setTitle('CurrencyFair - Dashboard');
		
		$formLogin = new Form_Login();
		
		// If login form is posted, let's try to login the user
		if ($this->getRequest()->isPost())
		{
			$postData = $this->getRequest()->getPost();
			
            $modelAuth = new Model_Auth();
            $authAdapter = $modelAuth->getAuthAdapter();
			
			if ($formLogin->isValid($postData))
			{
				$formData = $formLogin->getValues();
				
				$authAdapter
                    ->setIdentity($formData['username'])
                    ->setCredential($formData['password'])
                ;
				
                $auth = $modelAuth->getAuth();
                $result = $auth->authenticate($authAdapter);

				// Check if credentials are valid
                if ($result && $result->isValid())
                {
					$user = $authAdapter->getResultRowObject();
					$auth->getStorage()->write($user);

					// Generate user's access token
					$accessToken = base64_encode(hash('sha256', $user->api_client_id . time()));
					
					// Put it in the Redis DB and make it valid for 1 hour
					(new Model_Redis)->setValueTTL($accessToken, $user->api_client_id, 3600);
						
					// Save API data to the session so we can reuse it when making calls to our REST server
					$session = new Zend_Session_Namespace();
					$session->api = array(
						'clientId' => $user->api_client_id,
						'accessToken' => $accessToken
					);
					
					$this->_helper->redirector->gotoRouteAndExit(array(), 'app');
                }
                else
                {
                    $this->messenger(array(
                        'closable' => false,
                        'color' => 'red',
                        'message' => 'Wrong username/password combination'
                    ));
                }
			}
		}
		
		$data = array(
			'formLogin' => $formLogin
		);
		
		$this->view->data = $data;
	}
	
	public function logoutAction()
	{
		// If user is not logged in, redirect to the login page
		if (!Library_Registry::loggedUser())
			$this->_helper->redirector->gotoRouteAndExit(array(), 'auth-login');

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		// Log out the user
    	Zend_Auth::getInstance()->clearIdentity();
		
		// Remove API data from the session
		$session = new Zend_Session_Namespace();
		unset($session->api);
		
		$this->_helper->redirector->gotoRouteAndExit(array(), 'auth-login');
	}
}

