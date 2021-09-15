<?php

namespace SlopeIt\ClockMock\DateTimeMock;


use Nette\Utils\DateTime;
use SlopeIt\ClockMock\ClockMock;

class NetteUtilsDateTimeMock extends DateTime
{

	public function __construct(?string $datetime = 'now', ?\DateTimeZone $timezone = NULL)
	{
		$datetime = $datetime ?? 'now';

		parent::__construct($datetime, $timezone);

		$this->setTimestamp(strtotime($datetime, ClockMock::getFrozenDateTime()->getTimestamp()));

		if ($this->shouldUseMicrosecondsOfFrozenDate($datetime)) {
			$this->setTime(
				idate('H', $this->getTimestamp()),
				idate('i', $this->getTimestamp()),
				idate('s', $this->getTimestamp()),
				(int) ClockMock::getFrozenDateTime()->format('u')
			);
		}
	}



	private function shouldUseMicrosecondsOfFrozenDate(string $datetime): bool
	{
		// After some empirical tests, we've seen that microseconds are set to the current actual ones only when all of
		// these variables are false (i.e. when an absolute date or time is not provided).
		$parsedDate = date_parse($datetime);

		return $parsedDate['year'] === FALSE
			&& $parsedDate['month'] === FALSE
			&& $parsedDate['day'] === FALSE
			&& $parsedDate['hour'] === FALSE
			&& $parsedDate['minute'] === FALSE
			&& $parsedDate['second'] === FALSE;
	}

}