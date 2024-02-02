<?php
namespace GDO\TorDetection\Method;

use GDO\Core\GDO_Exception;
use GDO\Cronjob\MethodCronjob;
use GDO\Net\HTTP;
use GDO\TorDetection\GDO_TorIP;
use GDO\TorDetection\Module_TorDetection;
use GDO\Util\FileUtil;
use GDO\Util\Strings;

final class CronjobUpdate extends MethodCronjob
{

    public function runAt(): string
    {
        return $this->runHourly();
    }

    /**
     * @throws GDO_Exception
     */
    public function run(): void
    {
        $this->logNotice("Updating Tor Exit Nodes...");
        $this->updateTORExitNodes();
    }

    /**
     * @throws GDO_Exception
     */
    public function updateTORExitNodes(): void
    {
        $module = Module_TorDetection::instance();
        $url = $module->cfgExitNodesURL();
        $contents = HTTP::getFromURL($url);
        if ($contents)
        {
            $contents = str_replace("\r", '', $contents);
            $lines = explode("\n", trim($contents));
            GDO_TorIP::table()->truncate();
            $lines = array_map(function($line){ return [$line]; }, $lines);
            GDO_TorIP::bulkInsert(GDO_TorIP::table()->gdoColumnsCache(), $lines);
        }
    }

}
