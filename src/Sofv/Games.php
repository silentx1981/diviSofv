<?php

namespace Silentx\Sofv;

use Silentx\Lib;
use DateTime;
use DateTimeZone;
use DOMElement;

class Games
{
	private $url;
	private DateTimeZone $timezone;
	private $prototypeGame = [
		'date'       => '',
		'teamA'      => '',
		'teamB'      => '',
		'resultA'    => '',
		'resultB'    => '',
		'status'     => '',
		'type'       => '',
		'gamenumber' => '',
		'location'   => '',
		'hometeam'   => '',
	];

	public function __construct(string $url)
	{
		$this->url = $url;
		$this->timezone = new DateTimeZone(wp_timezone_string());
	}


	public function getCurrentGames()
	{
		return $this->parseUrlData();
	}

	private function parseUrlData()
	{
		$url = new Lib\Url();
		$dom = $url->getDomDocument($this->url);
		$elements = $dom->getElementsByTagName('div');

		$result = [];
		/** @var DOMElement $element */
		foreach ($elements as $element) {
			$class = $element->getAttribute('class');
			$pos = mb_strpos($class, 'nisListeRD list-group');
			if ($pos === false)
				continue;

			$result = array_merge($result, $this->parseUrlGame($element));
		}

		return $result;
	}

	private function parseUrlGame(DOMElement $element)
	{
		$result = [];
		$elements = $element->getElementsByTagName('div');
		/** @var DOMElement $element */
		foreach ($elements as $element) {
			if ($element instanceof DOMElement)
				$result = $this->parseUrlGameLine($element, $result);
		}

		return $result;
	}

	private function parseUrlGameLine(DOMElement $element, array $data)
	{
		$class = $element->getAttribute('class');
		$result = $data;

		if (mb_strpos($class, 'list-group-item sppTitel') !== false) {
			$datum = new DateTime(strtotime($element->nodeValue), $this->timezone);
			$datum->setTime(0, 0, 0);
			$result[] = $this->prototypeGame;
			$result[count($result) -1]['date'] = $datum;
		} else if (mb_strpos($class, 'col-md-1 time col-xs-12') !== false) {
			/** @var DateTime $datum */
			$datum = $result[count($result) -1]['date'];
			$timeEx = explode(":", $element->nodeValue);
			$datum->setTime($timeEx[0], $timeEx[1]);
		} else if (mb_strpos($class, 'col-md-5 col-xs-12 teamA') !== false) {
			$result[count($result) -1]['teamA'] = $element->nodeValue;
			if (mb_strpos($class, 'tabMyTeam') !== false)
				$result[count($result) -1]['hometeam'] = 'teamA';
		} else if (mb_strpos($class, 'col-md-5 col-xs-12 teamB') !== false) {
			$result[count($result) -1]['teamB'] = $element->nodeValue;
			if (mb_strpos($class, 'tabMyTeam') !== false)
				$result[count($result) -1]['hometeam'] = 'teamB';
		} else if (mb_strpos($class, 'sppStatusText') !== false) {
			$result[count($result) - 1]['status'] = $element->nodeValue;
		} else if (mb_strpos($class, 'col-xs-11 col-md-offset-1 font-small') !== false) {
			$elements = $element->getElementsByTagName('span');
			foreach ($elements as $elementSub)
				$result = $this->parseUrlGameLine($elementSub, $result);
			if ($result[count($result) -1]['status'] ?? null)
				$result[count($result) -1]['type'] = trim(preg_replace('/'.addcslashes($result[count($result) -1]['status'], '()./\'"').'.*/s', '', $element->nodeValue));
			else
				$result[count($result) -1]['type'] = preg_replace('/Spielnummer.*/s', '', $element->nodeValue);
			$gamenumber = trim(preg_replace('/.*Spielnummer/s', '', $element->nodeValue));
			$gamenumber = preg_replace('/(&nbsp;){1,}/', '', $gamenumber);
			if (preg_match_all('/[0-9]*/s', $gamenumber, $gamenumberMatch))
				$result[count($result) -1]['gamenumber'] = $gamenumberMatch[0][2] ?? null;
			$result[count($result) -1]['location'] = trim(preg_replace('/.*'.$result[count($result) -1]['gamenumber'].'/s', '', $element->nodeValue));
		}



		return $result;
	}

}