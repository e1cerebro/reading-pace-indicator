<?php
class Utils {
	/**
	 * Generates the estimated read duration for a given string of content.
	 *
	 * @param string $content The content to calculate the read duration for.
	 *
	 * @return string The estimated read duration in minutes or hours, rounded up.
	 */
	public function generateReadDuration(string $content): string {
		if (empty($content)) {
			return "0 minutes";
		}
		
		$wordCount = $this->countWords($content);
		$average_reading_speed = get_option('words_per_minute_option_name', AVERAGE_READING_SPEED);
		$readTimeInMinutes = ceil($wordCount / $average_reading_speed);
		return $this->convertMinsToHours($readTimeInMinutes);
	}

	/**
	 * Counts the number of words in a given string.
	 *
	 * @param string $contentString The string to count the number of words in.
	 *
	 * @return int The number of words in the string.
	 */
	public function countWords(string $contentString): int
	{
		if (empty($contentString)) {
			return 0;
		}
		
		$strippedString = strip_tags($contentString, []);
		$cleanedString = preg_replace('/\s+/', ' ', $strippedString);
		$wordsArray = explode(" ", $cleanedString);
		
		$cleanedWordsArray = array_filter($wordsArray, fn($word) => !empty($word));
		$wordCount = count($cleanedWordsArray);
		
		return $wordCount;
	}

	/**
	 * Converts minutes to hours and minutes format.
	 *
	 * @param int $minutes The number of minutes to convert.
	 *
	 * @return string The formatted time in hours and minutes.
	 */
	public function convertMinsToHours(int $minutes): string
	{
		$hours = intdiv($minutes, 60);
		$formattedHours = $hours < 10 ? "0{$hours}" : $hours;
		$hoursLabel = $hours > 1 ? 'hrs' : 'hr';
		$remainingMinutes = $minutes % 60;
		$formattedMinutes = $remainingMinutes < 10 ? "0{$remainingMinutes}" : $remainingMinutes;
		$minutesLabel = $remainingMinutes > 1 ? 'mins' : 'min';
		
		return "{$formattedHours} {$hoursLabel} : {$formattedMinutes} {$minutesLabel}";
	}
}


