<?php

class Model_Auth
{
    public function getAuthAdapter()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
         
        $authAdapter
            ->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('SHA2(CONCAT(password_salt, ?), 256)')
        ;
        
        return $authAdapter;
    }

    public function getAuth()
    {
        return Zend_Auth::getInstance();
    }
}