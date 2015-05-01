<?php

class Model_Db_Table_Base extends Zend_Db_Table_Abstract
{
    public function __call($method, $args)
    {
        if (preg_match('/(get(One|All)By)(.+)/', $method, $matches))
        {
            if (count($matches) != 4)
                throw new Exception('getOneBy/getAllBy requires at least one field');

            $count = $matches[2];
            $fields = explode('And', $matches[3]);
            
            if (count($fields) != count($args))
                throw new Exception('Number of fields doesn\'t match the number of arguments');
            
            $filter = new Zend_Filter_Word_CamelCaseToUnderscore();
            $where = array();            
            
            foreach ($fields as $key => $fieldName)
            {
                $fieldName = strtolower($filter->filter($fieldName));
                $fieldValue = $args[$key];
                
                if ($fieldValue === null)
                    $where[] = sprintf('%s IS NULL', $fieldName);
                else                    
                {
                    if (is_array($fieldValue))
                        $where[sprintf('%s IN (?)', $fieldName)] = $fieldValue;
                    else
                        $where[sprintf('%s = ?', $fieldName)] = $fieldValue;
                }
            }

            $result = null;
            
            switch ($count)
            {
            	case 'One':
					$result = $this->fetchRow($where);
                    break;
                    
            	case 'All':
            	    $result = $this->fetchAll($where);
            	    break;
            }
                        
            return $result;
        }
    }
    
	public function createRowset(array $data = array())
	{
		$rowsetClass = $this->getRowsetClass();
		
		$config = array(
			'table' => $this,
			'rowClass' => $this->getRowClass(),
			'data' => $data
		);
		
		$rowset = new $rowsetClass($config);
			
		return $rowset;
	}
	
    /**
     * Replace default column names with custom expressions.
     * 
     * $columnName => $dbExpression
     */
    public function getColumnsAsArray($replacements = null)
    {
        $columns = $this->info(self::COLS);
        
        if (is_array($replacements))
        {
            foreach ($replacements as $column => $expression)
            {
                $key = array_search($column, $columns);
                
                if ($key !== false)
                    unset($columns[$key]);

                $columns[$column] = $expression;
            }
        }
        
        return $columns;
    }
 }