<?php namespace Bkwld\Library\Laravel;

// Dependencies
use DB;
use Exception;
use Input;
use Redirect;
use Symfony\Component\HttpFoundation\File\File;
use URL;

class Validator {
	
	/**
	 * Is like the unique validator but tests multiple columns.  All columns
	 * must be the same for the validation to fail.
	 * 
	 * Note: It looks for the values of the other referenced column from Input::get()
	 * Note: If useing with Decoy slugs, don't forget that foreign keys unique-where
	 *   clauses are added for free by Bkwld\Decoy\Input\Slug
	 * 
	 * Params:
	 * - Table name
	 * - The other columns.  Semicolon delimited
	 * - Optional column name of the attribute
	 * - Optional id to ignore
	 * - Optional column for id
	 * 
	 * Example:
	 * array(
	 * 	'slug' => 'unique_with:tags,type;category,slug,10'
	 * )
	 * 
	 */
	public function uniqueWith($attribute, $value, $parameters) {
		
		// You must specify additional columns
		if (!isset($parameters[0]) || !isset($parameters[1])) throw new Exception('Table and additional columns must be provided');

		// Optional column name for the attribtue
		if (isset($parameters[2])) {
			$attribute = $parameters[2];
		}

		// Form query
		$query = DB::table($parameters[0])->where($attribute, '=', $value);
		foreach(explode(';', $parameters[1]) as $column) {
			if (!Input::has($column)) throw new Exception('Column not found in input: '.$column);
			$query = $query->where($column, '=', Input::get($column));
		}

		// We also allow an ID to be specified that will not be included in the
		// uniqueness check. This makes updating columns easier since it is
		// fine for the given ID to exist in the table.
		if (isset($parameters[3])) {
			$id = (isset($parameters[4])) ? $parameters[4] : 'id';
			$query->where($id, '<>', $parameters[3]);
		}

		return $query->count() == 0;
		
	}
	
	/**
	 * Test if a the field has a file or references a valid file path
	 */
	public function file($attribute, $value, $parameters) {
		if ($value instanceof File && $value->getPath() != '') return true;
		if (is_array($value) && is_file(public_path().$value[0])) return true; // How replaced files look
		if (is_string($value) && is_file(public_path().$value)) return true;
		return false;
	}

}