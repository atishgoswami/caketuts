<?php

/**
 * Application handling
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package  Shdlr
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://shdlr.com Private
 * @link     http://shdlr.com
 */
App::uses('Controller', 'Controller');
/**
 * Application controller class
 *
 * @category Controller
 * @package  Shdlr
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://shdlr.com Private
 * @link     http://shdlr.com
 *
 */
class AppController extends Controller
{

    /**
     * Components and Setiings.
     *
     * @var array
     */
    public $components = array(
                          'Session' => array(),
                          'Auth'    => array(
                                        'loginRedirect'  => array(
                                                             'controller' => 'posts',
                                                             'action'     => 'index',
                                                            ),
                                        'logoutRedirect' => array(
                                                             'controller' => 'pages',
                                                             'action'     => 'display','home'
                                                            )
                                       )
                         );


    /**
     * beforeFilter callbacks
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allow('index', 'view');
    }//end beforeFilter()


}//end class
