@extends('layout')

@section('content')
<div class="card" >
	<div class="card-header d-flex justify-content-between">
		<h5 class="card-title">@lang('Animals')</h5> 
		<a href="{{url('animals/create')}}" class="btn btn-primary open-form"  onclick="return false;">@lang('Create')</a>
	</div>
	<div class="card-body">
		<form method="POST" action="{{url('/animals/table')}}" class="form-inline" id="search-form">
			@csrf
			<div class="form-group mx-sm-3 mb-2">
				<input type="text" name="name" class="form-control" placeholder="@lang('Name')">
			</div>
			<button type="submit" class="btn btn-secondary mb-2">@lang('Search')</button>
		</form>
	</div>
	<table class="table" id="animals-tbl">
		<thead>
			<tr>
				<th scope="col"> @lang('Name')</th>
				<th scope="col"> @lang('Description')</th>
				<th scope="col"> </th>
			</tr>
		</thead>
		<tbody><tr><td>Loading...</td></tr></tbody>
	</table>
</div>
<div class="modal fade" id="animalFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>
@endsection

@section('script')
<script>
    function loadTableContent() {
        $("#animals-tbl tbody").load('/animals/table');
    }

    $(document).on('click', 'a.open-form', function (event) {
        event.preventDefault();

        $("#animalFormModal .modal-content").load($(this).attr('href'), function () {
            $('#animalFormModal').modal('show');
        });
    }).on('submit', 'form.animal-form',  (event) =>  {
        event.preventDefault();

        let ajaxForm = new AjaxForm({element: event.currentTarget});
        ajaxForm.jqXHR().done((response) => {
            $("#animalFormModal .modal-content").html(`<div class="alert alert-success" role="alert">${response.alert}</div>`);
            loadTableContent();
            setTimeout(function () {
                $('#animalFormModal').modal('hide');
            }, 2000);
        });
    }).on('submit', '#search-form',  (event) =>  {
        event.preventDefault();
		
        let searchForm = new AjaxForm({element: event.currentTarget});
        searchForm.jqXHR().done((response) => {
            $("#animals-tbl tbody").html(response);
        });
		
    }).ready(function () {
        loadTableContent();
    });
	
	
</script>
@endsection

