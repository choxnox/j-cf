<?php

class Library_Registry extends Zend_Registry
{
    /**
     * @return Model_Db_Entity_User
     */
    public static function loggedUser()
    {
        return Zend_Registry::get('loggedUser');
    }
}