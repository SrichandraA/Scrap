<?php 
return array(
     'controllers' => array(
         'invokables' => array(
             'Album\Controller\Album' => 'Album\Controller\AlbumController',
         ),
     ),
    
    
    // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'album' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/[/:action][/:id][/:page]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z0-9_-]*',
                         'page'     => '[a-zA-Z0-9_-]*',

                         
                     ),
                     'defaults' => array(
                         'controller' => 'Album\Controller\Album',
                         'action'     => 'index',
                     ),
                 ),
             
             ),
         ),
     ),

    
    
     'view_manager' => array(
         'template_path_stack' => array(
             'album' => __DIR__ . '/../view',
         ),
     ),
 );