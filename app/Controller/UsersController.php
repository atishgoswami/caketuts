<?php
/**
 * Users handling
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */

App::uses('AppController', 'Controller');

/**
 * Users controller class
 *
 * @category Controller
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */
class UsersController extends AppController
{


    /**
     * beforeFilter callback
     *
     * @return void
     */
    public function beforeFilter()
    {
        /**
         * Calls the beforeFilter() action of the parent
         * class i.e. AppController
         */
        parent::beforeFilter();

        // Allow users to register and logout.
        $this->Auth->allow('add', 'logout');
    }//end beforeFilter()


    /**
     * Shows user listings
     *
     * @return void
     */
    public function index()
    {
        /**
         * Sets the recursion level of the
         * of the query for the related model
         */
        $this->User->recursive = 0;
        /**
         * Sets a view variable "users" with the
         * data iterated by the pagination component
         */
        $this->set('users', $this->paginate());
    }//end index()


    /**
     * View action for a single user
     *
     * @param integer $id user id
     *
     * @return void
     */
    public function view($id = null)
    {

        //Set the model primaryKey to $id value
        $this->User->id = $id;
        //check if any such record exists
        if (!$this->User->exists()) {
            // Throws an exception and stops execution of code
            throw new NotFoundException(__('Invalid user'));
        }
        //sets the data of the user obtained from the id passed
        //in a view variable "user"
        $this->set('user', $this->User->read(null, $id));
    }//end view()


    /**
     * Add action which will register the user
     *
     * @return void
     */
    public function add()
    {
        //Check if it is a post request
        if ($this->request->is('post')) {
            //Sets the primary key of the model to null
            //so that the submitted add can be added as
            //new entry in the table
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                //Success message to be show to the user
                $this->Session->setFlash(__('The user has been saved'));
                //redirects to index of the current controller
                return $this->redirect(array('action' => 'index'));
            }
            //Error message to be show to the user
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    }//end add()


    /**
     * Edit action which will edit user details
     *
     * @param integer $id user id
     *
     * @return void
     */
    public function edit($id = null)
    {
        //Set the model primaryKey to $id value
        $this->User->id = $id;
        //check if any such record exists
        if (!$this->User->exists()) {
            // Throws an exception and stops execution of code
            throw new NotFoundException(__('Invalid user'));
        }
        //Checks either the request is post or a put request
        //"put" request check is helpful when building a rest api
        if ($this->request->is('post') || $this->request->is('put')) {
            // Edits the data of the record whose primary key is
            // set in the model [$this->User->id = $id]
            if ($this->User->save($this->request->data)) {
                //Success message to be show to the user
                $this->Session->setFlash(__('The user has been saved'));
                //redirects to index of the current controller
                return $this->redirect(array('action' => 'index'));
            }
            //Error message to be show to the user
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            //If not a get request the fetch data for table for the id
            //passed in the params
            $this->request->data = $this->User->read(null, $id);
            //unset password
            unset($this->request->data['User']['password']);
        }
    }//end edit()


    /**
     * Delete action will delete the user record
     *
     * @param integer $id user id to be deleted
     *
     * @return void
     */
    public function delete($id = null)
    {
        //Will allow a post request to proceed
        $this->request->onlyAllow('post');
        //Primary key of the model is set to the $id
        //passed in the action
        $this->User->id = $id;
        //Checks if the record exists
        if (!$this->User->exists()) {
            // throws execption if record on present
            // and stops execution
            throw new NotFoundException(__('Invalid user'));
        }
        // Will try to delete the records and return a boolean value
        if ($this->User->delete()) {
            //Sets success message for the user
            $this->Session->setFlash(__('User deleted'));
            //redirects to index action of the current controller
            return $this->redirect(array('action' => 'index'));
        }
        //Sets error message for the user
        $this->Session->setFlash(__('User was not deleted'));
        //redirects to index action of the current controller
        return $this->redirect(array('action' => 'index'));

    }//end delete()


    /**
     * login action to log the user in
     *
     * @return Void
     */
    public function login()
    {
        //Check if it is a post request
        if ($this->request->is('post')) {
            //Check if the posted credentials validate login
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            }
            //Set Session flash message
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }//end login()


    /**
     * Logout action to log the user out
     *
     * @return Void
     */
    public function logout()
    {
        //$this->Auth->logout() will destroy the user session and returns
        //redirect path which $this->redirect() to.
        return $this->redirect($this->Auth->logout());
    }//end logout()


}//end class