<?php

namespace app\models;

class Users extends \lithium\data\Model {
	/**
     * @see lithium\data\Model::$_meta
     */
    protected $_meta = [
        'email' => 'string',
        'username' => 'string',
        'password' => 'string'
    ];
}

// Users::applyFilter('save', $GLOBALS['hashPasswordFunction']);