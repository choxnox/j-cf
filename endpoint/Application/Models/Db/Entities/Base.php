<?php

class Model_Db_Entity_Base extends Zend_Db_Table_Row_Abstract
{
    public function isEmpty()
    {
        return empty($this->_data);
    }
    
    public function __call($method, array $args)
    {
        if (preg_match('/^(get|set)(.+?)(FromJSON)?$/', $method, $matches))
        {
            $prefix = $matches[1];
			$propertyName = strtolower($this->fromCamelCase($matches[2]));
            
            if (isset($matches[3]))
                $suffix = $matches[3];
            else
                $suffix = null;
            
            if ($suffix)
            {
                if (array_key_exists($propertyName, $this->_data) || property_exists($this, $propertyName))
                {
                    if ($prefix == 'get')
                        $propertyValue = $this->{$propertyName};
                    else 
                        throw new Exception('Methods with special suffixes work only with "get" method');
                }
                else
                    throw new Exception("Invalid property: {$propertyName}");
                
                switch ($suffix)
                {
                    case 'FromJSON':
                        $returnAsArray = @(boolean)$args[0];
                        return json_decode($propertyValue, $returnAsArray);
                        break;
                }
            }
            else 
            {
                if (array_key_exists($propertyName, $this->_data) || property_exists($this, $propertyName))
                {
                    if ($prefix == 'get')
                        return $this->{$propertyName};
                    elseif ($prefix == 'set')
                    {
                    $this->{$propertyName} = $args[0];

                        return $this;
                    }
                }
                else
                    throw new Exception("Invalid property: {$propertyName}");
            }
        }
    }
	
    public function __set($name, $value)
    {
        if (!array_key_exists($name, $this->_data) && !property_exists($this, $name))
        {
            //throw new Zend_Exception("You cannot set new properties on this object ({$name})");
        }

        if (array_key_exists($name, $this->_data))
            parent::__set($name, $value);
        else
            $this->$name = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_data))
        {
            return $this->_data[$name];
        }
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __unset($name)
    {
        if (isset($this->_data[$name]))
        {
            unset($this->_data[$name]);
        }
    }

    public function __wakeup()
    {
        $this->_connected = true;
    }
    
	private function fromCamelCase($string)
	{
		$string = preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $string);
		return $string;
	}
	
	private function toCamelCase($string)
	{
      	$string[0] = strtoupper($string[0]);	    
    	$function = create_function('$c', 'return strtoupper($c[1]);');
    	return preg_replace_callback('/_([a-z])/', $function, $string);	    
	}
 }