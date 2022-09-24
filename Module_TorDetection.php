<?php
namespace GDO\TorDetection;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\Net\GDT_Url;
use GDO\Net\GDT_IP;
use GDO\Core\GDO_RedirectError;
use GDO\Core\GDT_Hook;

/**
 * Tor detection and optional restriction.
 * 
 * @author gizmore
 * @since 7.0.1
 */
final class Module_TorDetection extends GDO_Module
{
	public int $priority = 20;
	
	public function getDependencies() : array
	{
		return [
			'Net'
		];
	}
	
	##############
	### Config ###
	##############
	public function getConfig() : array
	{
		return [
			GDT_Checkbox::make('tor_restriction')->initial('0'),
			GDT_Url::make('tor_exitnode_url')->initial('https://check.torproject.org/torbulkexitlist')->allowExternal()->notNull(),
		];
	}
	public function cfgRestricted() : bool { return $this->getConfigValue('tor_restriction'); }
	public function cfgExitNodesURL() : string { return $this->getConfigVar('tor_exitnode_url'); }

	#############
	### Hooks ###
	#############
	public function onInstall() : void { Install::install($this); }

	public function onLoadLanguage() : void { $this->loadLanguage('lang/tor'); }
	
	public function getExitNodePath() : string
	{
		return $this->tempPath('exit_nodes.txt');
	}
	
	public function onModuleInit() : void
	{
		$path = $this->getExitNodePath();
		if ($file = file_get_contents($path))
		{
			if (strpos($file, GDT_IP::$CURRENT) !== false)
			{
				$this->torDetected();
			}
		}
	}
	
	private function torDetected() : void
	{
		if ($this->cfgRestricted())
		{
			$this->restrict();
		}
		
		GDT_Hook::callHook('TorDetected');
	}
	
	private function restrict() : void
	{
		global $me;
		if (!$me->isAlwaysAllowed())
		{
			$href = $this->href('Restricted');
			throw new GDO_RedirectError('err_tor_restricted', null, $href);
		}
	}
	
}
