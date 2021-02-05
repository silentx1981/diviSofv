<?php

namespace Silentx\Lib;

use DOMDocument;
use tidy;

Class Url
{

	public function getDomDocument(string $url)
	{
		$dom = new DOMDocument();
		$tidy = new tidy();
		$content = file_get_contents($url);
		$content = $tidy->repairString($content, [
			'clean' => true,
			'output-xhtml' => true,
			'show-body-only' => true,
			'wrap' => 0,
		]);

		$dom = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($content);
		libxml_use_internal_errors(false);

		return $dom;

	}
}