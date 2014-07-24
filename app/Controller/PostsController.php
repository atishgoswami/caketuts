<?php
/**
 * Posts handling
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */

App::uses('AppController', 'Controller');

/**
 * Posts controller class
 *
 * @category Controller
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */
class PostsController extends AppController
{


    /**
     * Helpers Array.
     *
     * @var array
     */
    public $helpers = array(
                       'Html', 'Form',
                      );


    /**
     * Index action showing all posts
     *
     * @return void
     */
    public function index()
    {
        /**
         * Queries all the posts and sets them as $posts
         * for the view to loop through
         */
        $this->set('posts', $this->Post->find('all'));
    }//end index()


    /**
     * View Page of a single post
     *
     * @param integer $id id of the post to be shown
     *
     * @return void
     */
    public function view($id = null)
    {
        //Checks if no id is passed as an argument
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        //Finds the post related to id using the "Magic Find Types"
        //http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#magic-find-types
        $post = $this->Post->findById($id);

        //Check if the id helped return a post or not
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        //Set the post related array in view as $post
        $this->set('post', $post);
    }//end view()


    /**
     * Add action to create a post
     *
     * @return void
     */
    public function add()
    {
        //Checks if the request type is post
        if ($this->request->is('post')) {
            //Addes a extra data key which will help associate the post with
            //a user (the logged in user)
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');

            //Sets the current model primary key to null
            $this->Post->create();
            //Calls the model save method
            if ($this->Post->save($this->request->data)) {
                //Sets a flash message to be shown to the user after
                //successfully saving the data
                $this->Session->setFlash(__('Your post has been saved.'));
                //Redirects user to the posts/index action
                return $this->redirect(array('action' => 'index'));
            }
            //Else will show error message if the post save was not successful
            $this->Session->setFlash(__('Unable to add your post.'));
        }
    }//end add()


    /**
     * Edit Action for the post
     *
     * @param integer $id post record id to be edited
     *
     * @return void
     */
    public function edit($id = null)
    {
        //Checks if no id is passed as an argument
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        //Finds the post related to id using the "Magic Find Types"
        //http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#magic-find-types
        $post = $this->Post->findById($id);

        //Check if the id helped return a post or not
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        //Check if the request is post or a put (for old and new browsers)
        if ($this->request->is(array('post', 'put'))) {
            //Sets an id of the post to the editted
            //This will cause the model save to run an
            //update on the record
            $this->Post->id = $id;

            //Calls the method save method and which in turn check if primary key
            //of the model is set
            if ($this->Post->save($this->request->data)) {
                //Sets a flash message to be shown to the user after
                //successfully updating the data
                $this->Session->setFlash(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            //Else will show error message if the post save was not successful
            $this->Session->setFlash(__('Unable to update your post.'));
        }
        //check if the data to the request is not set or null
        if (!$this->request->data) {
            //If true then sets the data fetched from the id of the
            //post into the $this->request->data which helps in prefilled
            //form generation
            $this->request->data = $post;
        }
    }//end edit()


    /**
     * Delete action for the post record
     *
     * @param integer $id id of the post to be deleted
     *
     * @return void
     */
    public function delete($id)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Post->delete($id)) {
            $this->Session->setFlash(
                __('The post with id: %s has been deleted.', h($id))
            );
            return $this->redirect(array('action' => 'index'));
        }
    }//end delete()


    /**
     * isAuthorized callback for the PostController
     *
     * @param array $user user details present in the session
     *
     * @return boolean
     */
    public function isAuthorized($user)
    {
        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }

        // The owner of a post can edit and delete it
        if (in_array($this->action, array('edit', 'delete'))) {
            $postId = (int) $this->request->params['pass'][0];
            if ($this->Post->isOwnedBy($postId, $user['id'])) {
                return true;
            }
        }

        //Calls the AppController isAuthorized()
        return parent::isAuthorized($user);
    }//end isAuthorized()


}//end class