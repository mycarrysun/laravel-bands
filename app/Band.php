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


	/******************************************/
	//              Relationships
	/******************************************/
	/**
	 * Get the user for this band
	 */
	public function user() {
		return $this->belongsTo( 'App\User' );
	}

	/**
	 * Get the albums for this band
	 */
	public function albums() {
		return $this->hasMany( 'App\Album' );
	}


	/******************************************/
	//                Accessors
	/******************************************/
	public function getStartDateAttribute() {
		if ( ! empty( $this->attributes['start_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['start_date'] ) );
		}
	}


	/*******************************************/
	//                Mutators
	/*******************************************/
	public function setStartDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['start_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}


	/******************************************/
	//                 Scopes
	/******************************************/
	public function scopeSearch( $query, $search ) {
		if ( ! empty( $search ) ) {
			return $query->where( 'name', 'like', "%$search%" )
			             ->orwhere( 'website', 'like', "%$search%" );
		}
	}
}
