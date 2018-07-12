<?php namespace Cupparis\Auth\Contracts;

interface Verifiable
{

	/**
	 * Check if a user is verified.
	 *
	 * @return string
	 */
	public function getAuthVerified();

	/**
	 * Set value of auth verified.
	 *
	 * @param  string $value
	 * @return string
	 */
	public function setAuthVerified($value);

	/**
	 * Get the column name for the "auth verified" flag.
	 *
	 * @return string
	 */
	public function getAuthVerifiedName();


}
