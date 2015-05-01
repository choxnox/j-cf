<?php

class Model_Redis
{
	private static $_namespace = 'currencyfair';

	private static $_hostname = 'localhost';
	
    public static function delete($key)
    {
		$redis = new Redis();
		$redis->connect(self::$_hostname);
		
		$redis->delete(self::_generateKey($key));
    }
    
	public static function deleteValue($key)
	{
		$key = func_get_args();
		$key = implode('_', $key);
		
		return self::delete($key);
	}
    
    public static function get($key)
    {
		$redis = new Redis();
		$redis->connect(self::$_hostname);
		
		return $redis->get(self::_generateKey($key));
    }
    
	public static function getValue($key)
	{
		$key = func_get_args();
		$key = implode('_', $key);
		
		return self::get($key);
	}
	
    public static function set($key, $value, $ttl = false)
    {
		$redis = new Redis();
		$redis->connect(self::$_hostname);
		
		$key = self::_generateKey($key);
		
		if ($ttl)
			$redis->set($key, $value, $ttl);
		else
			$redis->set($key, $value);
    }
    
	public static function setValue($value, $key)
	{
		$key = array_slice(func_get_args(), 1);
		$key = implode('_', $key);
		
		self::set($key, $value);
	}
	
	private static function _generateKey($key)
	{
		return md5(sprintf('%s:%s', self::$_namespace, $key));
	}
}