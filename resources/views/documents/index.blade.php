@extends('milestone::layouts.app')

@section('content')
    <documents-board :documents="{{ $documents->toJson() }}"></documents-board>
@endsection