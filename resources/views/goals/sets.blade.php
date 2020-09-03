@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                
                    <div class="card-header">
                        <h3>@if ($showOne) Selected Set @else Current Set @endif</h3>
                        @if ($set)
                            {{ $set->start_local_friendly }} to {{ $set->end_local_friendly }}
                        @endif
                    </div>
                    <div class="card-body">
                        @if (!$set)
                            You don't have any goal sets.
                        @else
                        <form method="POST" action="{{ route('goalSets.update') }}" class="mb-5">
                            @csrf

                            
                            <table class="goal-set-table table table-borderless">
                                @foreach ($set->goals as $goal)
                                <tr>
                                    <td>{{ $goal->goal->description }}</td>
                                    @if ($goal->goal->completion_type === 'boolean')
                                        <td>
                                            <input name="goals[{{ $goal->id }}]" type="checkbox" @if ($goal->complete) checked @endif value="1">
                                        </td>
                                    @elseif ($goal->goal->completion_type === 'integer')
                                        <td>
                                            <input name="goals[{{ $goal->id }}]" value="{{ intval($goal->progress_value) ?? 0 }}" type="number" step="1" min="0"> / {{ intval($goal->goal->completion_value) }}
                                        </td>
                                    @else
                                        <td>
                                            <input name="goals[{{ $goal->id }}]" value="{{ $goal->progress_value ?? 0 }}" type="number" step="0.01" min="0"> / {{ $goal->goal->completion_value }}
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </table>

                            <button type="submit" class="btn btn-success">Save Changes</button>

                        </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3>@if ($showOne) Other Sets @else Previous Sets @endif</h3>
                    </div>
                    <div class="card-body">
                    @if (!$otherSets->count())
                        You have no @if ($showOne) other @else past @endif goal sets.
                    @else
                        <table class="table table-borderless">
                        @foreach ($otherSets as $otherSet)
                            <tr>
                                <td>{{ $otherSet->start_local_friendly }}</td>
                                <td>14/15</td>
                                <td>
                                    <a href="{{ route('goalSets', $otherSet->id) }}">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    @endif
                    </div>
                </div>
                
            </div>

        </div>
            
    </div>
@endsection