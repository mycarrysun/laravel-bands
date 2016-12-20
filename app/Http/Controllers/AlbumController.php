<?php

namespace App\Http\Controllers;

use App\Album;
use App\Band;
use App\Http\Requests\AlbumRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class AlbumController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index() {
		$user = Auth::user();

		//Get query vars for sorting/pagination
		$params = [
			'per_page' => Input::get( 'per_page' ) ? Input::get( 'per_page' ) : 10,
			'sort'     => Input::get( 'sort' ) ? Input::get( 'sort' ) : 'name',
			'sort_dir' => Input::get( 'sort_dir' ) ? Input::get( 'sort_dir' ) : 'asc',
			'band_id'  => Input::get( 'band_id' ),
		];

		if ( $user->is_admin ) {
			//user is admin
			//get all albums and bands in the system
			$albums = Album::searchBand( $params['band_id'] )
			               ->orderBy( $params['sort'], $params['sort_dir'] )
			               ->paginate( $params['per_page'] );

			$bands = Band::select( 'id', 'name' )
			             ->orderBy( 'name', 'asc' )
			             ->get()
			             ->all();
		} else {
			//user is not an admin
			//only get this user's albums and bands
			$albums = $user->albums()
			               ->orderBy( $params['sort'], $params['sort_dir'] )
			               ->paginate( $params['per_page'] );

			$bands = $user->bands()
			              ->select( 'id', 'name' )
			              ->orderBy( 'name', 'asc' )
			              ->get()
			              ->all();
		}

		return view( 'albums.list' )
			->with( 'albums', $albums )
			->with( 'bands', $bands )
			->with( 'appends', $params );
	}

	/**
	 * Get the form for creating a new resource
	 *
	 * @return View
	 */
	public function create() {
		$user = Auth::user();

		if ( $user->is_admin ) {
			//user is admin
			//get all bands in the system
			$bands = Band::orderBy( 'name', 'asc' )
			             ->get()
			             ->all();
		} else {
			//user is not an admin
			//only get this user's bands
			$bands = $user->bands()
			              ->orderBy( 'name', 'asc' )
			              ->get()
			              ->all();
		}

		return view( 'albums.form' )
			->with('bands', $bands);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param AlbumRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( AlbumRequest $request ) {
		$messages = []; //sends messages back to page

		$band = Band::findOrFail( $request->get( 'band_id' ) );
		$user = Auth::user();

		if ( $user->can( 'update', $band ) ) {
			//user is authenticated to update the band
			//we can add a new album

			$album = new Album;

			$album->fill( $request->all() );

			$album->band()->associate( $band );

			$album->save();

			//Save successful
			//create message to notify user
			array_push( $messages, [
				'type'    => 'success',
				'content' => "You have successfully added the album: $album->name.",
			] );

		} else {

			//Save FAILED because user is not authorized
			array_push( $messages, [
				'type'    => 'danger',
				'content' => 'You are not allowed to create an album for this band.',
			] );

		}

		return redirect( '/albums' )
			->with( 'messages', $messages );

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return View
	 */
	public function show( $id ) {
		$album = Album::findOrFail( $id );
		$user  = Auth::user();

		if ( ! $user->can( 'view', $album ) ) {

			//If user is not authorized to view this album,
			//redirect and show error message
			return redirect( '/albums' )->with( 'messages', [
				[
					'type'    => 'danger',
					'content' => 'You are not allowed to view this album.',
				],
			] );

		}

		return view( 'albums.view' )
			->with( 'album', $album );
	}

	/**
	 * Get the form for editing an existing resource.
	 *
	 * @return View
	 */
	public function edit( $id ) {
		$user = Auth::user();

		$album = Album::findOrFail( $id );

		if ( ! $user->can( 'update', $album ) ) {
			//If user is not allowed to edit this band,
			//redirect and show error message
			return redirect( '/albums' )->with( 'messages', [
				[
					'type'    => 'danger',
					'content' => 'You are not allowed to edit this album.',
				],
			] );
		}

		if ( $user->is_admin ) {
			//user is admin
			//get all bands in the system
			$bands = Band::select( 'name', 'id' )
			             ->orderBy( 'name', 'asc' )
			             ->get()
			             ->all();
		} else {
			//user is not an admin
			//only get this user's bands
			$bands = $user->bands()
			              ->select( 'id', 'name' )
			              ->orderBy( 'name', 'asc' )
			              ->get()
			              ->all();
		}

		return view( 'albums.form' )
			->with('bands', $bands)
			->with('album', $album);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param AlbumRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( AlbumRequest $request, $id ) {
		$messages = []; //use for sending messages to the page

		$album = Album::findOrFail( $id );
		$band  = Band::findOrFail( $request->get( 'band_id' ) );
		$user  = Auth::user();

		if ( $user->can( 'update', $album ) ) {
			//user is authorized to make updates to the album

			$album->fill( $request->all() );

			$album->band()->associate( $band );

			$album->save();

			//album updated
			//prepare success message
			array_push( $messages, [
				'type'    => 'success',
				'content' => "You have successfully updated the album: $album->name.",
			] );

		} else {

			//user is not authorized
			array_push( $messages, [
				'type'    => 'danger',
				'content' => 'You are not allowed to update this album.',
			] );

		}

		return redirect( '/albums' )
			->with( 'messages', $messages );

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		$messages = []; //use for sending messages to the page

		$user = Auth::user();

		$album = Album::findOrFail( $id );

		if ( $user->can( 'delete', $album ) ) {
			//user is authorized to delete the album

			$album->delete();

			//delete successful
			//prepare success message
			array_push( $messages, [
				'type'    => 'success',
				'content' => 'Album deleted.',
			] );

		} else {

			//delete FAILED
			//user is not authorized
			array_push( $messages, [
				'type'    => 'danger',
				'content' => 'You are not allowed to delete this album.',
			] );

		}

		//get current query vars if we are on the list page
		$params = [
			'sort'     => Input::get( 'sort' ) ? Input::get( 'sort' ) : 'name',
			'sort_dir' => Input::get( 'sort_dir' ) ? Input::get( 'sort_dir' ) : 'asc',
			'per_page' => Input::get( 'per_page' ) ? Input::get( 'per_page' ) : 10,
			'page'     => Input::get( 'page' ),
		];

		return redirect( '/albums?' . http_build_query( $params ) )
			->with( 'messages', $messages );
	}
}
