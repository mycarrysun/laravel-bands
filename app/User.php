<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

	/******************************************/
	//               Attributes
	/******************************************/
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	/**
	 * The attributes that are appended to it's JSON form.
	 *
	 * @var array
	 */
	protected $appends = [
		'is_admin'
	];

	/******************************************/
	//              Relationships
	/******************************************/
	public function bands(){
		return $this->hasMany('App\Band');
	}

	public function albums(){
		return $this->hasManyThrough('App\Album', 'App\Band');
	}


	/******************************************/
	//               Accessors
	/******************************************/
	public function getIsAdminAttribute(){
		return $this->id === 1; //corresponds to Mike Harrison's account
	}
}
