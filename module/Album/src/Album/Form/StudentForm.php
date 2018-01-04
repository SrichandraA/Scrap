<?php
namespace Album\Form;

 use Zend\Form\Form;

 class StudentForm extends Form
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
             'name' => 'name',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Name',
             ),
         ));
       $this->add(array(
             'name' => 'classs',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Class',
             ),
         ));
            $this->add(array(
             'name' => 'email',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Email',
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