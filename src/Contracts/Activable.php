<?php namespace Cupparis\Auth\Contracts;

interface Activable
{

	/**
	 * Check if a user has been activated.
	 *
	 * @return string
	 */
	public function getAuthActivated();

	/**
	 * Set value of auth activated.
	 *
	 * @param  string $value
	 * @return string
	 */
	public function setAuthActivated($value);

	/**
	 * Get the column name for the "auth activated" flag.
	 *
	 * @return string
	 */
	public function getAuthActivatedName();

}
