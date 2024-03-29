<?php
namespace GDO\TorDetection;

use GDO\Core\Application;
use GDO\Core\GDO_DBException;
use GDO\Core\GDO_Module;
use GDO\Core\GDO_RedirectError;
use GDO\Core\GDT;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_Hook;
use GDO\Net\GDT_IP;
use GDO\Net\GDT_Url;
use GDO\User\GDO_User;

/**
 * Tor detection and optional restriction.
 *
 * @since 7.0.3
 * @author gizmore
 */
final class Module_TorDetection extends GDO_Module
{

	public int $priority = 20;

	public function getDependencies(): array
	{
		return [
			'Net',
		];
	}

    public function getClasses(): array
    {
        return [
            GDO_TorIP::class,
        ];
    }

	##############
	### Config ###
	##############
	public function getConfig(): array
	{
		return [
			GDT_Checkbox::make('tor_restriction')->initial('0'),
			GDT_Url::make('tor_exitnode_url')->initial('https://raw.githubusercontent.com/SecOps-Institute/Tor-IP-Addresses/master/tor-exit-nodes.lst')->allowExternal()->notNull(),
		];
	}

	public function onInstall(): void { Install::install($this); }

	public function onLoadLanguage(): void { $this->loadLanguage('lang/tor'); }

	#############
	### Hooks ###
	#############

    /**
     * @throws GDO_RedirectError
     */
    private function torDetected(): ?GDT
	{
		if ($this->cfgRestricted())
		{
			$this->restrict();
		}

		return GDT_Hook::callHook('TorDetected');
	}

	public function cfgRestricted(): bool { return $this->getConfigValue('tor_restriction'); }

	private function restrict(): void
	{
		global $me;
		if (!$me->isAlwaysAllowed())
		{
			$href = $this->href('Restricted');
			throw new GDO_RedirectError('err_tor_restricted', null, $href);
		}
	}

	public function cfgExitNodesURL(): string { return $this->getConfigVar('tor_exitnode_url'); }

    /**
     * @throws GDO_DBException
     */
    public function hookBeforeExecute(): ?GDT
    {
        if (GDO_TorIP::isTorIP(GDT_IP::$CURRENT))
        {
            return $this->torDetected();
        }
        return null;
    }
}
