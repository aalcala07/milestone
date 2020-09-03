@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Settings</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    Please fix the errors below.
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <div class="form-group">
                    <label>Timezone</label>
                    @error('timezone')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <select name="timezone" id="timezone" class="form-control">
                        <option value="UTC">UTC</option>
                        @foreach (config('milestone.timezones') as $timezone => $timezoneLabel)
                            <option value="{{ $timezone }}" @if ($timezone === $settings->timezone) selected @endif >{{ $timezoneLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Settings</button>
            </form>

        </div>
    </div>
</div>
@endsection
