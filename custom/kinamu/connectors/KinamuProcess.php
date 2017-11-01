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


 class KinamuProcess { public $sFilePath; public $sFileName; public function __construct($sFileName) { $this->sFilePath = '/var/run'; $this->sFileName = $sFileName; } public function setPath($sPath) { if(subStr($sPath, -1) == '/') { $sPath = subStr($sPath, 0, -1); } $this->sFilePath = $sPath; } public function getFullPath() { return $this->sFilePath . '/' . $this->sFileName; } public function isLocked() { $db = $GLOBALS["db"]; $sFile = $this->sFileName; $res = $db->query("select 1 from config where category = 'evalock' and name = '$sFile'"); return $db->fetchByAssoc($res) ? true : false; } public function lockProcess() { $db = $GLOBALS["db"]; $sFile = $this->sFileName; $db->query("insert into config (category,name,value) values('evalock','$sFile','".date('Y-m-d H:i:s')."')"); return true; } public function unlockProcess() { $db = $GLOBALS["db"]; $sFile = $this->sFileName; $db->query("delete from config where category='evalock' and name='$sFile'"); return true; } } 