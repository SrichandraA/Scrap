<?php
namespace Album\Form;

 use Zend\Form\Form;

 class UserForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('album');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'username',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Username',
             ),
         ));
         $this->add(array(
             'name' => 'password',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Password',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }
