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
			return "0 mins";
		}
		$video_minutes = $this->getVideoUrlFromContent($content);
		$wordCount = $this->countWords($content);
		$average_reading_speed = esc_attr(get_option('words_per_minute_option_name', AVERAGE_READING_SPEED));
		$readTimeInMinutes = ceil($wordCount / $average_reading_speed);
		$durationInMinutes = $readTimeInMinutes + $video_minutes;
		return $this->convertMinsToHours($durationInMinutes);
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

	public function getVideoUrlFromContent($htmlContent) {
		$totalMinutes = 0;

		if(empty($htmlContent)) return $totalMinutes;
		
		$youtubeRegex = '/youtube\.com\/embed\/([^\"]+)/';

		// Check if the content contains a YouTube video
		if (preg_match_all($youtubeRegex, $htmlContent, $matches) && count($matches[1]) > 0) {
			foreach ($matches[1] as $youtubeVideoId) {
				$videoDuration = $this->get_youtube_video_duration($youtubeVideoId);
				if($videoDuration) {
					$totalMinutes += $this->convert_ISO_time_to_minutes($videoDuration);
				}
			}
		}

		return $totalMinutes;
	}

	/**
	 * Get YouTube video duration.
	 *
	 * @param string $video_id YouTube video ID.
	 * @return string|false Video duration or false on failure.
	 */
	public function get_youtube_video_duration( $video_id ) {
		if ( empty( $video_id ) ) {
			return false;
		}

		$api_key = esc_attr(get_option('youtube_api_key_option_name'));
		$url     = "https://www.googleapis.com/youtube/v3/videos?id={$video_id}&part=contentDetails&key={$api_key}";

		$response = API_Utils::curl_request( $url );

		if ( empty( $response['items'][0]['contentDetails']['duration'] ) ) {
			return false;
		}

		return $response['items'][0]['contentDetails']['duration'];
	}

	/**
	 * Convert ISO time format to minutes.
	 *
	 * @param string $iso_time_format The ISO time format.
	 * @return int|false Total number of minutes or false on failure.
	 */
	function convert_ISO_time_to_minutes( $iso_time_format ) {
		try {
			$interval = new DateInterval( $iso_time_format );
			$total_seconds = $interval->s + $interval->i * 60 + $interval->h * 3600;
			$total_minutes = round( $total_seconds / 60 );
			return $total_minutes;
		} catch ( Exception $e ) {
			// Handle the error here, e.g. log the error and return a default value.
			return false;
		}
	}

	/**
	 * Converts minutes to hours and minutes format.
	 *
	 * @param int $minutes The number of minutes to convert.
	 *
	 * @return string The formatted time in hours and minutes.
	 */
	public function convertMinsToHours(int $minutes): string {
		$hours = intdiv($minutes, 60);
		$minutes = $minutes % 60;
		
		return sprintf("%02d hrs : %02d mins", $hours, $minutes);
	}
}