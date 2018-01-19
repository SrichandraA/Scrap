<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Album\Controller\Album' => 'Album\Controller\AlbumController',
             'Album\Controller\Auth' => 'Album\Controller\AuthController'
         ),
     ),

     'router' => array(
             'routes' => array(

                 'login' => array(
                     'type'    => 'Literal',
                     'options' => array(
                         'route'    => '/auth',
                         'defaults' => array(
                             '__NAMESPACE__' => 'Album\Controller',
                             'controller'    => 'Auth',
                             'action'        => 'login',
                         ),
                     ),
                     'may_terminate' => true,
                     'child_routes' => array(
                         'process' => array(
                             'type'    => 'Segment',
                             'options' => array(
                                 'route'    => '/[:action]',
                                 'constraints' => array(
                                     'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                     'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                 ),
                                 'defaults' => array(
                                 ),
                             ),
                         ),
                     ),
                 ),
                 'album' => array(
                     'type'    => 'segment',
                     'options' => array(
                         'route'    => '/[/:action][/:id]',
                         'constraints' => array(
                             'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                             'id'     => '[0-9_-]+',


                         ),
                         'defaults' => array(
                             'controller' => 'Album\Controller\Album',
                             'action' => 'index',
                         ),
                     ),

                 ),


             ),
         ),

     'view_manager' => array(
         'template_path_stack' => array(
             'album' => __DIR__ . '/../view',
         ),
         'strategies' => array(
    'ViewJsonStrategy',
),
     ),
 );
