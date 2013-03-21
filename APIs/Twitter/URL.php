<?php namespace BKWLD\APIs\Twitter;

/**
 * Utilities for dealing with user
 */
class URL {

	/**
	 * Deep link to user's page
	 */	
	static public function user($handle) {
		return 'https://twitter.com/'.$handle;
	}
	
	/**
	 * Deep link to a status
	 */	
	static public function post($handle, $id) {
		return 'https://twitter.com/'.$handle.'/status/'.$id;
	}
	
}