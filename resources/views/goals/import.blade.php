@extends('milestone::layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3>Import Goals</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please fix the errors below.
                </div>
            @endif
            
            <form method="POST" action="{{ route('goals.storeImport') }}" enctype="multipart/form-data">
                @csrf

                @if ($period)
                    <p><b>Importing to {{ $period->name }}: {{ $period->start_local_friendly }} to {{ $period->end_local_friendly }}</b></p>
                    <input type="hidden" name="period_id" value="{{ $period->id }}">
                @else
                    <input type="hidden" name="selectable_periods" value="true">
                    <div class="form-group">
                        <label>Select a goal period:</label>
                        <select name="period_id">
                            @foreach ($periods as $thisPeriod)
                                <option value="{{ $thisPeriod->id }}" {{ old("period_id") == $thisPeriod->id ? "selected":"" }}>{{ $thisPeriod->name }}: {{ $thisPeriod->start_local_friendly }} to {{ $thisPeriod->end_local_friendly }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group">
                    <label for="csv_file">Select a CSV file:</label>
                    @error('csv_file')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <input name="csv_file" type="file" />
                </div>

                <div class="mb-4">
                    <p>Make sure your CSV file contains each of the following columns in the correct order.</p>
                    <table class="table">
                        <tr>
                            <th>Column</th>
                            <th>Name</th>
                            <th>Acceptable Value</th>
                            <th>Example</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Description</td>
                            <td>Text describing you goal.</td>
                            <td><i>Read 3 books</i></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Completion Type</td>
                            <td>One of the following: "decimal", "integer", or "boolean".</td>
                            <td><i>integer</i></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Completion Value</td>
                            <td>A number corresponding to the value entered for column 2 (leave blank for type "boolean").</td>
                            <td><i>3</i></td>
                        </tr>
                    </table>
                </div>

                <input type="submit" value="Upload and Import Goals" class="btn btn-primary" />
                @if ($period)
                    <a href="{{ route('goals.periods.show', $period->id) }}" class="btn btn-secondary">Go Back to Goal Period {{ $period->id }}</a>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection