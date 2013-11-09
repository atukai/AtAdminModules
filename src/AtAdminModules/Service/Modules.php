<?php

namespace AtAdminModules\Service;

use Zend\ModuleManager\ModuleManager;

class Modules
{
    const STATUS_GIT_BRANCH_DIVERGED = 'Your branch has diverged; %d ahead, %d behind';
    const STATUS_GIT_BRANCH_AHEAD    = 'Your branch is %d ahead';
    const STATUS_GIT_BRANCH_BEHIND   = 'Your branch is %d behind';
    const STATUS_GIT_UP_TO_DATE      = 'Up to date';
    const STATUS_GIT_NOT_TRACKED     = 'Module not tracked by git';

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var array
     */
    protected $loadedModules = array();

    /**
     * @param ModuleManager $moduleManager
     */
    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
        $this->loadedModules = $moduleManager->getLoadedModules(true);
    }

    /**
     * @param string $name Module name
     */
    public function getModuleInfo($name)
    {
        $module = $this->loadedModules[$name];

        $moduleInfo = array(
            'localHash'  => 'N/A',
            'remoteHash' => 'N/A',
            'version'    => 'N/A',
            'status'     => self::STATUS_GIT_NOT_TRACKED,
        );

        $class = new \ReflectionClass($module);
        $path = dirname($class->getFileName());
        $pathArg = escapeshellarg($path);

        $localHash = exec("cd $pathArg; git rev-parse HEAD");
        if ($localHash != '') {
            $remotes = exec("cd $pathArg; git remote");
            $remotes = explode("\n", $remotes);

            if (array_search("upstream", $remotes) !== false) {
                $remote = $this->getRemoteRepoInfo($pathArg, 'upstream');
            } else if (array_search("origin", $remotes) !== false) {
                $remote = $this->getRemoteRepoInfo($pathArg, 'origin');
            } else {
                $remote = null;
            }

            if ($remote) {
                $moduleInfo = array(
                    'localHash'    => $localHash,
                    'remoteHash'   => $remote['hash'],
                    'version'      => $remote['version'],
                    'status'       => $remote['status'],
                );
            }
        }

        return $moduleInfo;
    }

    /**
     * @param $pathArg
     * @param $remote
     * @return array
     */
    protected function getRemoteRepoInfo($pathArg, $remote)
    {
        exec("cd $pathArg; git fetch $remote");
        $ahead = $behind = array();
        exec("cd $pathArg; git rev-list $remote/master..HEAD", $ahead);
        exec("cd $pathArg; git rev-list HEAD..$remote/master", $behind);

        $ahead = count($ahead);
        $behind = count($behind);

        $remoteHash = exec("cd $pathArg; git rev-parse $remote/master");
        $version = exec("cd $pathArg; git describe --long --tags --always");

        if ($ahead != 0 && $behind != 0) {
            $status = sprintf(self::STATUS_GIT_BRANCH_DIVERGED, $ahead, $behind);
        } else if ($ahead == 0 && $behind != 0) {
            $status = sprintf(self::STATUS_GIT_BRANCH_BEHIND, $behind);
        } else if ($ahead != 0 && $behind == 0) {
            $status = sprintf(self::STATUS_GIT_BRANCH_AHEAD, $ahead);
        } else {
            $status = self::STATUS_GIT_UP_TO_DATE;
        }

        return array(
            'hash'    => $remoteHash,
            'version' => $version,
            'status'  => $status,
        );
    }
}