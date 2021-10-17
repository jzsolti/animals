<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Adatbázis szerkezet többnyelvű oldalak megoldására
## Migration
JSON típusú mezők létrehozása

```php
Schema::create('animals', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->json('name');
    $table->json('description');
    $table->timestamps();
});
```

## Model
### [Laravel attribute casting](https://laravel.com/docs/6.x/eloquent-mutators#attribute-casting) 
A JSON típusú mezők ha array attribute casting-ot kapnak akkor tömböt várnak  és tömböt adnak vissza lekérdezéskor.
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    // attribute casting
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];
    
    public function getTranslatedNameAttribute()
    {
    	return $this->name[ app()->getLocale()];
    }
    
    public function getTranslatedDescriptionAttribute()
    {
    	return $this->description[ app()->getLocale()];
    }
}
```

## Config
### /config/langs.php
```php
<?php

return array('hu'=>'hu','en'=>'en');
```

## Form view
Tömbnek kell lenniük mert a model azt vár.
```html
@foreach(config('langs') as $language)
<div class="form-group">
    <label for="">@lang('Name') - {{$language}}</label>
	{{ Form::text("name[$language]", null, ["class"=>"form-control"]) }}
</div>
<div class="form-group">
    <label for="">@lang('Description')- {{$language}}</label>
	{{ Form::text("description[$language]", null, ["class"=>"form-control"]) }}
</div>
@endforeach
```

## Validation
```php
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
```

## Controller create
Mentésnél nincs semmi plusz feladat, ugyanúgy járunk el ahogy 
a többi mezőtípus mentésénél, mivel az attribute casting-nak köszönhetően 
a request-ből kapott tömb automatikusan átalakul json-á.
```php
    public function store(AnimalRequest $request)
    {
        $animal = new Animal;
        $animal->name = $request->name;
        $animal->description = $request->description;
        $animal->save();
        return response()->json(['alert' => 'OK']);
    }
```

## Szűrés lekérdezés
A Laravel query builder is támogatja már a json típusú megoldásokat
```php
$locale = app()->getLocale();
$animals = Animal::orderBy('name->'.$locale,'asc');

if ($request->isMethod('post') && !empty($request->input('name')) ) {
    $animals->where('name->'.$locale, 'like', $request->name.'%');
}
```

## View megjelenítés
A model mutator metódusait használjuk használjuk, lásd fentebb a model-ben.
```php
{{ $animal->translatedName }}
{{ $animal->translatedDescription }}
```