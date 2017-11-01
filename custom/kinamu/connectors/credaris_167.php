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


 require_once('custom/kinamu/connectors/credaris_165.php'); class KinamuPluginView { public $sCSSClasses = ''; public $oOutputBuffer; public $sMode = 'HTML'; public $oPlugin; public $bCSSRendered = false; public $bOnGoing = false; public function __construct() { $this->oOutputBuffer = new KinamuOutputBuffer(); $this->setCSSText(); } public function setMode($sMode) { $this->sMode = ($sMode == 'console') ? 'console' : 'HTML'; } public function setPlugin($oPlugin) { $this->oPlugin = $oPlugin; } public function renderHeadline($sHeadline='') { if(empty($sHeadline)) { $sHeadline = 'KINAMU Connector'; } if($this->sMode == 'HTML') { $sContent = '<div style="margin:10px 0px 5px 0px;">'; $sContent .= '<h1>' . $sHeadline . '</h1>'; $sContent .= '</div>'; } else { $sContent = strToUpper($sHeadline) . "\n\n"; } $this->render($sContent); } public function renderSubHeadline($sHeadline='') { if(empty($sHeadline)) { $sHeadline = isset($this->oPlugin->sHeadline) ? $this->oPlugin->sHeadline : ''; } if($this->sMode == 'HTML') { $sContent = '<h3>' . $sHeadline . '</h3><br>'; } else { $sContent = strToUpper($sHeadline) . "\n\n"; } $this->render($sContent); } public function renderActivity($sText) { if($this->sMode == 'HTML') { $this->renderAndSendToClient('<div class="KinamuPlugin_Log">' . $sText . '</div>'); } else { $this->renderAndSendToClient($sText . "\n"); } } public function renderOnGoingActivity($sText) { if($this->sMode == 'HTML') { $this->renderAndSendToClient('<div class="KinamuPlugin_Log" style="position:absolute; top:0px; left:0px; right:0px;">' . $sText . '</div>'); } else { $this->renderAndSendToClient("\n"); } } public function renderOnGoingActivityStart() { if($this->bOnGoing) { return; } $this->bOnGoing = true; if($this->sMode == 'HTML') { $this->renderAndSendToClient('<div style="position:relative;">'); } else { $this->renderAndSendToClient("\n"); } } public function renderOnGoingActivityEnd() { if(!$this->bOnGoing) { return; } $this->bOnGoing = false; if($this->sMode == 'HTML') { $this->renderAndSendToClient('</div><br>'); } else { $this->renderAndSendToClient("\n"); } } public function renderError($sText) { if($this->sMode == 'HTML') { $this->render('<div class="KinamuPlugin_Log_Error">' . $sText . '</div>'); } else { $this->render(strToUpper($sText)); } } public function renderSubNav($oSubNav) { if($this->sMode != 'HTML') { return; } $sHtml = '<div class="KinamuPluginTaskNav">'; foreach($oSubNav->items as $sTask => $sTitle) { $sSelected = ($sTask == $oSubNav->selected) ? 'selected' : ''; $sHtml .= '<a href="index.php?module=' . $_REQUEST['module'] . '&action=plugin&plugin=' . $_REQUEST['plugin'] . '&task=' . $sTask . '" class="KinamuPluginTaskNavItem ' . $sSelected. '">' . $sTitle. '</a>'; } $sHtml .= '<div style="clear:left;"></div>'; $sHtml .= '</div>'; $this->render($sHtml); } public function credaris_149($oHistory) { $sHtml = ''; $sSubHeadline = (isset($oHistory->sTitle) && !empty($oHistory->sTitle)) ? $oHistory->sTitle : 'Log'; $sHtml .= '<script>
		function k_refreshPage() {

			var sUrl = "index.php?module=' . $_REQUEST['module'] . '&action=plugin&plugin=' . $_REQUEST['plugin'] . '&page=' . $oHistory->iPage . '&showErrors=";
	
			if(document.getElementById("kSelectErrors").checked) {
				sUrl += "1";
			} else {
				sUrl += "0";
			}

			window.location.href = sUrl;
	
		}
			
		</script>'; $sNav = ''; $sNav .= "<br>"; $sChecked = (isset($_REQUEST['showErrors']) && ($_REQUEST['showErrors'] == 1)) ? 'checked' : ''; $sUrl = 'index.php?module=' . $_REQUEST['module'] . '&action=plugin&plugin=' . $_REQUEST['plugin']; $sUrl .= isset($_REQUEST['task']) ? '&task=' . $_REQUEST['task'] : ''; $sNav .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:10px;">'; $sNav .= '<tr>'; $sNav .= '<td width="33%">'; if($oHistory->iPage > 0) { $sNav .= '<a href="' . $sUrl . '&page=' . ($oHistory->iPage -1) . '">&lt; previous ' . $oHistory->iRowsPerPage . ' entries</a>'; } $sNav .= '</td>'; $sNav .= '<td width="34%" align="center">'; if($oHistory->iPage > 0) { $sNav .= '<a href="' . $sUrl . '&page=0">last ' . $oHistory->iRowsPerPage . ' entries</a>'; } $sNav .= '</td>'; $sNav .= '<td width="33%" align="right">'; $sNav .= '<a href="' . $sUrl . '&page=' . ($oHistory->iPage +1) . '">next ' . $oHistory->iRowsPerPage . ' entries &gt;</a>'; $sNav .= '</td>'; $sNav .= '</tr>'; $sNav .= '</table>'; $sHtml .= $sNav; $sHtml .= '<table class="KinamuPluginRecordsTable">'; $sHtml .= '<tr>'; foreach($oHistory->aGridCols as $iCol => $aColumn) { $sCSS = isset($aColumn['css']) ? $aColumn['css'] : ''; $sHtml .= '<td class="tdHeader" style="' . $sCSS . '">' . $aColumn['sName'] . '</td>'; } $sHtml .= '</tr>'; foreach($oHistory->aGridRows as $iRow => $aRecord) { $sCSS = isset($aRecord['css']) ? $aRecord['css'] : ''; $sHtml .= '<tr style="' . $sCSS . '">'; foreach($oHistory->aGridCols as $iCol => $aColumn) { $mValue = isset($aRecord['value'][$aColumn['sKey']]) ? $aRecord['value'][$aColumn['sKey']] : ''; $sHtml .= '<td>' . $mValue . '</td>'; } $sHtml .= '</tr>'; } $sHtml .= '</table>'; $sHtml .= $sNav; $this->render($sHtml); } public function renderPluginSelection($aPlugins) { $app_list_strings = $GLOBALS["app_list_strings"]; $this->renderHeadline(); $sHtml = ''; $sHtml .= 'Please select a plugin:<br><br>'; $sHtml .= '<ul class="KinamuPluginSelection">'; foreach($aPlugins as $sPlugin) { $sName = isset($app_list_strings['moduleList'][$sPlugin]) ? $app_list_strings['moduleList'][$sPlugin] : $sPlugin; $sHtml .= '<li><a href="index.php?module=' . $_REQUEST['module'] . '&action=plugin&plugin=' . $sPlugin . '">' . $sName . '</a></li>'; } $sHtml .= '</ul>'; $sHtml .= '<br><br><br><br>'; $this->render($sHtml); } public function setCSSText() { $this->sCSSClasses .= '.KinamuPlugin_Log_Error {color:red;}'; $this->sCSSClasses .= '.KinamuPlugin_Log {background-color:#111; unicode-bidi:embed; font-family:monospace; white-space:pre; color:#FFF; padding:1px 1px 1px 3px;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable {margin:0px; padding:0px; width:100%; border:1px solid #888; border-collapse:collapse;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr:nth-child(odd){ background-color:#DDD; }'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr:nth-child(even) {}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr.error {background-color:#C33; color:#FFF;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable td {vertical-align:middle; border:1px solid #888; border-width:0px 1px 1px 0px; text-align:left; padding:1px 5px;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable td, .KinamuPluginRecordsTable td a {font-size:11px; font-family:Arial; font-weight:normal;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr:last-child td {border-width:0px 1px 0px 0px;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr td:last-child {border-width:0px 1px 1px 0px;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr:last-child td:last-child {border-width:0px 0px 0px 0px;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable td.tdHeader {background-color:#CCC; border:0px solid #888; border-width:0px 1px 1px 0px; font-size:14px; font-family:Arial; font-weight:bold; color:#000;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable td.tdHeader2 {background-color:#CCC; border:0px solid #888; border-width:0px 1px 1px 0px; font-size:12px; font-family:Arial; color:#000;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr:first-child td:first-child {border-width:0px 1px 1px 0px;}'; $this->sCSSClasses .= '.KinamuPluginRecordsTable tr:first-child td:last-child {border-width:0px 1px 1px 0px;}'; $this->sCSSClasses .= '.KinamuPluginTaskNav {background-color:#CCC; margin-bottom:20px;}'; $this->sCSSClasses .= 'a.KinamuPluginTaskNavItem {display:block; float:left; padding:6px 10px; text-decoration:none; color:#000; font-weight:bold; border-right:1px solid #FFF;}'; $this->sCSSClasses .= 'a.KinamuPluginTaskNavItem:hover, a.KinamuPluginTaskNavItem.selected {background-color:#DA1617; color:#FFF;}'; $this->sCSSClasses .= '.KinamuPluginSelection {margin:0px;}'; } public function renderCSS($sCSS='') { if($this->bCSSRendered || ($this->sMode != 'HTML')) { return; } $this->bCSSRendered = true; $this->render('<style type="text/css">' . $this->sCSSClasses . '</style>'); } public function render($sContent) { $this->oOutputBuffer->add($sContent); } public function renderAndSendToClient($sContent) { $this->oOutputBuffer->add($sContent); $this->sendToClient(); } public function sendToClient($bExit=false) { if(($this->sMode == 'HTML') && !$this->bCSSRendered) { $this->renderCSS(); } $this->oOutputBuffer->send($bExit); } } 