<?php

class Reports_ByCountryController extends Library_Rest_Controller
{
	/**
	 * Usage: GET /reports/by_country
	 * 
	 * @throws Exception
	 */
	public function indexAction()
	{
		// Let's first check if we're allowed to access this resource
		$this->_checkAuth();
		
		$paramDate = $this->getRequest()->getParam('date');

		if (!$paramDate)
			$paramDate = 'today';
		
		// Handle special "date" values such as "today"
		switch ($paramDate)
		{
			case 'today':
				$paramDate = new Zend_Db_Expr('NOW()');
		}
		
		try
		{
			// Fetch report from the DB
			$report = (new Model_Db_Table_Reports_Country)->fetchAll(array('`date` = DATE(?)' => $paramDate));

			$data = array(
				'data' => $report->toArray()
			);

			// Respond in JSON format
			$this->_helper->json($data);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e, 400);
		}
	}
	
	/**
	 * Usage: DELETE /reports/by_country/:id
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
			$this->_displayError($e);
		}
	}

	/**
	 * Usage: GET /reports/by_country/:id
	 * NOT IMPLEMENTED
	 * 
	 * @throws Exception
	 */		
	public function getAction()
	{
		try
		{
			throw new Exception('Not implemented', 2);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e);
		}
	}

	/**
	 * Usage: HEAD /reports/by_country/:id
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
			$this->_displayError($e);
		}
	}

	/**
	 * Usage: POST /reports/by_country
	 * NOT IMPLEMENTED
	 * 
	 * @throws Exception
	 */		
	public function postAction()
	{
		try
		{
			throw new Exception('Not implemented', 2);
		} 
		catch (Exception $e) 
		{
			$this->_displayError($e);
		}
	}

	/**
	 * Usage: PUT /reports/by_country/:id
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
			$this->_displayError($e);
		}
	}
}

