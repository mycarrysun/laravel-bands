<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Album extends Model {
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
		'recorded_date',
		'release_date',
		'number_of_tracks',
		'label',
		'producer',
		'genre',
	];

	/**
	 * The attributes that are cast to native types
	 *
	 * @var array
	 */
	protected $casts = [
		'number_of_tracks' => 'integer',
	];


	/******************************************/
	//              Relationships
	/******************************************/
	/**
	 * Get the user that created this album
	 *
	 * @return User
	 */
	public function user() {
		return $this->band->user;
	}

	/**
	 * Get the band this album belongs to
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function band() {
		return $this->belongsTo( 'App\Band' );
	}


	/******************************************/
	//                Accessors
	/******************************************/
	/**
	 * Get recorded_date attribute
	 *
	 * @return false|string
	 */
	public function getRecordedDateAttribute() {
		if ( ! empty( $this->attributes['recorded_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['recorded_date'] ) );
		}
	}

	/**
	 * Get release_date attribute
	 *
	 * @return false|string
	 */
	public function getReleaseDateAttribute() {
		if ( ! empty( $this->attributes['release_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['release_date'] ) );
		}
	}


	/*******************************************/
	//                Mutators
	/*******************************************/
	/**
	 * Set recorded_date attribute
	 *
	 * @param $date
	 */
	public function setRecordedDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['recorded_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}

	/**
	 * Set release_date attribute
	 *
	 * @param $date
	 */
	public function setReleaseDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['release_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}


	/******************************************/
	//                 Scopes
	/******************************************/
	/**
	 * Filter albums by the selected band ID
	 *
	 * @param $query
	 * @param $id
	 *
	 * @return mixed
	 */
	public function scopeSearchBand( $query, $id ) {
		if ( ! empty( $id ) ) {
			return $query->where('band_id', $id);
		}
	}

}
