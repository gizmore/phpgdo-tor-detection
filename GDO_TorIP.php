<?php
namespace GDO\TorDetection;

use GDO\Core\GDO;
use GDO\Core\GDO_DBException;
use GDO\Net\GDT_IP;

final class GDO_TorIP extends GDO
{

    public function gdoCached(): bool { return false; }

    public function gdoColumns(): array
    {
        return [
            GDT_IP::make('tor_ip')->primary()->notNull(),
        ];
    }

    /**
     * @throws GDO_DBException
     */
    public static function isTorIP(string $ip): bool
    {
        return self::table()->countWhere("tor_ip='$ip'") > 0;
    }

}
