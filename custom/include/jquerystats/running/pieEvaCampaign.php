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
color:"#F7464A",
highlight: "#FF5A5E",
label: "<?php if($language=="en_us") echo "Recipients"; else echo "Empfänger"; ?>"
},
{
value: <?php echo $_REQUEST["i"]-0; ?>,
color: "#33dd33",
highlight: "#53fe53",
label: "<?php if($language=="en_us") echo "Opened"; else echo "Geöffnet"; ?>"
},
{
value: <?php echo $_REQUEST["c"]-0; ?>,
color: "#46BFBD",
highlight: "#5AD3D1",
label: "<?php if($language=="en_us") echo "Clicks"; else echo "Klicks"; ?>"
},
{
value: <?php echo $_REQUEST["d"]-0; ?>,
color: "rgb(195,132,97)",
highlight: "rgb(215,152,117)",
label: "<?php if($language=="en_us") echo "Doublets"; else echo "Dubletten"; ?>"
},
{
value: <?php echo $_REQUEST["s"]-0; ?>,
color: "rgb(188,122,122)",
highlight: "rgb(208,142,142)",
label: "Softbounces"
},
{
value: <?php echo $_REQUEST["h"]-0; ?>,
color: "rgb(136,68,68)",
highlight: "rgb(156,88,88)",
label: "Hardbounces"
},
{
value: <?php echo $_REQUEST["rob"]-0; ?>,
color: "rgb(9,77,219)",
highlight: "rgb(29,97,239)",
label: "Robinsonlisted"
},
{
value: <?php echo $_REQUEST["b"]-0; ?>,
color: "rgb(0,0,0)",
highlight: "rgb(50,50,50)",
label: "Blacklisted"
},
{
value: <?php echo $_REQUEST["a"]-0; ?>,
color: "#4D5360",
highlight: "#616774",
label: "<?php if($language=="en_us") echo "Unsubscriptions"; else echo "Abmeldungen"; ?>"
}
];

window.onload = function(){
var ctx = document.getElementById("chart-area").getContext("2d");
window.myPie = new Chart(ctx).Pie(pieData);
};
</script>
</body>
</html>