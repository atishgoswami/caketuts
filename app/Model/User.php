<?php

/**
 * User model handling
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
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * User model class
 *
 * @category Model
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */

class User extends AppModel
{

    /**
     * Validation Rules Variable.
     *
     * @var array
     */
    public $validate = array(
                        'username' => array(
                                       'required' => array(
                                                      'rule'    => array('notEmpty'),
                                                      'message' => 'A username is required',
                                                     )
                                      ),
                        'password' => array(
                                       'required' => array(
                                                      'rule'    => array('notEmpty'),
                                                      'message' => 'A password is required',
                                                     )
                                      ),
                        'role'     => array(
                                       'valid' => array(
                                                   'rule'       => array(
                                                                    'inList',
                                                                    array(
                                                                     'admin', 'author',
                                                                    ),
                                                                   ),
                                                   'message'    => 'Please enter a valid role',
                                                   'allowEmpty' => false,
                                                  )
                                      )
                       );


    /**
     * beforeSave callback for the user model
     *
     * @param array $options options for the callback function
     *
     * @return boolean
     */
    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher                       = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }//end beforeSave()


}//end class