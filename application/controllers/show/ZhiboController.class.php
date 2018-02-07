<?php


header("Content-type:text/html;charset=utf-8");
include_once 'application/views/show/templates/m/aliyun-php-sdk-core/Config.php';
use vod\Request\V20170321 as vod;
class ZhiboController extends BaseController
{

   public function  zhiboAction()
    {

        $regionId = 'cn-shanghai';
        $access_key_id='LTAIdZHQXD7HsG1H';
        $access_key_secret='7kOmdeAEci4RRH4wdHdUAoCoMvBaAU';
        $profile = DefaultProfile::getProfile($regionId, $access_key_id, $access_key_secret);
        $client = new DefaultAcsClient($profile);
        try {
           self:: testGetVideoPlayAuthAction($client, $regionId);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    //阿里直播测试方法
   public function testGetVideoPlayAuthAction($client, $regionId) {

        $request = new vod\GetVideoPlayAuthRequest();
        $request->setAcceptFormat('JSON');
        $request->setRegionId($regionId);
        $request->setVideoId(123);            //视频ID
        $response = $client->getAcsResponse($request);
        return $response;
    }

}