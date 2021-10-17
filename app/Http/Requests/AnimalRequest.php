<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnimalRequest extends FormRequest {

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
			'name.*' => 'required|max:255',
			'description.*' => 'required|max:2500',
		];
	}

	public function messages()
	{
		return [
			'name.*.required' => __('validation.required', ['attribute' => __('validation.attributes.name')]),
			'name.*.max' => __('validation.max', ['attribute' => __('validation.attributes.name')]),
			'description.*.required' => __('validation.required', ['attribute' => __('validation.attributes.description')]),
			'description.*.max' => __('validation.max', ['attribute' => __('validation.attributes.description')]),
		];
	}

}
