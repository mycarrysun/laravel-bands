<?php

namespace App\Observers;

use App\Band;

class BandObserver
{
	/**
	 * Listen to the User created event.
	 *
	 * @param  Band  $band
	 * @return void
	 */
	public function created(Band $band)
	{
		//
	}

	/**
	 * Listen to the Band deleting event.
	 *
	 * @param  Band  $band
	 * @return void
	 */
	public function deleting(Band $band)
	{
		$band->albums()->delete();
	}
}