<?php
namespace GDO\TorDetection;

use GDO\Net\HTTP;
use GDO\Util\FileUtil;
use GDO\Util\Strings;

final class Install
{

	public static function install(Module_TorDetection $module): void
	{
		$url = $module->cfgExitNodesURL();
		$path = $module->getExitNodePath();
		FileUtil::createDir(Strings::rsubstrTo($path, '/'));
		file_put_contents($path, HTTP::getFromURL($url));
	}

}
