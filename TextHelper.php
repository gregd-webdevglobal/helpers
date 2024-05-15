<?php
namespace App\Lib;

Use App\Lib\ArrayHelper;
Use Illuminate\Support\Str;

/**
 * Class TextHelper
 *
 * Provides various text manipulation methods.
 */
class TextHelper {
	/**
	 * Breaks long text into paragraphs with a maximum length and returns the HTML representation.
	 *
	 * @param string $text The text to break.
	 * @param int $length The maximum length of each paragraph.
	 * @param int $maxLength The maximum length of the entire text.
	 * @return string The HTML representation of the broken text.
	 */
	private static $usedWords = array();
	public static function breakLongText($text, $length = 400, $maxLength = 450){
		$text = strip_tags($text);
		$text = TextHelper::cleanCP1252($text);
		$textLength = strlen($text);
		$splitText = array();

		if (!($textLength > $maxLength)){
			$splitText[] = $text;
			return "<p>".$text."</p>\n";
		}

		$needle = '.';

		while (strlen($text) > $length){
			$end = strpos($text, $needle, $length);
			if ($end === false){
				$splitText[] = substr($text,0);
				$text = '';
				break;
			}

			$end++;
			$splitText[] = substr($text,0,$end);
			$text = substr_replace($text,'',0,$end);
		}
		if ($text){
			$splitText[] = substr($text,0);
		}

		$paragraph="";
		foreach ($splitText as $line){
			$paragraph =  $paragraph . "<p>".$line."</p>\n";
		}
		return $paragraph;
	}

	/**
	 * Cleans CP1252 characters from the given description.
	 *
	 * @param string $description The text to clean.
	 * @return string The cleaned text.
	 */
	public static function cleanCP1252($description){
		$clean = mb_convert_encoding($description, "HTML-ENTITIES", 'UTF-8');
		$search = array("â€™","â€™","&acirc;&euro;&oelig;","â€œ","â€","Â");
		$replace = array("'","'","'","\"","\"","");
		$clean=str_replace($search, $replace, $description);
		return $clean;
	}

	/**
	 * Limits the number of words in the given text and appends an ellipsis if necessary.
	 *
	 * @param string $text The text to limit.
	 * @param int $limit The maximum number of words.
	 * @param string $chars Additional characters to consider as part of words.
	 * @return string The limited text with an optional ellipsis.
	 */
	public static function wordLimiter( $text, $limit = 30, $chars = '0123456789' ) {
		$text = TextHelper::cleanCP1252($text);
		if( strlen( $text ) > $limit ) {
			$words = str_word_count( $text, 2, $chars );
			$words = array_reverse( $words, TRUE );
			foreach( $words as $length => $word ) {
				if( $length + strlen( $word ) >= $limit ) {
					array_shift( $words );
				} else {
					break;
				}
			}
			$words = array_reverse( $words );
			$text = implode( " ", $words ) . '&hellip;';
		}
		return $text;
	}

	/**
	 * Cleans up and normalizes whitespace within a text string by replacing sequences of whitespace
	 * characters with a single space and trimming the result.
	 *
	 * @param string $text The input text string to be cleaned.
	 * @return string The cleaned text string with normalized whitespace.
	 */
	public static function cleanWordWrap($text) {
		return trim(preg_replace("/(\s*[\r\n]+\s*|\s+)/", ' ', $text));
	}

	/**
	 * Converts a weight from pounds to kilograms, first normalizing the input to remove non-digit characters.
	 *
	 * @param string|int $pounds The weight in pounds.
	 * @return float The weight in kilograms, rounded to one decimal place.
	 */
	public static function poundsToKilos($pounds) {
		$pounds= self::normalizeWeight($pounds);
		$pounds = preg_replace('~\D~', '', $pounds);
		return round($pounds * 0.4535,1);
	}

	/**
	 * Converts a height in feet and inches to centimeters.
	 *
	 * @param string $height The height string, expected in format 'X'Y" where X is feet and Y is inches.
	 * @return float The height in centimeters, rounded to the nearest integer.
	 */
	public static function heightToCentimeters($height) {
		$height = self::normalizeHeight($height);
		$heightArray = array_filter(explode("'", $height));
		foreach ($heightArray as $key => $value) {
			$heightArray[$key] = preg_replace('~\D~', '', $value);
		}
		$feet = $heightArray[0]*12;
		if (count($heightArray) > 1) {
			$inches = $feet + $heightArray[1];
		}else{
			$inches = $feet;
		}
		return round($inches * 2.54);
	}

	/**
	 * Normalizes a weight string by removing non-digit characters.
	 *
	 * @param string $weight The weight string to be normalized.
	 * @return string The normalized weight string containing only digits.
	 */
	public static function normalizeWeight($weight) {
		return preg_replace('~\D~', '', $weight);
	}

	/**
	 * Normalizes a height string by cleaning up non-digit characters, ensuring the format is consistent
	 * for further processing.
	 *
	 * @param string $height The height string to be normalized.
	 * @return string The normalized height string, properly formatted with feet and inches.
	 */
	public static function normalizeHeight($height) {
		$heightArray = explode("'", $height);
		if (count($heightArray) == 1) {
			return preg_replace('~\D~', '', $heightArray[0]) . "'";
		}
		if ($heightArray[1] == 0) {
			return preg_replace('~\D~', '', $heightArray[0]) . "'";
		}
		if (is_numeric(preg_replace('~\D~', '', $heightArray[0])) && is_numeric(preg_replace('~\D~', '', $heightArray[1]))) {
			return preg_replace('~\D~', '', $heightArray[0]) . "'" . preg_replace('~\D~', '', $heightArray[1]) . "\"";
		}
		return $height;
	}
}
