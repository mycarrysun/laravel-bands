<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

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
	 * The attributes that are appended to it's JSON form.
	 *
	 * @var array
	 */
	protected $appends = [
		'band_name',
	];

	/**
	 * The attributes that are cast to native types
	 */
	protected $casts = [
		'number_of_tracks' => 'integer',
	];


	/******************************************/
	//              Relationships
	/******************************************/
	/**
	 * Get the user for this album
	 */
	public function user() {
		return $this->band->user;
	}

	/**
	 * Get the band this album belongs to
	 */
	public function band() {
		return $this->belongsTo( 'App\Band' );
	}


	/******************************************/
	//                Accessors
	/******************************************/
	public function getRecordedDateAttribute() {
		if ( ! empty( $this->attributes['recorded_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['recorded_date'] ) );
		}
	}

	public function getReleaseDateAttribute() {
		if ( ! empty( $this->attributes['release_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['release_date'] ) );
		}
	}

	public function getBandNameAttribute() {
		return $this->band['name'];
	}



	/*******************************************/
	//                Mutators
	/*******************************************/
	public function setRecordedDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['recorded_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}

	public function setReleaseDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['release_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}


	/******************************************/
	//                 Scopes
	/******************************************/
	public function scopeSearchBand( $query, $id ) {
		if ( ! empty( $id ) ) {
			return $query->where('band_id', $id);
		}
	}

}
