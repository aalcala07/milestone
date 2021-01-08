@extends('milestone::layouts.app')

@section('content')
    <documents-board :groups="{{ $groups->toJson() }}" :start-ui="{{ $ui }}"></documents-board>
@endsection