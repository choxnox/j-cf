<?php

class View_Helper_Messenger extends Zend_View_Helper_Abstract
{
    public function messenger($options)
    {
        $defaults = array(
            'closable' => false,
            'color' => 'blue',      // ['red', 'orange', 'blue', 'green']
            'gritter' => array(
                'timeout' => 5
            ),
            'icon' => null,
            'message' => null,
            'nextRequest' => false,
            'strong' => false,
            'throw' => false,
            'type' => 'inline'      // ['gritter', 'inline', 'modal']
        );
        
        $options += $defaults;

        if ($options['icon'])
            $options['icon'] = sprintf('glyphicon glyphicon-%s', $options['icon']);
        
        $alertColors = array(
            'blue' => 'blue',
            'green' => 'green',
            'orange' => 'orange',
            'red' => 'danger'
        );
        
        $options['color'] = $alertColors[$options['color']];
        
        if (!$this->view)
            $this->view = Zend_Layout::getMvcInstance()->getView();
        
       	$result = $this->view->partial('Partials/messenger.phtml', array('options' => $options));
        
        if ($options['nextRequest'])
        {
            $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');  
            $flashMessenger->direct(array(
                'html' => $result,
                'options' => $options
            ), 'messenger');
        }
        
        return $result;
    }
}