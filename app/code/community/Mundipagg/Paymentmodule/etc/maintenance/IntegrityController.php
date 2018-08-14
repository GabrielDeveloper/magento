<?php

namespace Mundipagg\Integrity;

class IntegrityController
{
    /** @var SystemInfoInterface */
    protected $systemInfo;

    public function __construct(SystemInfoInterface $systemInfo)
    {
        $this->systemInfo = $systemInfo;
    }

    public function getSystemInformation()
    {
        return [
            'integrityScript' => __FILE__,
            'modmanFilePath' => $this->systemInfo->getModmanPath(),
            'integrityCheckFile' => $this->systemInfo->getIntegrityCheckPath(),
            'phpVersion' => phpversion(),
            'moduleVersion' => $this->systemInfo->getModuleVersion(),
            'platformVersion' => $this->systemInfo->getPlatformVersion(),
            'platformRootDir' => $this->systemInfo->getPlatformRootDir(),
            'directoriesIgnored' => $this->systemInfo->getDirectoriesIgnored(),
            'installType' => $this->systemInfo->getInstallType()
        ];
    }

    public function getIntegrityCheck()
    {
        $modmanPath = $this->systemInfo->getModmanPath();
        $integrityPath = $this->systemInfo->getIntegrityCheckPath();
        $directoriesIgnored = $this->systemInfo->getDirectoriesIgnored();

        $integrityEngine = new IntegrityEngine();
        $integrityCheck= $integrityEngine->verifyIntegrity($modmanPath, $integrityPath, $directoriesIgnored);

        return $integrityCheck;
    }

    public function showGeneralInfo($title, $info)
    {
        echo "<h3>$title</h3>";
        echo '<pre>';
        print_r($info);
        echo '</pre>';
        echo json_encode($info);
    }

    public function showNonEmptyInfo($message, $info)
    {
        if (count($info) > 0) {
            echo "<h3 style='color:red'>$message</h3>";
            echo '<pre>';
            print_r($info);
            echo '</pre>';
            echo json_encode($info);
        }
    }
}
