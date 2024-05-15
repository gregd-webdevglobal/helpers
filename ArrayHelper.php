<?php
namespace App\Lib;

/**
 * Helper class for array operations.
 */
class ArrayHelper {
	/**
	 * Flattens a multidimensional array into a single-dimensional array.
	 *
	 * @param array $array The multidimensional array to be flattened.
	 * @return array|false The flattened array or false if the input is not an array.
	 */
	public static function arrayFlatten($array) {
		if (!is_array($array)) {
			return FALSE;
		}
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, ArrayHelper::arrayFlatten($value));
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * Removes duplicate elements from a multidimensional array based on a specific key.
	 *
	 * @param array $array The multidimensional array to remove duplicates from.
	 * @return array The filtered array with unique values based on the specified key.
	 */
	public static function MultidimensionalUnique($array){
		$temp = array_unique(array_column($array, 'id'));
		return array_intersect_key($array, $temp);
	}

	/**
	 * Removes elements from a multidimensional array where a specific key has a given value.
	 *
	 * @param array $array The multidimensional array to remove elements from.
	 * @param string $key The key to check for the given value.
	 * @param mixed $value The value to match for removal.
	 * @return array The modified array after removing elements.
	 */
	public static function removeElementWithValue($array, $key, $value){
		foreach($array as $subKey => $subArray){
			if($subArray[$key] == $value){
				unset($array[$subKey]);
			}
		}
		return $array;
	}

	/**
	 * Removes elements from a multidimensional array where a specific key does not have a given value.
	 *
	 * @param array $array The multidimensional array to remove elements from.
	 * @param string $key The key to check for the given value.
	 * @param mixed $value The value to compare for removal.
	 * @return array The modified array after removing elements.
	 */
	public static function removeElementWithoutValue($array, $key, $value){
		foreach($array as $subKey => $subArray){
			if($subArray[$key] != $value){
				unset($array[$subKey]);
			}
		}
		return $array;
	}

	/**
	 * Changes the key of an element in an associative array.
	 *
	 * @param array $arr The associative array to modify.
	 * @param mixed $old The old key to be replaced.
	 * @param mixed $new The new key to assign to the element.
	 * @return array The modified array with the changed key.
	 */
	public static function changeKey($arr,$old,$new){
		$arr[$new] = $arr[$old];
		unset($arr[$old]);
		return$arr;
	}

	/**
	 * Checks if a given key exists in a multidimensional array.
	 *
	 * @param array $arr The multidimensional array to search.
	 * @param mixed $key The key to search for.
	 * @return bool True if the key exists, false otherwise.
	 */
	public static function multiKeyExists(array $arr, $key) {
		if (array_key_exists($key, $arr)) {
			return true;
		}
		foreach ($arr as $element) {
			if (is_array($element)) {
				if (ArrayHelper::multiKeyExists($element, $key)) {
					return true;
				}
			}

		}
		return false;
	}

	/**
	 * Search for a partial word within an array.
	 *
	 * This function checks if a partial word or string exists within
	 * any of the values in the given array. If found, it returns `true`;
	 * otherwise, it does nothing (implicitly returning `null`).
	 *
	 * @param string $word   The word or string to search for.
	 * @param array  $array  The array of strings in which to search for the word.
	 *
	 * @return bool|null     Returns `true` if the word is found within any of the array values,
	 *                       returns `false` if the array is empty or not an array,
	 *                       otherwise returns `null` if the word is not found.
	 *
	 * @example
	 * $arr = ["apple", "banana", "cherry"];
	 * partial_arraysearch("app", $arr);  // Returns true
	 * partial_arraysearch("berry", $arr); // Returns true
	 * partial_arraysearch("grape", $arr); // Does nothing (implicitly returns null)
	 */
	public static function partial_arraysearch($word, $array) {
		if (empty($array) && !is_array($array)){ return false;}
		foreach ($array as $key=>$value) {
			if (strpos( strtolower($value), strtolower($word)) !== false) {
				return true;
			}
		}

	}

	/**
	 * Sorts an array by the length of its values in descending order.
	 *
	 * This function sorts the input array based on the length of its values,
	 * with the longest strings appearing first in the sorted array.
	 *
	 * @param array $array  The array of strings to be sorted.
	 *
	 * @return array       Returns the sorted array.
	 *
	 * @example
	 * $arr = ["apple", "banana", "cherry"];
	 * sortArrayByValueLength($arr);  // Returns ["banana", "cherry", "apple"]
	 */
	public static function sortArrayByValueLength($array) {
		usort($array, function($a, $b) {
			return strlen($b) - strlen($a);
		});
		return $array;
	}

	/**
	 * Sorts a multidimensional array by the length of values in a specific column in descending order.
	 *
	 * This function sorts the input multidimensional array based on the length of its values for a given column,
	 * with the longest strings in that column appearing first in the sorted array.
	 *
	 * @param array  $array   The multidimensional array to be sorted.
	 * @param string $column  The key/index of the column in the sub-arrays to be used for sorting.
	 *
	 * @return array          Returns the sorted array.
	 *
	 * @example
	 * $arr = [
	 *     ["name" => "apple", "description" => "a fruit"],
	 *     ["name" => "banana", "description" => "a yellow fruit"],
	 *     ["name" => "cherry", "description" => "small red fruit"]
	 * ];
	 * sortMultidimensionalArrayByColumnValueLength($arr, "description");
	 * // Returns array sorted by length of "description" values in descending order.
	 */
	public static function sortMultidimensionalArrayByColumnValueLength($array, $column) {
		usort($array, function($a, $b) use ($column) {
			return strlen($b[$column]) - strlen($a[$column]);
		});
		return $array;
	}

	public static function searchSecondDimension($id, $array, $lukey) {
		foreach ($array as $key => $val) {
			if ($val[$lukey] == $id) {
				return $key;
			}
		}
		return null;
	}
}
