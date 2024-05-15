<?php
namespace App\Lib;

Use Cache;
Use Illuminate\Support\Facades\Http;

class ApiHttpReq {
	static public function APIRequest($apiURL, $postInput, $iscached = true, $cacheseconds = 600) {
		if ($iscached) {
			$name = "";
			$endpointPCS = explode("/", $apiURL);
			foreach ($endpointPCS as $key => $val) {
				$name .= $val . ".";
			}
			foreach ($postInput as $key => $val) {
				if (($val) && ($val <> '') && (!is_array($val))) {
					$name .= $key . "." . $val . ".";
				}
			}
			$name .= $cacheseconds;

			return Cache::remember($name, $cacheseconds, function () use ($apiURL, $postInput) {
				return Http::withoutVerifying()->withToken(env('API_TOKEN'))->post($apiURL, $postInput)->json();
			});
		} else {
			return Http::withoutVerifying()->withToken(env('API_TOKEN'))->post($apiURL, $postInput)->json();
		}
	}

	static public function APIRequestGET($apiURL, $getInput, $iscached = true, $cacheseconds = 600) {
		if ($iscached) {
			$name = "";
			$endpointPCS = explode("/", $apiURL);
			foreach ($endpointPCS as $key => $val) {
				$name .= $val . ".";
			}
			foreach ($getInput as $key => $val) {
				if (($val) && ($val <> '') && (!is_array($val))) {
					$name .= $key . "." . $val . ".";
				}
			}
			$name .= $cacheseconds;
			return Cache::remember($name, $cacheseconds, function () use ($apiURL, $getInput) {
				return Http::get($apiURL, $getInput)->json();
			});
		} else {
			return Http::get($apiURL, $getInput)->json();
		}
	}
}
