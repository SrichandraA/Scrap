<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'StudentRest\Controller\StudentRest' => 'StudentRest\Controller\StudentRestController',
        ),
    ),

    // The following section is new` and should be added to your file
    'router' => array(
        'routes' => array(
            'student-rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/student-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'StudentRest\Controller\StudentRest',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array( //Add this config
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
