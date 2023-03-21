<?php
namespace GDO\TorDetection\Method;

use GDO\UI\MethodPage;

final class Restricted extends MethodPage
{

	public function isAlwaysAllowed(): bool { return true; }

	protected function getTemplateName(): string
	{
		return 'tor_restricted_page.php';
	}

	public function getMethodTitle(): string
	{
		return t('restricted');
	}

	public function getMethodDescription(): string
	{
		return t('err_tor_restricted');
	}

}
