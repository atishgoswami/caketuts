<?php

/**
 * Application handling
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package  Shdlr
 * @author   Atish Goswami <atishgoswami@gmail.com>
 * @license  http://shdlr.com Private
 * @link     http://shdlr.com
 */
App::uses('Controller', 'Controller');
/**
 * Application controller class
 *
 * @category Controller
 * @package  Shdlr
 * @author   Atish Goswami <atishgoswami@gmail.com>
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
                                                            ),
                                        'authorize'      => array('Controller'), // Added this line
                                       ),
                          'Acl'     => array(),
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


    /**
     * isAuthorized Callback function
     * for the AppController
     *
     * @param array $user user details present in the session
     *
     * @return boolean
     */
    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }//end isAuthorized()


}//end class
