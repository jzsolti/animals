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
