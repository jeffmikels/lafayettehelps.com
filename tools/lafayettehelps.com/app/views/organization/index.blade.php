@extends('layout')

@section('content')

	<input class="form-control" name="search" id="searchbox" placeholder="Search" />
	<script type="text/javascript">
	function check()
	{	
		q = 'lafayette';
		url = "{{route('search', array('object_name' => 'organizations'))}}";
		$.ajax({
			url: url,
			dataType: "json",
			type: 'POST',
			data: {
				query: q
			},
			success: function( items ) {
				console.log(items);
				response_list = new Array();
				contact_ids = new Array();
				//if (items.length > 0 ) response_list.push({ label: '-- HOUSEHOLDS ----', value: '' });
				for (i in items)
				{
					label = items[i].name;
					value = items[i].id;
					response_list.push( {
						label: label,
						value: value,
						data: items[i]
					} );
				}
			}
		});		
	}
	$( "#searchbox" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "{{route('search', array('object_name' => 'organizations'))}}",
				dataType: "json",
				type: 'POST',
				data: {
					query: request.term
				},
				success: function( items ) {
					console.log(items);
					response_list = new Array();
					contact_ids = new Array();
					//if (items.length > 0 ) response_list.push({ label: '-- HOUSEHOLDS ----', value: '' });
					for (i in items)
					{
						label = items[i].name;
						value = items[i].id;
						response_list.push( {
							label: label,
							value: value,
							data: items[i]
						} );
					}
					response(response_list);
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			obj = ui.item.data;
			id = obj.id;
			selected = obj;
			// $('#search_row_both').fadeOut('slow').attr('disabled',true);
			// notify('loading data...');
			//$('#household_name').select();

		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	}); // $("#search_box_both")
	$("#search_box_both").select();
	</script>

	<h1>Current Organizations</h1>

	<div class="list-group user-list">
	
		@foreach($organizations as $org)
		<a class="list-group-item" href="{{ action('OrganizationController@showDetail', $org->id); }}">
			@if (isAdmin())
			<span class="badge pull-right">{{$org->status}}</span>
			@endif
			<h2>{{$org->name}}</h2>
		</a>
		@endforeach

	</div>
	
	{{ $organizations->links() }}
@stop
