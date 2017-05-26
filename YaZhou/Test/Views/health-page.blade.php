<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Amazooka API Health Check Panel</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

</head>
<body>
<style>
    .section{
        height: 80px;
        text-align: center;
        border-radius: 5px;
        line-height:30px;
        padding-top: 10px;
    }

    .dsp_block{
        display:block;
    }
    span.dsp_block{
        font-size: 18px;
    }
    .center{
        text-align: center;
    }
    .disabled {
        z-index: 1000;
        background-color: lightgrey;
        opacity: 0.6;
        pointer-events: none;
    }
    .glyphicon{
        font-size: 20px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="page-header">
                <h1>Amazooka API Health Check Panel</h1>
            </div>
            @foreach ($services as $service)
                @if ($service['massage'] === 'disabled')
                    <div class="col-md-6" data-toggle="tooltip" title="Checking is disabled for this service">
                        <p class="section disabled"><span class="dsp_block">{{$service['name']}}</span>
                            <span class="glyphicon glyphicon-warning-sign "></span>
                        </p>
                    </div>
                @elseif ($service['massage'] === 'success')
                    <div class="col-md-6" data-toggle="tooltip" title="This service is working fine">
                        <p class="section btn-success"><span class="dsp_block">{{$service['name']}}</span>
                            <span class="glyphicon glyphicon-check "></span>
                        </p>
                    </div>
                @else
                    <div class="col-md-6" data-toggle="tooltip" title="{{$service['massage']}}">
                        <p class="section btn-danger"><span class="dsp_block">{{$service['name']}}</span>
                            <span class="glyphicon glyphicon-remove-sign "></span>
                        </p>
                    </div>
                @endif
            @endforeach

            <p class="clearfix"></p>
            <div class="center page-header">
                <h1>Migration Status</h1>
            </div>

            <div class="col-md-2"></div>
            <div class="col-md-8">
                <table class="table-bordered table">
                    <thead class="thead-inverse">
                    <tr>
                        <th class="col-md-10">Migration name</th>
                        <th class="col-md-2">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($migrations as $migration)
                        <tr>
                            <td>{{ $migration['name']}}</td>
                            @if ($migration['code'] === 1)
                                <td class="success">Yes</td>
                            @else
                                <td class="danger">No</td>
                            @endif
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <div class="col-md-2"></div>
            <p class="clearfix"></p>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>

