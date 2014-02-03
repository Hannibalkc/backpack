<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'UploadFile\Controller\UploadFile' => 'UploadFile\Controller\UploadFileController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'upload-file' => array(
                'type'    => 'segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/upload-file[/:action]',
                    'constraints' => array(
    						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
							'controller' => 'UploadFile\Controller\UploadFile',
							'action'     => 'index',
					),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'UploadFile' => __DIR__ . '/../view',
        ),
    ),
);
