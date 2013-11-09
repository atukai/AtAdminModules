<?php

namespace AtAdminModules\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ModulesController extends AbstractActionController
{
    public function indexAction()
    {
        $sl = $this->getServiceLocator();

        $moduleVersions = array();
        foreach ($sl->get('ModuleManager')->getLoadedModules() as $name => $module) {
            $moduleVersions[$name] = $sl->get('atadmin_modules_service')->getModuleInfo($name);
        }

        return array(
            'modules' => $moduleVersions
        );
    }
}
