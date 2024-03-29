<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
//     'router' => array(
//         'routes' => array(
//             'user' => array(
//                 'type'    => 'Segment',
//                 'options' => array(
//                     // Change this to something specific to your module
//                     'route'    => '/user',
//                     'defaults' => array(
//                         // Change this value to reflect the namespace in which
//                         // the controllers for your module are found
//                         '__NAMESPACE__' => 'User\Controller',
//                         'controller'    => 'User',
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
    				'user' => array(
    						'type' => 'Segment',
//     						'options' => array(
//     								'route' => '/user[/:id]',
//     								'constraints' => array(
//     										'id' => '[0-9]+',
//     								),
//     								'defaults' => array(
//     										'controller' => 'User\Controller\User',
//     								),
//     						),
    				    'options' => array(
    				    		'route'    => '/user[/][:action][/:id]',
    				    		'constraints' => array(
    				    				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
    				    				'id'     => '[0-9]+',
    				    		),
    				    		'defaults' => array(
    				    				'controller' => 'User\Controller\User',
    				    				'action'     => 'index',
    				    		),
    				    ),
    				),
//     		    'test' => array(
//     		    		'type' => 'Segment',
//     		    		'options' => array(
//     		    				'route' => '/test[/:id]',
//     		    				'constraints' => array(
//     		    						'id' => '[0-9]+',
//     		    				),
//     		    				'defaults' => array(
//     		    						'controller' => 'User\Controller\User',
//     		    				),
//     		    		),
//     		    ),
    		),
    ),
    'view_manager' => array( //Add this config
    		'strategies' => array(
    				'ViewJsonStrategy',
    		),
    ),
//     'view_manager' => array(
//         'template_path_stack' => array(
//             'User' => __DIR__ . '/../view',
//         ),
//     ),
);
