@extends('layouts.app')

@section('css')
    <style type="text/css">
        .panel .panel-heading .pull-right label:first-child {
            margin-top: 10px;
        }

        .panel .panel-heading .pull-right label.number {
            margin-top: 4px;
            margin-left: 10px;
            padding: 10px;
            font-size: 14px;
        }

        .panel .panel-heading > .pull-right {
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Channels ({{ $channels->total() }}) </h2>
            
            @if(count($channels) == 0)
                <div class="panel panel-danger text-center" style="margin-top:100px">
                    <div class="panel-heading clearfix">
                        Whoops, looks like there are no channels in your account:(
                    </div>
                    <div class="panel-body">
                        <p>
                            Channels are created automatically when you start sending the logs.<br>
                            If you are just testing the application, you can run <label class="label label-success">php artisan test:run</label> to play with test data
                        </p>
                    </div>
                </div>
            @endif
            
            @foreach($channels as $channel) 
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left">
                            <h4>
                                {{ $channel->name }} ({{ $channel->logs()->count() }})
                            </h4>
                        </div>
                        <div class="pull-right clearfix">
                            <label class="pull-left">TMS:</label> 
                            <label class="pull-left label label-danger number">{{ number_format($channel->tms) }}</label>
                            <label class="pull-left label label-success number">${{ $channel->calculateUSD() }}</label>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <th>Title</th>
                                <th>MS</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                @foreach($channel->someLogs as $log) 
                                   <tr>
                                        <td class="col-xs-8">
                                            <a href="{{ route('log', $log->id) }}">
                                                {{ $log->title }}
                                            </a>
                                        </td>
                                        <td class="col-xs-2">
                                            <label class="label label-danger">
                                                {{ number_format($log->ms) }}
                                            </label>
                                        </td>
                                        <td class="col-xs-2">
                                            {{ $log->created_at->diffForHumans() }}
                                        </td>
                                   </tr> 
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            {!! $channels->links() !!}
        </div>
    </div>
</div>
@endsection