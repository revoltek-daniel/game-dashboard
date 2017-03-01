<?php
$host = '84.200.28.42';
$port = 1887;
$port2 = 2302;
$port3 = 1604;
$waitTimeoutInSeconds = 0;
$fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds);
$fp = fsockopen($host,$port2,$errCode,$errStr,$waitTimeoutInSeconds);
$fp = fsockopen($host,$port3,$errCode,$errStr,$waitTimeoutInSeconds);

