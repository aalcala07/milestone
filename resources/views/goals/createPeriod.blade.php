@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Create Goal Period</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        Please fix the errors below.
                    </div>
                @endif

                <form method="POST" action="{{ route('goals.periods.store') }}">
                    @csrf

                    <div class="form-group">
                        <label>Name</label>
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    </div>

                    <div class="form-group">
                        <label>Start Date</label>
                        @error('start')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <select name="start" class="form-control @error('start') is-invalid @enderror">
                            @foreach ($startDates as $startDate)
                                <option value="{{ $startDate['value'] }}" {{ old("start") == $startDate['value'] ? "selected":"" }}>{{ $startDate['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        @error('end')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <select name="end" class="form-control @error('end') is-invalid @enderror">
                            @foreach ($endDates as $endDate)
                                <option value="{{ $endDate['value'] }}" {{ old("start") == $endDate['value'] ? "selected":"" }}>{{ $endDate['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Create Goal Period</button>
                </form>
            </div>
        </div>
    </div>
@endsection