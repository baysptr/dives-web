<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard DiVes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/4.4/examples/navbar-fixed/navbar-top-fixed.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet">
    <style>
        #container {
            height: 420px;
        }

        .highcharts-figure, .highcharts-data-table table {
            min-width: 350px;
            max-width: 700px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #EBEBEB;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }
        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }
        .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
            padding: 0.5em;
        }
        .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }
        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">DiVes</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_data.php">Manage Data</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Information</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <div class="jumbotron">
        <h1 style="text-align: center">Identification Vehicles (DiVes)</h1><br/>
        <div class="lead">
            <div class="row">
                <div class="col-md-12"><div id="spline"></div><br/></div>
                <div class="col-md-6"><div id="piefs"></div></div>
                <div class="col-md-6"><div id="barfs"></div></div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/modules/pattern-fill.js"></script>
<script src="https://code.highcharts.com/themes/high-contrast-light.js"></script>
<script>
    var datas = $.getJSON("get_data.php", function (data) {
        var series = [];
        for (var i=0;i<data.length;i++){
            // console.log(data[i]['data'][0][0]);
            var row = [];
            row['name'] = data[i]['name'];
            var drow = [];
            for (var j=0;j<data[i]['data'].length; j++){
                var d = new Date(data[i]['data'][j][0]).getTime();
                var v = data[i]['data'][j][1];
                drow.push([d, v]);
            }
            row['data'] = drow;
            series.push(row);
        }
        // console.log(series);
        // ====================================================
        // Draw Line CHart
        // ====================================================
        Highcharts.chart('spline', {
            title: {
                text: 'Intensitas Nopol yang teridentifikasi'
            },
            yAxis: {
                title: {
                    text: 'Intensitas'
                },
                allowDecimals: false,
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        Highcharts.dateFormat('%e %b', this.x)  + ': <strong>' + this.y + 'x</strong> teridentifikasi';
                },
                borderRadius: 10,
                borderWidth: 3,
                crosshairs: [true]
            },
            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    },
                }
            },
            xAxis: {
                type: 'datetime',
                labels: {
                    formatter: function() {
                        var monthStr = Highcharts.dateFormat('%e %b', this.value);
                        // var firstLetter = monthStr.substring(0, 1);
                        return monthStr;
                    }
                },
            },
            series: series
        });
    });
</script>
<script>
    function getColorPattern(i) {
        var colors = Highcharts.getOptions().colors,
            patternColors = [colors[2], colors[0], colors[3], colors[1], colors[4]],
            patterns = [
                'M 0 0 L 5 5 M 4.5 -0.5 L 5.5 0.5 M -0.5 4.5 L 0.5 5.5',
                'M 0 5 L 5 0 M -0.5 0.5 L 0.5 -0.5 M 4.5 5.5 L 5.5 4.5',
                'M 1.5 0 L 1.5 5 M 4 0 L 4 5',
                'M 0 1.5 L 5 1.5 M 0 4 L 5 4',
                'M 0 1.5 L 2.5 1.5 L 2.5 0 M 2.5 5 L 2.5 3.5 L 5 3.5'
            ];

        return {
            pattern: {
                path: patterns[i],
                color: patternColors[i],
                width: 5,
                height: 5
            }
        };
    }
    var datas = $.getJSON("data_pie.php", function (data) {
        // console.log(data);
        var series = [];
        var names = [];
        for(var i=0;i<data.length;i++){
            var row = {name: data[i].name, color: getColorPattern(i), y: data[i].y};
            names.push(data[i].name);
            series.push(row);
        }
        console.log(series);
        // ====================================================
        // Draw Pie CHart
        // ====================================================
        Highcharts.chart('piefs', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Persentase Intensitas Nopol Teridentifikasi'
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.point.name + '</b><br/>' +
                        '<strong>' + (this.point.percentage).toFixed(2) + '%</strong> teridentifikasi';
                },
                borderRadius: 10,
                borderWidth: 3,
                crosshairs: [true]
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        connectorColor: '#777',
                        format: '<b>{point.name}</b>: {point.percentage:.2f} %'
                    },
                    cursor: 'pointer',
                    borderWidth: 3
                }
            },
            series: [{
                name: 'Intensitas',
                data: series
            }]
        });
        // ======================================================
        // Draw Bar Chart
        // ======================================================
        Highcharts.chart('barfs', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Bagan Mean Nopol Teridentifikasi'
            },
            tooltip: {
                valueSuffix: 'x',
                // borderColor: '#8ae'
                borderRadius: 10,
                borderWidth: 3
            },
            xAxis: {
                categories: names,
                allowDecimals: false
            },
            yAxis: {
                title: {
                    text: 'Intensitas'
                },
                allowDecimals: false,
            },
            legend: {
                enabled: false,
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        connectorColor: '#777',
                        format: '<b>{point.name}</b> {point.1f}.'
                    },
                    borderWidth: 3
                }
            },
            series: [{
                name: 'Intensitas',
                data: series
            }]
        });
    });
</script>
</body>
</html>
