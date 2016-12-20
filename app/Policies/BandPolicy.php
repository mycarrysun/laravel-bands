<?php

namespace App\Policies;

use App\User;
use App\Band;
use Illuminate\Auth\Access\HandlesAuthorization;

class BandPolicy
{
    use HandlesAuthorization;

	/**
	 * Authorize all actions for admins
	 *
	 * @param User $user
	 * @param $ability
	 *
	 * @return bool
	 */
	public function before(User $user, $ability){
		if($user->is_admin){
			return true;
		}
	}

    /**
     * Determine whether the user can view the band.
     *
     * @param  \App\User  $user
     * @param  \App\Band  $band
     * @return mixed
     */
    public function view(User $user, Band $band)
    {
	    return $user->id === $band->user_id;
    }

    /**
     * Determine whether the user can create bands.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the band.
     *
     * @param  \App\User  $user
     * @param  \App\Band  $band
     * @return mixed
     */
    public function update(User $user, Band $band)
    {
	    return $user->id === $band->user_id;
    }

    /**
     * Determine whether the user can delete the band.
     *
     * @param  \App\User  $user
     * @param  \App\Band  $band
     * @return mixed
     */
    public function delete(User $user, Band $band)
    {
        return $user->id === $band->user_id;
    }
}
