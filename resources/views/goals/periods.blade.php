@extends('milestone::layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                    <h5 class="card-header">
                        Period
                    </h5>
                    <div class="card-body">

                        @if (!$period)
                            You don't have any goal periods
                        @else
                            <div class="d-flex">
                                <div>
                                    <h3>{{ $period->name }}</h3>
                                    <b>{{ $period->start_local_friendly }}</b> to <b>{{ $period->end_local_friendly }}</b>
                                </div>
                                <div class="ml-auto d-flex align-items-center">
                                    <div class="goal-period-stat mr-5">
                                        <span class="goal-period-points-number">{{ $stats['total_points'] }}/{{ $stats['total_points_possible'] }}</span><br>
                                        <span class="goal-period-points-text">points</span>
                                    </div>
                                    <div class="goal-period-stat goal-period-percentage-stat">{{ $stats['percent_complete'] }}%</div>
                                </div>
                            </div>
                            <hr>

                            @if (!$goals->count())
                                <p>No goals in this period.</p>
                            @else
                                <table class="table table-borderless">
                                @foreach ($goals as $goal)
                                    <tr>
                                        <td>{{ $goal['description'] }}</td>
                                        <td align="right">{{ $goal['points'] }} points</td>
                                    </tr>
                                @endforeach
                                </table>
                            @endif
                            <a href="{{ route('goals.import', ['period' => $period->id]) }}" class="btn btn-sm btn-primary">Import Goals</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <h5 class="card-header">
                        Other Periods
                    </h5>
                    <div class="card-body">
                        @if (!count($otherPeriods))
                            <p>There are no other goal periods.</p>
                        @else
                            <table class="table table-borderless">
                            @foreach ($otherPeriods as $otherPeriod)
                                <tr>
                                    <td>{{ $otherPeriod->start_local_friendly }} - {{ $otherPeriod->end_local_friendly }}<br>
                                    {{ $otherPeriod->name }}</td>
                                    <td>
                                        <a href="{{ route('goals.periods.show', $otherPeriod->id) }}">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        @endif
                        <a href="{{ route('goals.periods.create') }}" class="btn btn-sm btn-primary">Create Goal Period</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection