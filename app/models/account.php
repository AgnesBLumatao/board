<?php
class Account extends AppModel
{
public $validation = array(
'username' => array(
'length' => array(
'validate_between', 1, 25,
),
),
'password' => array(
'length' => array(
'validate_between', 1, 25,
),
),
);

}