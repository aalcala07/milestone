@extends('milestone::layouts.app')

@section('content')
    <documents-board :groups="{{ $groups->toJson() }}"></documents-board>
@endsection