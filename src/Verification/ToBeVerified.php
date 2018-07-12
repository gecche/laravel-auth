<?php namespace Cupparis\Auth\Verification;

trait ToBeVerified {

	/**
	 * Get the e-mail address where password reset links are sent.
	 *
	 * @return string
	 */
	public function getEmailForVerification()
	{
		return $this->email;
	}

}
