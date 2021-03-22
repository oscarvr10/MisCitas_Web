@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Médicos más activos</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="input-daterange datepicker row align-items-center" data-date-format="yyyy-mm-dd">
            <div class="col">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input id="startDate" class="form-control" placeholder="Fecha de Inicio" type="text" value="{{ $start }}">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input id="endDate" class="form-control" placeholder="Fecha de Fin" type="text" value="{{ $end }}">
                    </div>
                </div>
            </div>
        </div>
        <div id="container">

        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
    const chart = Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Médicos más activos'
        },
        subtitle: {
            text: 'Médicos con más citas atendidas por mes'
        },
        xAxis: {
            categories: [],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'N° de Citas'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        series: []
    });

    let $start, $end;

    function fetchData() {
        const startDate = $start.val();
        const endDate = $end.val();

        const url = `/charts/appointments/column/data?start=${startDate}&end=${endDate}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                //console.log(jsonResponse);
                chart.xAxis[0].setCategories(data.categories);

                if (chart.series.length > 0) {
                    chart.series[1].remove();
                    chart.series[0].remove();
                }
                
                chart.addSeries(data.series[0]); // attended appointments
                chart.addSeries(data.series[1]); // cancelled appointments
            });
    }

    $(document).ready(() => {
        $start = $('#startDate');
        $end = $('#endDate');

        fetchData();
        $start.change(fetchData);
        $end.change(fetchData);
    });
    </script>
@endsection