<?php

namespace Silentx\Sofv;

use Silentx\Lib;

class Games
{
	public function getCurrentGames()
	{
		$url = new Lib\Url();
		$dom = $url->getDomDocument('https://www.sofv.ch/solothurner-fussballverband/vereine-sofv/verein-sofv.aspx/v-1831/a-as/');


		$x = $dom->getElementsByTagName('div');

		/** @var \DOMElement $y */
		foreach ($x as $y) {
			echo "<hr>";
			$class = $y->getAttribute('class');
			print_r($class);
			echo mb_strpos($class, 'nisListeRD')."<br>";
			if (mb_strpos($class, 'nisListeRD') !== false)
				print_r($y->nodeValue);
		}

		return [];
	}

}