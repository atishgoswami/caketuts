<?php

/**
 * Group model handling
 *
 * PHP version 5.3
 *
 * @category Model
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */

App::uses('AppModel', 'Model');

/**
 * Group model class
 *
 * @category Model
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */

class Group extends AppModel
{

    /**
     * Assigns table to the model.
     *
     * @var boolean|string
     */
    public $useTable = false;

    /**
     * Validation Rules Variable.
     *
     * @var array
     */
    public $validate = array(
                        'alias' => array(
                                    'required' => array(
                                                   'rule'    => array('notEmpty'),
                                                   'message' => 'A group name is required',
                                                  )
                                   ),
                       );


    /**
     * saveGroup method will help save/register a new Group Entry
     *
     * @param array $data array set to save for the model
     *                    array(
     *                     'Group' => array(
     *                                 'id'        => 'has the id of the entry',
     *                                 'alias'     => 'name of alias of group',
     *                                 'parent_id' => 'id of the parent group or Null',
     *                                )
     *                    )
     *
     * @return void
     */
    public function save($data = NULL, $validate = true, $fieldList = array())
    {
        $this->set($data);
        $result = $this->validates();
        if ((bool) $result) {
            $aro        = ClassRegistry::init('Aro');
            $parentId   = (!empty($data['Group']['parent_id'])) ? $data['Group']['parent_id'] : null;
            $groupAlias = $data['Group']['alias'];
            // Build data to save
            $aroData = array(
                        'parent_id'   => $parentId,
                        'foreign_key' => null,
                        'alias'       => $groupAlias,
                        'model'       => 'Group',
                       );
            // Prepare model to save data
            if (!empty($data['Group']['id'])) {
                $aroData['id'] = $data['Group']['id'];
            } else {
                $aro->create();
                $aro->id = 0;
            }
            // Save needed data
            $aro->save($aroData);
        }
        return $result;
    }//end save()


    /**
     * Exists checks for the records
     *
     * @param integer $id id of the record
     *
     * @return boolean
     */
    public function exists($id = null)
    {
        $aro = ClassRegistry::init('Aro');
        return $aro->exists($id);
    }//end exists()


    /**
     * formatPermissionSet will format the data
     * to show on proper table
     *
     * @param array $groupRelatedInfo array for the group and related info
     *
     * @return array
     */
    public function formatPermissionSet($groupRelatedInfo)
    {
        $result = $this->__getPermissionForAro($groupRelatedInfo);
        return $this->__formatRawAroView($result);
    }//end formatPermissionSet()


    /**
     * Gets all the permission for the the given ARO entry
     *
     * @param array $groupRelatedInfo ARO table record
     *
     * @return array
     */
    private function __getPermissionForAro($groupRelatedInfo)
    {
        //Get the ARO Model Instance
        $aro = ClassRegistry::init('Aro');
        //Gather the node structure of the Aro record
        //like the inherited ARO's
        $aroDetails = $aro->node($groupRelatedInfo['Aro']['alias']);
        //Extract all the ids of the ARO records obtained
        //in the node search
        $aroIds = Hash::extract($aroDetails, '{n}.Aro.id');

        //Generated condition for the permission search to get all the
        //permission which are assigned to the ARO and its parents if any
        $conditions = array('Permission.aro_id' => $aroIds);
        //Keep the ACO lft order descending so the tree structure is well formated
        $order = array('Aco.lft' => "desc");
        //Get all permission for the ARO
        $permissions = $aro->Permission->find('all', compact('conditions', 'order'));

        return compact('aroDetails', 'permissions');
    }//end __getPermissionForAro()


    /**
     * Formats the data for the view page
     *
     * @param array $result [description]
     *
     * @return array
     */
    private function __formatRawAroView($result)
    {
        //Initialize emoty arrays for aro and permissions
        //as well as loop counter
        $aro = $permissions = array(); $count = 1;

        //Iterates the ARO related node info
        foreach ($result['aroDetails'] as $data) {
            //Check if this is the current ARO
            if ($count == 1) {
                $aro['details'] = $data['Aro'];
            } else {
                //Check if the parents key has already been
                //created.
                if (empty($aro['parents'])) {
                    $aro['parents'] = array();
                }
                //add the id and alias of the parent ARO
                $aro['parents'] += array($data['Aro']['id'] => $data['Aro']['alias']);
            }
            //Increment counter
            $count++;
        }

        //Iterate through the permissions array and
        //format the data accordingly
        foreach ($result['permissions'] as $key => $perm) {
            $denied                       = array_search(-1, $perm['Permission']);
            $permissions[$key]['granted'] = !$denied;
            $permissions[$key]['Aro']     = $perm['Aro'];
            $permissions[$key]['Aco']     = $perm['Aco'];
        }

        return compact('aro', 'permissions');
    }//end __formatRawAroView()


    /**
     * Method used to build needed tables for ACL
     *
     * @return void
     */
    public function checkBuildAclTables()
    {
        // Get the database connect object
        $db = ConnectionManager::getDataSource('default');
        //Don't Cache the Sources
        $db->cacheSources = false;

        // Get tables list
        $tables = $db->listSources();

        // Initialize ACL related tables list
        $aclTables = array(
                      'acos', 'aros_acos', 'aros',
                     );

        //Gets the schema default for acl related tables
        $schemaFileContent = explode(';', file_get_contents(APP. 'Config'. DS .'Schema' . DS . 'db_acl.sql'));

        // Loop through SQL file which contains ACL related tables
        foreach ($schemaFileContent as $query) {
            foreach ($aclTables as $key => $table) {
                if (!in_array($table, $tables)
                    && false !== strpos($query, 'CREATE TABLE ' . $table . ' ')
                ) {
                    $db->query(
                        trim(
                            str_replace(
                                $table,
                                $db->config['prefix'] . $table,
                                $query
                            )
                        )
                    );
                }
            }
        }
    }//end checkBuildAclTables()


    /**
     * Action method used to build ACOs entires
     *
     * @return array
     */
    public function buildAcos()
    {
        $aco = ClassRegistry::init("Aco");
        // Build conditions to get root parent ACO's id
        $rootParent = array('alias' => 'ALL');
        // If needed root ACO already exists then get its id otherwise create it and get its id
        if (!$rootParentId = (int)$aco->field('Aco.id', $rootParent)) {
            $aco->create();
            $aco->save($rootParent);
            $rootParentId = (int)$aco->getLastInsertID();
        }
        // Initialize variable used to store list of ACOs
        $acos = array($rootParentId => 'ALL');
        // Get list of methods for App controller
        $appMethods = get_class_methods('AppController');
        // Get list of all controllers and then sort it alphabetically
        $controllerList = App::objects('Controller');
        sort($controllerList);
        // Loop through list of controllers to get each controller's methods
        foreach ($controllerList as $controller) {
            $controller = substr(
                $controller, 0,
                (strlen($controller) - strlen("Controller"))
            );
            // We don't need methods for application controller
            if ('App' == $controller) continue;
            // Get ACO id for controller and build ACOs data as per our need
            $controllerAcoId        = $this->__getAcoId($controller, $rootParentId);
            $acos[$controllerAcoId] = $controller;
            // Include controller's class file
            App::import('Controller', $controller);
            // Get controller's methods and sort them alphabetically
            $controllerMethods = array_diff(
                get_class_methods($controller . 'Controller'),
                $appMethods
            );
            sort($controllerMethods);
            // Loop through controller's method to store each public method as ACO
            foreach ($controllerMethods as $controllerMethod) {
                // Don't add non public method
                if ('_' != substr($controllerMethod, 0, 1)) {
                    $acos[
                        $this->__getAcoId(
                            //$controller."::".$controllerMethod,
                            $controllerMethod,
                            $controllerAcoId
                        )
                    ] = $controller . '/' . $controllerMethod;
                }
            }
        }
        // Delete not needed ACOs from database table
        $aco->deleteAll('Aco.id NOT IN (' . implode(', ', array_keys($acos)) . ')');
        // Return built ACOs
        return $acos;
    }//end buildAcos()


    /**
     * Method used to get ACO id for alias (if not found then insert record for alias)
     *
     * @param string  $alias    Alias for ACO
     * @param integer $parentId Id for ACO's parent
     *
     * @return integer
     */
    private function __getAcoId($alias, $parentId)
    {
        $aco = ClassRegistry::init("Aco");
        // Build conditions to get ACO id for given alias and parent id
        $acoConditions = array(
                          'alias'     => $alias,
                          'parent_id' => $parentId,
                         );

        // Check if id of ACO alias exist if not then create a return id
        if (!$acoId = (int)$aco->field('Aco.id', $acoConditions)) {
            // Save needed ACO
            $aco->create();
            $aco->save($acoConditions);
            $acoId = (int)$aco->getLastInsertID();
        }
        // Return ACO id
        return $acoId;
    }//end __getAcoId()


}//end class