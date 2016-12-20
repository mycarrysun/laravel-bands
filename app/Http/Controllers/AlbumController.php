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
		$user     = Auth::user();
		$per_page = Input::get( 'per_page' ) ? Input::get( 'per_page' ) : 10;
		$sort_by  = Input::get( 'sort' ) ? Input::get( 'sort' ) : 'name';
		$sort_dir = Input::get( 'sort_dir' ) ? Input::get( 'sort_dir' ) : 'asc';
		$band_id  = Input::get( 'band_id' );

		if ( $user->is_admin ) {
			//user is admin
			//get all albums in the system
			$albums = Album::searchBand( $band_id )
			               ->orderBy( $sort_by, $sort_dir )
			               ->paginate( $per_page );

			$bands = Band::orderBy( 'name', 'asc' )
			             ->get()
			             ->all();
		} else {
			//user is not an admin
			//only get this user's albums
			$albums = $user->albums()
			               ->orderBy( $sort_by, $sort_dir )
			               ->paginate( $per_page );

			$bands = $user->bands()
			              ->orderBy( 'name', 'asc' )
			              ->get()
			              ->all();
		}

		$data = [
			'albums'  => $albums,
			'bands' => $bands,
			'appends' => [
				'per_page' => $per_page,
				'sort'     => $sort_by,
				'sort_dir' => $sort_dir,
				'band_id'  => $band_id,
			],
		];

		return view( 'albums.list', $data );
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

		$data = [
			'bands' => $bands,
		];

		return view( 'albums.form', $data );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param AlbumRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( AlbumRequest $request ) {
		$messages = [];

		$band = Band::findOrFail( $request->get( 'band_id' ) );
		$user = Auth::user();

		if ( $user->can( 'update', $band ) ) {
			$album = new Album;

			$album->fill( $request->all() );

			$album->band()->associate( $band );

			$album->save();

			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => "You have successfully added the album: $album->name.",
				],
				'created' => $album,
			] );
		} else {

			return response()->json( [
				'message' => [
					'type'    => 'danger',
					'content' => 'You are not allowed to create an album for this band.',
				],
			], 401 );
		}


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

			return redirect('/albums')->with('messages', [
				[
					'type' => 'danger',
					'content' => 'You are not allowed to view this album.'
				]
			]);

		}

		return view('albums.view')->with('album', $album);
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
			//user not allowed to edit this band
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

		$data = [
			'bands' => $bands,
			'album' => $album,
		];

		return view( 'albums.form', $data );
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
		$album = Album::findOrFail( $id );
		$band  = Band::findOrFail( $request->get( 'band_id' ) );
		$user  = Auth::user();

		if ( $user->can( 'update', $album ) && $user->can( 'update', $band ) ) {

			$album->fill( $request->all() );

			$album->band()->associate( $band );

			$album->save();

			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => "You have successfully updated the album: $album->name.",
				],
				'updated' => $album,
			] );

		} else {

			return response()->json( [
				'message' => [
					'type'    => 'danger',
					'content' => 'You are not allowed to update this album.',
				],
			], 401 );

		}


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

		$user = Auth::user();

		$album = Album::findOrFail( $id );

		if ( $user->can( 'delete', $album ) ) {

			$album->delete();

			array_push( $messages, [
				'type'    => 'success',
				'content' => 'Album deleted.',
			] );

		} else {

			array_push( $messages, [
				'type'    => 'danger',
				'content' => 'You are not allowed to delete this album.',
			] );

		}

		return redirect( '/albums' )->with( 'messages', $messages );
	}
}
