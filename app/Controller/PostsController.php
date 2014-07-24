<?php
/**
 * Posts handling
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
 * Posts controller class
 *
 * @category Controller
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
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
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);

        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    }//end view()


    /**
     * Add action to create a post
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
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
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your post.'));
        }

        if (!$this->request->data) {
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


}//end class