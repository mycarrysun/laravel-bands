<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'name'             => 'required|string|between:0,255',
	        'recorded_date'    => 'date',
	        'release_date'     => 'date',
	        'number_of_tracks' => 'integer',
	        'label'            => 'string|between:0,255',
	        'producer'         => 'string|between:0,255',
	        'genre'            => 'string|between:0,255',
        ];
    }
}
