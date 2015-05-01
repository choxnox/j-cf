<?php

class Model_Api_Messages
{
	const ERROR_INVALID_DATA = 10001;
	
	public function prepareDataFromDatabase($data)
	{
		$data['user_id'] = strval($data['user_id']);
		$data['amount_sell'] = floatval($data['amount_sell']);
		$data['amount_buy'] = floatval($data['amount_buy']);
		$data['rate'] = floatval($data['rate']);
		
		$timePlaced = strtoupper((new Zend_Date($data['datetime_created'], 'y-MM-dd HH:mm:ss', 'en'))->toString('dd-MMM-yy HH:mm:ss'));
	
		return array(
			'id' => $data['id'],
			'userId' => $data['user_id'],
			'currencyFrom' => $data['currency_from'],
			'currencyTo' => $data['currency_to'],
			'amountSell' => $data['amount_sell'],
			'amountBuy' => $data['amount_buy'],
			'rate' => $data['rate'],
			'timePlaced' => $timePlaced,
			'originatingCountry' => $data['country_code']
		);
	}
	
	public function prepareDataForDatabase($data)
	{
		if (count($data) != 8)
			throw new Exception('Invalid number of parameters', self::ERROR_INVALID_DATA);
		
		if (!(new Zend_Validate_Digits)->isValid($data['userId']))
			throw new Exception('Invalid "userId" field', self::ERROR_INVALID_DATA);
		
		if (strlen($data['currencyFrom']) != 3)
			throw new Exception('Invalid "currencyFrom" field', self::ERROR_INVALID_DATA);
		
		if (strlen($data['currencyTo']) != 3)
			throw new Exception('Invalid "currencyTo" field', self::ERROR_INVALID_DATA);
		
		if (!(new Zend_Validate_Float(array('locale' => 'en')))->isValid($data['amountSell']))
			throw new Exception('Invalid "amountSell" field', self::ERROR_INVALID_DATA);

		if (!(new Zend_Validate_Float(array('locale' => 'en')))->isValid($data['amountBuy']))
			throw new Exception('Invalid "amountBuy" field', self::ERROR_INVALID_DATA);
		
		if (!(new Zend_Validate_Float(array('locale' => 'en')))->isValid($data['rate']))
			throw new Exception('Invalid "rate" field', self::ERROR_INVALID_DATA);
		
		$data['timePlaced'] = strtolower($data['timePlaced']);
		$data['timePlaced'] = implode('-', array_map('ucfirst', explode('-', $data['timePlaced'])));
		
		if (!(new Zend_Validate_Date(array('locale' => 'en', 'format' => 'dd-MMM-yy HH:mm:ss')))->isValid($data['timePlaced']))
			throw new Exception('Invalid "timePlaced" field', self::ERROR_INVALID_DATA);

		if (strlen($data['originatingCountry']) != 2)
			throw new Exception('Invalid "originatingCountry" field', self::ERROR_INVALID_DATA);

		$datetimeCreated = (new Zend_Date($data['timePlaced'], 'dd-MMM-yy HH:mm:ss', 'en'))->toString('YYYY-MM-dd HH:mm:ss');

		return array(
			'user_id' => $data['userId'],
			'currency_from' => $data['currencyFrom'],
			'currency_to' => $data['currencyTo'],
			'amount_sell' => $data['amountSell'],
			'amount_buy' => $data['amountBuy'],
			'rate' => $data['rate'],
			'datetime_created' => $datetimeCreated,
			'country_code' => $data['originatingCountry']
		);
	}
}
