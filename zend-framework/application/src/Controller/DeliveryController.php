<?php

class DeliveryController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->view->word = 'World';
    }

    public function statusAction()
    {

    }
}