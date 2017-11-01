<?php

/*********************************************************************************
*
* DO NOT MODIFY THIS FILE!
*
* This file is part of KINAMU EvalancheConnector, an enhanced module for Sugar (TM). 
* Sugar (TM) is developed by SugarCRM Inc. (C).
* 
* KINAMU EvalancheConnector is licensed software and may only be used in alignment with the 
* End User License Agreement (EULA) received with this software. This software is copyrighted 
* and may not be further distributed without any written consent of 
* KINAMU Business Solutions GmbH (C).
* 
* KINAMU EvalancheConnector: Copyright (C) 2016 by KINAMU Business Solutions GmbH. All rights reserved.
*
* You can contact KINAMU Business Solutions GmbH via email at office@kinamu.com
*
********************************************************************************/
?>
<!doctype html>
<html>
<head>
<title>Line Chart</title>
<script src="custom/include/jquerystats/Chart.js"></script>
</head>
<body>
<?php
$_REQUEST['id'] = preg_replace('/[^0-9^A-Z^a-z^\-]/','',$_REQUEST['id']); $queryResult = $GLOBALS['db']->query("select linechart_6hrs from k_evacampaigns where id = '".$_REQUEST['id']."'"); $aRow = $GLOBALS['db']->fetchByAssoc($queryResult); $dataArray = unserialize(str_replace("&quot;",'"',$aRow['linechart_6hrs'])); ?>
<div style="width:98%">
<div>
<canvas id="canvas" height="430" width="600"></canvas>
</div>
</div>
<?php
$replaceFrom = Array("&Ouml;ffnungen","Öffnungen",utf8_encode("Öffnungen"),"Klicks","Abmeldungen"); $replaceTo = Array("Openers","Openers","Openers","Clicks","Unsubscriptions"); for($i=0; $i<count($dataArray["names"]) ;$i++) { if($language == "en_us") { $dataArray["names"][$i] = str_replace($replaceFrom,$replaceTo,$dataArray["names"][$i]); } echo "<strong style=\"color:rgb(".$dataArray["colors"][$i].");width:".floor(100/count($dataArray["names"]))."%;display:inline-block;text-align:center;\">"; echo array_sum($dataArray["data"][$i])."&nbsp;".$dataArray["names"][$i]."</strong>"; } ?>

<script>
var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
var lineChartData = {
labels : ["<?php echo implode('","',$dataArray["labels"]); ?>"],
datasets : [
<?php
for($i=0; $i<count($dataArray["names"]) ;$i++) { if($i>0) echo ","; ?>
{
label: "<?php echo $dataArray["names"][$i]; ?>",
fillColor : "rgba(<?php echo $dataArray["colors"][$i]; ?>,0.2)",
strokeColor : "rgba(<?php echo $dataArray["colors"][$i]; ?>,1)",
pointColor : "rgba(<?php echo $dataArray["colors"][$i]; ?>,1)",
pointStrokeColor : "#fff",
pointHighlightFill : "#fff",
pointHighlightStroke : "rgba(<?php echo $dataArray["colors"][$i]; ?>,1)",
data : [<?php echo implode(',',$dataArray["data"][$i]); ?>]
}
<?php
} ?>
]

}

window.onload = function(){
var ctx = document.getElementById("canvas").getContext("2d");
window.myLine = new Chart(ctx).Line(lineChartData, {
responsive: true
});
}
</script>
</body>
</html>