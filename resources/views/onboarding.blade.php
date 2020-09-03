@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Welcome to Milestone!</h1>

            <p>Create your first goal period and add goals to get started.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please fix the errors below.
                </div>
            @endif

            <form method="POST" action="{{ route('onboarding.store') }}">
                @csrf

                <h4>Goal Period</h4>

                <p>Choose the dates to start and end your goals.</p>
                
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
                            <option value="{{ $endDate['value'] }}" {{ old("end") == $endDate['value'] ? "selected":"" }}>{{ $endDate['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <h4>Goals</h4>

                <p>Add some initial goals. You can add more later.</p>

                @error('goals')
                    <p class="text-danger">{{ $message }}</p>
                @enderror

                <table class="table table-borderless">
                    <tr>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Completion Value</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="goals[0][description]" class="form-control">
                        </td>
                        <td>
                            <select class="form-control" name="goals[0][completion_type]">
                                <option value="boolean">Boolean</option>
                                <option value="integer">Integer</option>
                                <option value="decimal">Decimal</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="goals[0][completion_value]" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="goals[1][description]" class="form-control">
                        </td>
                        <td>
                            <select class="form-control" name="goals[1][completion_type]">
                                <option value="boolean">Boolean</option>
                                <option value="integer">Integer</option>
                                <option value="decimal">Decimal</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="goals[1][completion_value]" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="goals[2][description]" class="form-control">
                        </td>
                        <td>
                            <select class="form-control" name="goals[2][completion_type]">
                                <option value="boolean">Boolean</option>
                                <option value="integer">Integer</option>
                                <option value="decimal">Decimal</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="goals[2][completion_value]" class="form-control">
                        </td>
                    </tr>

                </table>

                <button type="submit" class="btn btn-success">Next</button>
            </form>

        </div>
    </div>
</div>
@endsection
