<?php
namespace GDO\TorDetection;

use GDO\Core\GDO_Exception;
use GDO\TorDetection\Method\CronjobUpdate;

final class Install
{

    /**
     * @throws GDO_Exception
     */
    public static function install(Module_TorDetection $module): void
	{
        CronjobUpdate::make()->updateTORExitNodes();
	}

}
