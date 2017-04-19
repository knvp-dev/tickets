@extends('layouts.app')

@section('content')

<div class="container is-flex">
	<div class="floating-panel has-text-centered fill-space">
		<h1 class="title has-text-centered is-text-blue is-uppercase">Edit ticket details</h1>

		<form action="/ticket/{{ $ticket->slug }}/update" method="post">
			{{ csrf_field() }}
			<p class="control">
				<label for="title">Title</label>
				<input class="input" type="text" placeholder="title" value="{{ $ticket->title }}" name="title">
			</p>

		<div class="field is-flex">
			<p class="control">
				<label for="category_id">Category</label>
				<span class="select is-small">
					<select name="category_id">
						@foreach($categories as $category)
							<option value="{{ $category->id }}" {{ $ticket->category->id == $category->id ? 'selected=selected' : '' }}>{{ $category->name }}</option>
						@endforeach
					</select>
				</span>
			</p>

			<p class="control">
				<label for="priority_id">Priority</label>
				<span class="select is-small">
					<select name="priority_id">
						@foreach($priorities as $priority)
							<option value="{{ $priority->id }}" {{ $ticket->priority->id == $priority->id ? 'selected=selected' : '' }}>{{ $priority->name }}</option>
						@endforeach
					</select>
				</span>
			</p>
</div>
			<p class="control">
			<label for="description">Description</label>
				<textarea class="textarea" name="description" placeholder="Explain how we can help you">{{ $ticket->description }}</textarea>
			</p>

			<p class="control">
				<button class="button blue-button">Save changes</button>
			</p>

		</form>
	</div>
</div>

@endsection