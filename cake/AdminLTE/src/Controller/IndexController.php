<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Index Controller
 *
 * @property \App\Model\Table\IndexTable $Index
 */
class IndexController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->set('username',$this->Auth->user('username'));
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    }

}
