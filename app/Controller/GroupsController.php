<?php
/**
 * Groups handling
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
 * Groups controller class
 *
 * @category Controller
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */
class GroupsController extends AppController
{


    /**
     * Index action for groups controller
     * Shows list of the all the groups
     *
     * @return void
     */
    public function index()
    {
        //Initial variables assignments
        $groups = $groupList = array();
        //Added conditions to fetch only groups
        $conditions = array('model' => 'Group');
        //Set recursive to -1 so that only the base model is queried
        $recursive = -1;
        //Finds all the Groups entry of the ARO Model/table
        $groups = $this->Acl->Aro->find('all', compact('conditions', 'recursive'));
        //Extract the groups entries as array("Aro.id" => "Aro.alias")
        if ((bool) $groups !== false) {
            $groupList = Hash::combine($groups, '{n}.Aro.id', '{n}.Aro.alias');
        }
        //Sets the data for the view Groups/index.ctp
        $this->set(compact('groups', 'groupList'));
    }//end index()


    /**
     * Add action for groups controller
     * Helps create a new group
     *
     * @return void
     */
    public function add()
    {
        //Checks if the request method is POST
        if ($this->request->is('post')) {
            //Calls the Group Model Save Model
            if ($this->Group->saveGroup($this->request->data)) {
                $this->Session->setFlash(__('Group has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
        }
        $this->__getGroupList();
    }//end add()


    /**
     * Edit action for groups controller
     * Helps create a edit group entries
     *
     * @param integer $id id of the group
     *
     * @return void
     */
    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid group'));
        }

        //Finds the post related to id using the "Magic Find Types"
        //http://book.cakephp.org/2.0/en/models/retrieving-your-data.html#magic-find-types
        $group = $this->Acl->Aro->findById($id);

        //Check if the id helped return a post or not
        if (!$group) {
            throw new NotFoundException(__('Invalid group'));
        }

        //Checks if the request method is POST
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Group']['id'] = $id;
            //Calls the Group Model Save Model
            if ($this->Group->saveGroup($this->request->data)) {
                $this->Session->setFlash(__('Group has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
        }
        if (!(bool)$this->request->data) {
            foreach ($group['Aro'] as $field => $value) {
                $this->request->data['Group'][$field] = $value;
            }
        }
        $this->__getGroupList($id);
    }//end edit()


    /**
     * View Page of a single Group
     *
     * @param integer $id id of the group to be shown
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
        $group = $this->Acl->Aro->findById($id);

        $this->Group->formatPermissionSet($group);

        //Check if the id helped return a group or not
        if (!$group) {
            throw new NotFoundException(__('Invalid group'));
        }

        //Set the group related array in view as $group
        $this->set('group', $group);
    }//end view()


    /**
     * Delete action for the group record
     *
     * @param integer $id id of the group to be deleted
     *
     * @return void
     */
    public function delete($id)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Acl->Aro->delete($id)) {
            $this->Session->setFlash(
                __('The Group with id: %s has been deleted.', h($id))
            );
            return $this->redirect(array('action' => 'index'));
        }
    }//end delete()


    /**
     * __getGroupList is a common method used to obtain the list
     * of Groups present in the ARO tables. Also has so options to set
     * the data as view variable or just return the fetched array
     *
     * @param boolean $exclude       excludes the record with a particular id
     * @param boolean $returnList    flag to decide if only to return the fetched list
     * @param string  $setView       name of the view variable
     * @param boolean $setNullparent flag to decide if merge no parent in the group list
     *                               useful for select dropdown
     *
     * @return array
     */
    private function __getGroupList(
        $exclude = false,
        $returnList = false,
        $setView = 'parents',
        $setNullparent = true
    ) {
        //Initial assignment of variables
        $$setView = ($setNullparent) ? array(0 => 'No Parent') : array();
        //Added conditions to fetch only groups
        $conditions = array('model' => 'Group');
        if ($exclude) {
            $conditions['id !='] = $exclude;
        }
        //Added conditions to fetch only groups
        $fields = array(
                   'id', 'alias',
                  );
        //Set recursive to -1 so that only the base model is queried
        $recursive = -1;
        //Finds all the Groups entry of the ARO Model/table
        $results = $this->Acl->Aro->find('list', compact('conditions', 'recursive', 'fields'));
        if ((bool)$results !== false) {
            if ($setNullparent) {
                $$setView = am($$setView, $results);
            }
        }

        if ($returnList) {
            return $$setView;
        }
        //Sets the data for the view Groups/add.ctp
        $this->set(compact($setView));
    }//end __getGroupList()


}//end class