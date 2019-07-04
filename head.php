<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo $siteTitle; ?> | $Study Log()</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript" ></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <!-- スクリプト部分 -->
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["columnchart"]});
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var dataArray = [['Age','Weight']];
            var df = $.Deferred();

            $(function() {
                $.ajax({
                    url: 'man.json',
                    dataType : 'json',
                }).done(function(data){
                    console.log("success");
                    $(data.man).each(function(){
                        var data_item = [this.age,this.weight];
                        dataArray.push(data_item);
                    });
                    df.resolve();
                }).fail(function(){
                    console.log("error");
                });
            });

            df.done(function(){
                var chartdata = google.visualization.arrayToDataTable(dataArray);

                var options = {
                    title: 'Age vs. Weight comparison',
                    hAxis: {title: 'Age', minValue: 0, maxValue: 15},
                    vAxis: {title: 'Weight', minValue: 0, maxValue: 15},
                    legend: 'none'
                };
                var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
                chart.draw(chartdata, options);
            });
        }
    </script> 
    </head>
    
    
    
</head>
