<?php
namespace GDO\TorDetection\Method;

use GDO\Core\GDO_Exception;
use GDO\Cronjob\MethodCronjob;
use GDO\Net\HTTP;
use GDO\TorDetection\Module_TorDetection;
use GDO\Util\FileUtil;
use GDO\Util\Strings;

final class CronjobUpdate extends MethodCronjob
{

    /**
     * @throws GDO_Exception
     */
    public function run(): void
    {
        $this->updateTORExitNodes();
    }

    /**
     * @throws GDO_Exception
     */
    public function updateTORExitNodes(): void
    {
        $module = Module_TorDetection::instance();
        $url = $module->cfgExitNodesURL();
        $path = $module->getExitNodePath();
        FileUtil::createDir(Strings::rsubstrTo($path, '/'));
        $contents = HTTP::getFromURL($url);
        file_put_contents($path, $contents);

    }

}
