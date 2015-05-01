<?php

class Form_Login extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        $this
            ->setName('login')
            ->setMethod('post')
            ->setDecorators(array(
                'FormElements',
                array(
                    'ViewScript', 
                    array(
                        'viewScript' => 'Forms/Login.phtml', 
                        'placement' => false
                    )
                ), 
                'Form'
            ))
        ;

        $fieldUsername = new Zend_Form_Element_Text('username');
        $fieldUsername
			->setRequired()
			->setAttrib('class', 'form-control input-lg')
            ->setAttrib('placeholder', 'Username')
			->setDecorators(array('ViewHelper', 'Errors'))
			->addValidator('NotEmpty', true, array(
				'messages' => array(
					Zend_Validate_NotEmpty::IS_EMPTY => 'Username is required'
				)
			))
        ;

        $fieldPassword = new Zend_Form_Element_Password('password');
        $fieldPassword
			->setRequired()
			->setAttrib('class', 'form-control input-lg')
            ->setAttrib('placeholder', 'Password')
			->setDecorators(array('ViewHelper', 'Errors'))
			->addValidator('NotEmpty', true, array(
				'messages' => array(
					Zend_Validate_NotEmpty::IS_EMPTY => 'Password is required'
				)
			))
        ;

        $buttonLogin = new Zend_Form_Element_Button('login');
        $buttonLogin
            ->setLabel('Login')
            ->setAttrib('class', 'btn btn-danger btn-block btn-lg')
            ->setAttrib('type', 'submit')
			->setDecorators(array('ViewHelper'))
        ;

        $this->addElements(array(
            $fieldUsername, $fieldPassword,
            $buttonLogin
        ));		
    }
}