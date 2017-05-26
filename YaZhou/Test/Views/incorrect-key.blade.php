<html>
<head>
    <title>Health page</title>

    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #0b0c0c;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
            margin-bottom: 40px;
        }

        .quote {
            font-size: 24px;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        @if ($responseCode === 200)
            <div class="title  alert-success">Ok )))</div>
            <div class="quote">Response code is {{$responseCode}}.</div>
        @else
            <div class="title  alert-danger">Ops filed :(((</div>
            <div class="quote">Response code is {{$responseCode}}.</div>
        @endif

    </div>
</div>
</body>
</html>
