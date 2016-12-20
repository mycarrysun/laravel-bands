<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Band extends Model {
	use SoftDeletes;

	/******************************************/
	//               Attributes
	/******************************************/
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'start_date',
		'website',
		'still_active',
	];

	/**
	 * The attributes that are cast to native types
	 *
	 * @var array
	 */
	protected $casts = [
		'still_active' => 'boolean'
	];


	/******************************************/
	//              Relationships
	/******************************************/
	/**
	 * Get the user for this band
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo( 'App\User' );
	}

	/**
	 * Get the albums for this band
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function albums() {
		return $this->hasMany( 'App\Album' );
	}


	/******************************************/
	//                Accessors
	/******************************************/
	/**
	 * Get the start_date attribute
	 *
	 * @return false|string
	 */
	public function getStartDateAttribute() {
		if ( ! empty( $this->attributes['start_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['start_date'] ) );
		}
	}


	/*******************************************/
	//                Mutators
	/*******************************************/
	/**
	 * Set the start_date attribute
	 *
	 * @param $date
	 */
	public function setStartDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['start_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}
}
