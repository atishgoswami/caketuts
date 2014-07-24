<?php

/**
 * Post model handling
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
 * Post model class
 *
 * @category Model
 * @package  Caketuts
 * @author   Atish Goswami <atishgoswami@gmail.com.com>
 * @license  http://caketuts.com Public
 * @link     http://caketuts.com
 */

class Post extends AppModel
{
    /**
     * Validation Array of Post Model.
     *
     * @var array
     */
    public $validate = array(
                        'title' => array('rule' => 'notEmpty'),
                        'body'  => array('rule' => 'notEmpty')
                       );
}//end class