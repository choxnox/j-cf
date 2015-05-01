<?php

class Library_Controller_Action extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $messages = $this->_helper->flashMessenger->getMessages('messenger');

        if (count($messages))
        {
            $message = current($messages);

            $options = $message['options'];
            $html = $message['html'];

            if ($options['type'] == 'gritter')
                $this->view->placeholder('messenger')->append($html);
            else
                $this->view->messenger = $html;
        }
    }

    /**
     * Display messages in views
     *
     * $options = array(
     *     'closable' => boolean,       [true]
     *     'color' => string,           ['red', 'orange', 'blue', 'green']
     *     'gritter' => array(
     *         'timeout' => int         [5]
     *     ),
     *     'message' => string,         ['']
     *     'nextRequest' => boolean,    [false]
     *     'throw' => boolean,          [false]
     *     'type' => array()            ['gritter', 'modal']
     * )
     *
     * @param   array   $options
     */
    public function messenger($options)
    {
        $defaults = array(
            'closable' => false,
            'color' => 'blue',      // ['red', 'orange', 'blue', 'green']
            'gritter' => array(
                'timeout' => 5
            ),
            'icon' => null,
            'message' => 'TO-DO',
            'nextRequest' => false,
            'strong' => false,
            'throw' => false,
            'type' => 'inline'      // ['gritter', 'inline', 'modal']
        );

        $options += $defaults;

        if (!is_array($options))
            return false;

        $helperMessenger = new View_Helper_Messenger($options);
        $messenger = $helperMessenger->setView($this->view)->messenger($options);

        if (!$options['nextRequest'])
        {
            if ($options['throw'])
                echo $messenger;
            else
                $this->view->messenger = $messenger;
        }
    }
	
    public function setTitle($title, $type = 'PREPEND')
    {
        $this->view->headTitle($title, $type);

        return $this;
    }
}