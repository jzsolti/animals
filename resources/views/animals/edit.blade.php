{{Form::model($animal,['url'=> url('animals/'.$animal->id),'method'=>'PUT', 'class' => 'animal-form']) }}
	<div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Edit animal</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
		@include('animals._form')
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
{{ Form::close() }}