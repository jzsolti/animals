@foreach($animals as $animal)
<tr>
	<td>{{ $animal->translatedName }}</td>
	<td>{{ $animal->translatedDescription }}</td>
	<td>
		<a href="{{url('animals/'.$animal->id.'/edit')}}" 
		   class="btn btn-success btn-sm open-form" onclick="return false;">
			@lang('Edit')
		</a>
	</td>
</tr>
@endforeach