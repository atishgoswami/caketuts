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
    public function saveGroup($data)
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
    }//end saveGroup()


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
     * @param array  $groupRelatedInfo array for the group and related info
     *
     * @param string $format           can be single or multiple
     *
     * @return array
     */
    public function formatPermissionSet($groupRelatedInfo, $format = "single")
    {
        debug(ClassRegistry::init('Aco')->generateTreeList(null, "{n}.Aco.id", "{n}.Aco.alias"));exit;
    }//end formatPermissionSet()


}//end class