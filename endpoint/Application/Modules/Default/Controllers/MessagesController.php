<?php

class MessagesController extends Library_Rest_Controller
{
	/**
	 * Usage: GET /messages
	 * 
	 * @throws Exception
	 */
	public function indexAction()
	{
		// Let's first check if we're allowed to access this resource
		$this->_checkAuth();
		
		$paramPage = intval($this->getRequest()->getParam('page'));
		
		if (!$paramPage)
			$paramPage = 1;
		
		$modelApiMessages = new Model_Api_Messages();
		
		try
		{
			// Fetch messages from the DB
			$messages = (new Model_Db_Table_Messages)->fetchAll(null, 'id DESC', 20, $paramPage - 1);

			// Let's prepare data for the output
			$data = array();

			foreach ($messages->toArray() as $message)
			{
				$data['data'][] = $modelApiMessages->prepareDataFromDatabase($message);
			}

			// Respond in JSON format
			$this->_helper->json($data);
		}
		catch (Exception $e)
		{
			$this->_displayError($e, 400);
		}
	}
	
	/**
	 * Usage: DELETE /messages/:id
	 * NOT IMPLEMENTED
	 * 
	 * @throws Exception
	 */
	public function deleteAction()
	{
		try
		{
			throw new Exception('Not implemented', 2);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e, 501);
		}
	}

	/**
	 * Usage: GET /messages/:id
	 * 
	 * @throws Exception
	 */
	public function getAction()
	{
		// Let's first check if we're allowed to access this resource
		$this->_checkAuth();
		
		$paramId = $this->getRequest()->getParam('id');
		$paramIds = explode(',', $paramId);
		
		$modelApiMessages = new Model_Api_Messages();

		try
		{
			// Fetch message(s) from the DB
			$messages = (new Model_Db_Table_Messages)->fetchAll(array('id IN (?)' => $paramIds), array('id DESC'));

			$data = array();

			// Let's prepare data for the output
			foreach ($messages->toArray() as $message)
			{
				$data['data'][] = $modelApiMessages->prepareDataFromDatabase($message);
			}

			// Respond in JSON format
			$this->_helper->json($data);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e, 400);
		}
	}

	/**
	 * Usage: HEAD /messages/:id
	 * NOT IMPLEMENTED
	 * 
	 * @throws Exception
	 */
	public function headAction()
	{
		try
		{
			throw new Exception('Not implemented', 2);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e, 501);
		}
	}

	/**
	 * Usage: POST /messages
	 * 
	 * @throws Exception
	 */
	public function postAction()
	{
		// NOTE: We're not checking if we're authorized to access this resource just for the review purposes
		
		$data = $this->getRequest()->getRawBody();

		$this->getResponse()->setHttpResponseCode(201);
		
		try
		{
			// Check if POST'd data is valid JSON object
			if (($data = json_decode($data, true)) !== null)
			{
				// Let's prepare data for the DB
				$messageData = (new Model_Api_Messages)->prepareDataForDatabase($data);

				// Insert message into the database
				$message = (new Model_Db_Table_Messages)->createRow($messageData);
				$message->save();
				
				// Send new message to our processor
				(new Model_Processor)->processMessage($message->toArray());
			}
			else
			{
				throw new Exception("Invalid data format", 1);
			}
		}
		catch (Exception $e)
		{
			$this->_displayError($e, 400);
		}
	}

	/**
	 * Usage: PUT /messages/:id
	 * NOT IMPLEMENTED
	 * 
	 * @throws Exception
	 */	
	public function putAction()
	{
		try
		{
			throw new Exception('Not implemented', 2);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e, 501);
		}
	}
}

