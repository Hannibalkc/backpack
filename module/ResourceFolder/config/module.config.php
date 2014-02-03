<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ResourceFolder\Controller\ResourceFolder' => 'ResourceFolder\Controller\ResourceFolderController',
        ),
    ),
//     'router' => array(
//         'routes' => array(
//             'resource-folder' => array(
//                 'type'    => 'Literal',
//                 'options' => array(
//                     // Change this to something specific to your module
//                     'route'    => '/resourceFolder',
//                     'defaults' => array(
//                         // Change this value to reflect the namespace in which
//                         // the controllers for your module are found
//                         '__NAMESPACE__' => 'ResourceFolder\Controller',
//                         'controller'    => 'ResourceFolder',
//                         'action'        => 'index',
//                     ),
//                 ),
//                 'may_terminate' => true,
//                 'child_routes' => array(
//                     // This route is a sane default when developing a module;
//                     // as you solidify the routes for your module, however,
//                     // you may want to remove it and replace it with more
//                     // specific routes.
//                     'default' => array(
//                         'type'    => 'Segment',
//                         'options' => array(
//                             'route'    => '/[:controller[/:action]]',
//                             'constraints' => array(
//                                 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                 'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                             ),
//                             'defaults' => array(
//                             ),
//                         ),
//                     ),
//                 ),
//             ),
//         ),
//     ),

    // The following section is new` and should be added to your file
    'router' => array(
    		'routes' => array(
    				'folder' => array(
    						'type' => 'Segment',
    						'options' => array(
    								'route'    => '/folder[/][:action][/:id]',
    								'constraints' => array(
    										'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
    										'id'     => '[0-9]+',
    								        'user_id'     => '[0-9]+',
    								),
    								'defaults' => array(
    										'controller' => 'ResourceFolder\Controller\ResourceFolder',
    										'action'     => 'index',
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
//     'view_manager' => array(
//         'template_path_stack' => array(
//             'ResourceFolder' => __DIR__ . '/../view',
//         ),
//     ),
);
