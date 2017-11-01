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


 require_once('include/MVC/View/views/view.list.php'); class CustomK_EvaCampaignsViewList extends ViewList { function display() { $eohtml = <<<EOHTML
<style type="text/css">
        .modalDialog {
                position: fixed;
                font-family: Arial, Helvetica, sans-serif;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: rgba(0,0,0,0.9);
                z-index: 99999;
                opacity:0;
                -webkit-transition: opacity 400ms ease-in;
                -moz-transition: opacity 400ms ease-in;
                transition: opacity 400ms ease-in;
                pointer-events: none;
        }
 
        .modalDialog:target {
                opacity:1;
                pointer-events: auto;
        }
 
        .modalDialog > div {
                width: 300px;
                position: relative;
                margin: 10% auto;
                padding: 5px 20px 13px 20px;
                border-radius: 10px;
                text-align: center;
                background: #fff;
                background: -moz-linear-gradient(#fff, #999);
                background: -webkit-linear-gradient(#fff, #999);
                background: -o-linear-gradient(#fff, #999);
        }
 
        .close {
                background: rgb(204,0,0);
                color: #FFFFFF;
                line-height: 25px;
                position: absolute;
                right: -12px;
                text-align: center;
                top: -10px;
                width: 24px;
                text-decoration: none;
                font-weight: bold;
                -webkit-border-radius: 12px;
                -moz-border-radius: 12px;
                border-radius: 12px;
                -moz-box-shadow: 1px 1px 3px #000;
                -webkit-box-shadow: 1px 1px 3px #000;
                box-shadow: 1px 1px 3px #000;
        }
 
        .close:hover { background: #444; }
</style>
  
<div id="openModal" class="modalDialog">
        <div>
                <a href="#close" title="Close" class="close" style="color:#fff;text-decoration:none;">X</a>
                <h3 style="text-align:center;color:#333;">XXXTEXTXXX</h3>
                <br/>
                <a href="#close"><input id="cancel_ready_button" type="button" value="Fertig" /></a>
        </div>
</div>

EOHTML;
echo str_replace("XXXTEXTXXX",urldecode($_REQUEST["modal_msg"]),str_replace("#close",$_REQUEST["return_url"],$eohtml)); parent::display(); } } 