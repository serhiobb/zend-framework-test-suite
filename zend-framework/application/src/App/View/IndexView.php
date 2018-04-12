<?php

class App_IndexView extends Zend_View_Abstract
{

    /**
     * Used to include the view script in a scope that only allows public
     * members.
     *
     * @return void
     */
    protected function _run()
    {
        $data = [$this->title, $this->content];

        //TODO: render()
        $this->_layout($data);
    }

    private function _layout(array $data){
        list($title, $content) = $data;

        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?php print "${title}"; ?></title>
        </head>
        <body>
            HELLO WORLD
            <?php print "${content}"; ?>
        </body>
        </html>
        <?php
    }
    
    private function getList(){

    }
    
    private function _auduoList(array $list)
    {
        
    }

    public function indexAction(array $data)
    {

    }
}