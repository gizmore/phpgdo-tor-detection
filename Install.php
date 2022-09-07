<?php
namespace GDO\TorDetection;

use GDO\Net\HTTP;

final class Install
{
	public static function install(Module_TorDetection $module) : void
	{
		$url = $module->cfgExitNodesURL();
		$path = $module->getExitNodePath();
		file_put_contents($path, HTTP::getFromURL($url));
	}
	
}
