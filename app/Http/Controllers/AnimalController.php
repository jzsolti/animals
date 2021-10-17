<?php

namespace App\Http\Controllers;

use App\Animal;
use Illuminate\Http\Request;
use App\Http\Requests\AnimalRequest;

class AnimalController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('animals.index');
	}

	public function table(Request $request)
	{
		$locale = app()->getLocale();
		$animals = Animal::orderBy('name->'.$locale,'asc');
		if ($request->isMethod('post') && !empty($request->input('name')) ) {
			$animals->where('name->'.$locale, 'like', $request->name.'%');
		}
		
		return view('animals._tbody', ['animals' => $animals->get()]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('animals.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(AnimalRequest $request)
	{
		$animal = new Animal;
		$animal->name = $request->name;
		$animal->description = $request->description;
		$animal->save();
		return response()->json(['alert' => 'OK']);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Animal  $animal
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Animal $animal)
	{
		return view('animals.edit', ['animal' => $animal]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Animal  $animal
	 * @return \Illuminate\Http\Response
	 */
	public function update(AnimalRequest $request, Animal $animal)
	{
		$animal->name = $request->name;
		$animal->description = $request->description;
		$animal->save();
		return response()->json(['alert' => 'OK']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Animal  $animal
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Animal $animal)
	{
		//
	}

}
