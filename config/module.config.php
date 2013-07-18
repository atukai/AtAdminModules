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
                                        'action'     => 'modules',
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

    'service_manager' => array(
        'invokables' => array (
            'at_admin_modules_service' => 'AtAdminModules\Service\Modules'
        )
    ),

    'navigation' => array(
        'admin' => array(
            'system' => array(
                'pages' => array(
                    'modules' => array(
                        'label' => 'Modules',
                        'route' => 'zfcadmin/system/modules',
                    )
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);