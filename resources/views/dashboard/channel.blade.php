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

        .channel .panel-heading{
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div id="chart" class="col-xs-12" style="height: 400px; margin: 0 auto"></div>
    </div>
    <div class="row" style="margin-top:30px">
        <div class="clearfix">
            <div id="pie-1" class="col-xs-6" style="height: 400px; margin: 0 auto"></div>
            <div id="pie-2" class="col-xs-6" style="height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Logs ({{ $logs->total() }}) </h2>
            <div class="channel panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="pull-left">
                        <h4>
                            {{ $channel->name }}
                        </h4>
                    </div>
                    <div class="pull-right clearfix">
                        <label class="pull-left">TMS:</label> 
                        <label class="pull-left label label-danger number">{{ number_format($tms) }}</label>
                        <label class="pull-left label label-success number">${{ $channel->calculateUSD($tms) }}</label>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <th>Title</th>
                            <th>MS</th>
                            <th>Date</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach($logs as $log) 
                               <tr class="log-entry" data-meta='{!! json_encode($log->meta) !!}'>
                                    <td class="col-xs-8">
                                        <a href="{{ route('channel', $channel->_id) }}?title={{ $log->title }}">
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
                                    <td><button class="btn btn-success btn-xs" onclick="openMeta(this)"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></button></td>
                               </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    {!! $logs->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div id="details" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <th>Name</th>
                        <th>Value</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        stats = {!! json_encode($stats) !!};

        formatSeriesForChart = function(stats) {
            var series = {};
            
            for (var key in stats) {
                var channel = stats[key]._id.split("|")[1];
                var date = stats[key]._id.split("|")[0];
                if(!series[channel]) {
                    series[channel] = {};
                }

                series[channel][date] = stats[key].total; 
            }

            var tmp = [];

            for (var key in series) {
                var categories = Object.keys(series[key]).sort();
                tmp.push({
                    name: key,
                    data: categories.map(function(item) { return series[key][item] })
                })
            }

            return {
                categories: Object.keys(series[key]).sort(),
                series: tmp
            }
        }

        formatSeriesForPie1 = function(stats) {
            var series = {};
            
            for (var key in stats) {
                var channel = stats[key]._id.split("|")[1];
                var date = stats[key]._id.split("|")[0];
                if(!series[channel]) {
                    series[channel] = 0;
                }

                series[channel] += stats[key].total; 
            }

            var tmp = [];

            for(var key in series) {
                tmp.push({
                    name: key,
                    y: series[key] 
                });
            }


            return tmp;
        }

        formatSeriesForPie2 = function(stats) {
            var series = {};
            
            for (var key in stats) {
                var channel = stats[key]._id.split("|")[1];
                var date = stats[key]._id.split("|")[0];
                if(!series[channel]) {
                    series[channel] = 0;
                }

                series[channel] += stats[key].count; 
            }

            var tmp = [];

            for(var key in series) {
                tmp.push({
                    name: key,
                    y: series[key] 
                });
            }


            return tmp;
        }

        var data1 = formatSeriesForChart(stats);

        Highcharts.chart('chart', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Daily Monetary Score for all titles'
            },
            xAxis: {
                categories: data1.categories
            },
            yAxis: {
                title: {
                    text: 'Monetary Score'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: data1.series,
            credits: {
                enabled: false
            }
        });

        var data2 = formatSeriesForPie1(stats);

        Highcharts.chart('pie-1', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Monetary Score Distribution by titles'
            },
            tooltip: {
                pointFormat: 'TMS: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Titles',
                colorByPoint: true,
                data: formatSeriesForPie1(stats)
            }],
            credits: {
                enabled: false
            }
        });

        var data3 = formatSeriesForPie2(stats);

        Highcharts.chart('pie-2', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Number of log entries'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Titles',
                colorByPoint: true,
                data: formatSeriesForPie2(stats)
            }],
            credits: {
                enabled: false
            }
        });


        function openMeta(btn) {
            var meta = $(btn).parent().parent().data('meta');
            $("#details tbody").html("");
            $("#details h4.modal-title").html($(btn).parent().parent().find('td:first-child a').html());
            for(var key in meta) {
                $("#details tbody").append("<tr><td>"+key+"</td><td>"+meta[key]+"</td></tr>");
            }

            $("#details").modal();
        }
    </script>
@endsection
