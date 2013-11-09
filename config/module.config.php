<?php

return array(

   'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'system' => array(
                        'child_routes' => array(
                            'modules' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route'    => '/modules',
                                    'defaults' => array(
                                        'controller' => 'AtAdminModules\Controller\Modules',
                                        'action'     => 'index',
                                    ),
                                )
                            ),
                        )
                    ),
                )
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'AtAdminModules\Controller\Modules' => 'AtAdminModules\Controller\ModulesController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);