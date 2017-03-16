@extends('layouts.app')

@section('content')

	<ticket-detail data="{{ json_encode($ticket) }}"></ticket-detail>

@endsection