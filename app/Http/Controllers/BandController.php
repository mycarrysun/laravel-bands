<?php

namespace App\Http\Controllers;

use App\Band;
use App\Http\Requests\BandRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class BandController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index() {
		$user = Auth::user();

		//Get query vars for sorting/pagination
		$per_page = Input::get( 'per_page' ) ? Input::get( 'per_page' ) : 10;
		$sort_by  = Input::get( 'sort' ) ? Input::get( 'sort' ) : 'name';
		$sort_dir = Input::get( 'sort_dir' ) ? Input::get( 'sort_dir' ) : 'asc';
		$query    = Input::get( 'query' );


		if ( $user->is_admin ) {
			//user is admin
			//get all bands in the system
			$bands = Band::orderBy( $sort_by, $sort_dir )
			             ->paginate( $per_page );
		} else {
			//user is not an admin
			//only get this user's bands
			$bands = $user->bands()
			              ->orderBy( $sort_by, $sort_dir )
			              ->paginate( $per_page );
		}


		$data = [
			'bands'   => $bands,

			//use this object for query vars and pagination appends() method
			'appends' => [
				'per_page' => $per_page,
				'sort'     => $sort_by,
				'sort_dir' => $sort_dir,
				'query'    => $query,
			],
		];

		return view( 'bands.list', $data );
	}

	/**
	 * Get the form for creating a new resource
	 *
	 * @return View
	 */
	public function create() {
		return view( 'bands.form' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param BandRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( BandRequest $request ) {
		$band = new Band;

		$band->fill( $request->all() );

		$band->user()->associate( Auth::user() );

		$band->save();

		return redirect( '/bands' )->with( 'messages', [
			[
				'type'    => 'success',
				'content' => "You have successfully added the band: $band->name.",
			],
		] );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return View
	 */
	public function show( $id ) {
		$band = Band::findOrFail( $id );
		$user = Auth::user();

		if ( ! $user->can( 'view', $band ) ) {

			return redirect( '/bands' )->with( 'messages', [
				[
					'type'    => 'danger',
					'content' => 'You are not allowed to view this band.',
				],
			] );

		}

		return view( 'bands.view' )->with( 'band', $band );

	}

	/**
	 * Get the form for editing an existing resource.
	 *
	 * @return View
	 */
	public function edit( $id ) {

		$user = Auth::user();

		$band = Band::findOrFail( $id );

		if ( ! $user->can( 'update', $band ) ) {
			//user not allowed to edit this band
			return redirect( '/bands' )->with( 'messages', [
				[
					'type'    => 'danger',
					'content' => 'You are not allowed to edit this band.',
				],
			] );
		}

		$data = [
			'band' => $band,
		];

		return view( 'bands.form', $data );
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param BandRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( BandRequest $request, $id ) {
		$messages = [];
		$band     = Band::findOrFail( $id );
		$user     = Auth::user();

		if ( $user->can( 'update', $band ) ) {
			$band->fill( $request->all() );

			$band->user()->associate( $user );

			$band->save();

			array_push( $messages, [
				'type'    => 'success',
				'content' => "You have successfully updated the band: $band->name.",
			] );

		} else {

			array_push( $messages, [
				'type'    => 'error',
				'content' => 'You are not allowed to edit this band.',
			] );

		}

		return redirect( '/bands' )->with( 'messages', $messages );

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		$messages = [];
		$band     = Band::findOrFail( $id );
		$user     = Auth::user();

		if ( $user->can( 'delete', $band ) ) {

			$band->delete();

			array_push( $messages, [
				'type'    => 'success',
				'content' => 'Band deleted.',
			] );

		} else {

			array_push( $messages, [
				'type'    => 'danger',
				'content' => 'You are not allowed to delete this band.',
			] );

		}

		$params = [
			'sort'     => Input::get( 'sort' ) ? Input::get( 'sort' ) : 'name',
			'sort_dir' => Input::get( 'sort_dir' ) ? Input::get( 'sort_dir' ) : 'asc',
			'per_page' => Input::get( 'per_page' ) ? Input::get( 'per_page' ) : 10,
			'page'     => Input::get( 'page' ),
		];

		return redirect( '/bands?' . http_build_query( $params ) )->with( 'messages', $messages );
	}
}
