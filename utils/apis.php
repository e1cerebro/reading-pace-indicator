<?php
	class API_Utils {
		/**
		 * Perform a cURL request.
		 *
		 * @param string $url The URL to send the request to.
		 * @param string $method The HTTP method to use for the request.
		 * @param array $data An optional array of data to send with the request.
		 * @param array $headers An optional array of headers to send with the request.
		 * 
		 * // Send a GET request.
		 * $response = curlRequest('https://example.com');
		 * // Send a POST request.
		 * $response = curlRequest('https://example.com', 'POST', array('name' => 'John', 'email' => 'john@example.com'));
		 * // Send a PUT request.
		 * $response = curlRequest('https://example.com/user/1', 'PUT', array('name' => 'John Smith'));
		 * // Send a DELETE request.
		 * $response = curlRequest('https://example.com/user/1', 'DELETE');
		 * // Send a request with custom headers.
		 * $headers = array('Authorization: Bearer abc123', 'Content-Type: application/json');
		 * $response = curlRequest('https://example.com', 'POST', array('name' => 'John', 'email' => 'john@example.com'), $headers);
		 * 
		 * @return mixed The response data.
		 */
		public static function curl_request($url, $method = 'GET', $data = array(), $headers = array()) {
			// Initialize a new curl session.
			$ch = curl_init();

			// Set the URL for the request.
			curl_setopt($ch, CURLOPT_URL, $url);

			// Set the method for the request.
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

			// If data was provided, set the request body.
			if (!empty($data)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}

			// If headers were provided, set them.
			if (!empty($headers)) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}

			// Set some other common options.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);

			// Execute the request.
			$response = curl_exec($ch);

			// Check for errors.
			if ($response === false) {
				$error = curl_error($ch);
				curl_close($ch);
				throw new Exception('cURL error: ' . $error);
			}

			// Get some information about the response.
			$info = curl_getinfo($ch);

			// Close the curl session.
			curl_close($ch);

			// If the response is JSON, decode it.
			if (isset($info['content_type']) && strpos($info['content_type'], 'application/json') !== false) {
				$response = json_decode($response, true);
			}

			// Return the response data.
			return $response;
		}

	}