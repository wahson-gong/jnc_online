<?php
$ch=curl_init();
//设置请求
header("Location:http://jnc.cdsile.cn/?p=show&c=backups&a=update");
curl_setopt($ch,CURLOPT_URL,"http://jnc.cdsile.cn/?p=show&c=backups&a=update");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
$output=curl_exec($ch);
curl_close($ch);
echo date("Y-m-d H:i:s",time())."\r\n";
