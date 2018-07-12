<?php namespace Cupparis\Auth\Contracts;

interface ToBeVerified {

	/**
	 * Get the e-mail address where password reset links are sent.
	 *
	 * @return string
	 */
	public function getEmailForVerification();

}
