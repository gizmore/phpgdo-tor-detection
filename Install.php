<?php
namespace GDO\TorDetection;

use GDO\Net\HTTP;
use GDO\TorDetection\Method\CronjobUpdate;
use GDO\Util\FileUtil;
use GDO\Util\Strings;

final class Install
{

	public static function install(Module_TorDetection $module): void
	{
        CronjobUpdate::make()->updateTORExitNodes();
	}

}
