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
<title>Pie Chart</title>
<script src="custom/include/jquerystats/Chart.js"></script>
</head>
<body>
<div id="canvas-holder">
<canvas id="chart-area" width="260" height="260"/>
</div>


<script>

var pieData = [
{
value: <?php echo $_REQUEST["r"]-0; ?>,
color:"#37a037",
highlight: "#47b047",
label: "<?php if($language=="en_us") echo "Read"; else echo "Gelesen"; ?>"
},
{
value: <?php echo $_REQUEST["o"]-0; ?>,
color: "#dbd80d",
highlight: "#ebe81d",
label: "<?php if($language=="en_us") echo "Overflown"; else echo "Überflogen"; ?>"
},
{
value: <?php echo $_REQUEST["u"]-0; ?>,
color: "#ab2121",
highlight: "#bb3131",
label: "<?php if($language=="en_us") echo "Not read"; else echo "Nicht gelesen"; ?>"
}

];

window.onload = function(){
var ctx = document.getElementById("chart-area").getContext("2d");
window.myPie = new Chart(ctx).Pie(pieData);
};



</script>
</body>
</html>