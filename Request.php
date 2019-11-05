<?php

namespace app\components;

use Yii;
use app\components\TrafficQueueHelpers;

use yii\helpers\ArrayHelper;

/**
 * Description of Request
 *
 * @author dan
 */
class Request extends SubIdX {

    public $userAgent = null;
    public $campUrl = null;
    public $stream_url = null;
    public $referer = null;
    public $browserName = null;
    public $browserVer = null;
    public $os = null;
    public $osPlatform = null;
    public $osVer = null;
    public $deviceType = null;
    public $xRequestedWith = null;
    public $deviceBrand = null;
    public $deviceModel = null;
    public $serverName = null;
    public $ipAddress = null;
    public $ips = array();
    public $moreIpAdress = null;
    public $operatorId = null;
    public $operatorName = null;
    public $subId = '0';
    public $file_error = null;
    public $campaign_id = 0;
    public $stream_id = 0;
    public $sources_id = 0;
    public $sourceName = null;
    public $isUniq = FALSE;
    public $isUniqStream = FALSE;
    public $firstRequestProcTime = null;
    public $lastRequestProcTime = null;
    public $requesProcTime = null;
    public $connectType = null;
    public $isBot = FALSE;
    public $botInfo = array();
    public $botInStream = FALSE;

    public function genereateFromRequest() {

        $headers = Yii::$app->request->headers;

        //debug($headers);

        $this->campUrl = $this->getRequestUrl();

        $this->referer = $this->getReferer();

        $this->setSub_id_x($this->campUrl);

        $this->userAgent = $this->getUserAgent($headers);

        $this->isBot = $this->isBot($headers);

        $this->botInfo = $this->botInfo($headers);

        $this->serverName = $this->getServerName();

        $this->ips = $this->getIps();

        $this->operatorId = $this->getOperatorId();
     
        $this->operatorName = $this->getOperatorName();

        $this->getConnectType();

        $this->browserName = $this->getBrowserName($headers);

        $this->browserVer = $this->getBrowserVer($headers);

        $this->os = $this->getOs($headers);

        $this->osPlatform = $this->getOsPlatform($headers);

        $this->osVer = $this->getOsVer($headers);

        $this->deviceType = $this->getDeviceType($headers);

        $this->xRequestedWith = $this->getxRequestedWith($headers);

        $this->deviceBrand = $this->getDeviceBrand($headers);

        $this->deviceModel = $this->getDeviceModel($headers);

        $this->ipAddress = $this->ipsToBase();

        $this->keyword = $this->setKeyword($this->campUrl);

        $this->cost = $this->setCost($this->campUrl);

        $this->currency = $this->setCurrency($this->campUrl);

        $this->external_id = $this->setExternal_id($this->campUrl);

        $this->creative_id = $this->setCreative_id($this->campUrl);

        $this->ad_compaign_id = $this->setAd_compaign_id($this->campUrl);

        $this->site = $this->setSite($this->campUrl);

        return $this;
    }

    public function setScriptTime($firstTime, $lastTime) {

        $this->requesProcTime = $lastTime - $firstTime;
    }

    protected function setSub_id_x($url) {

        $this->sub_id_1 = $this->setSub_id_1($url);

        $this->sub_id_2 = $this->setSub_id_2($url);

        $this->sub_id_3 = $this->setSub_id_3($url);

        $this->sub_id_4 = $this->setSub_id_4($url);

        $this->sub_id_5 = $this->setSub_id_5($url);

        $this->sub_id_6 = $this->setSub_id_6($url);

        $this->sub_id_7 = $this->setSub_id_7($url);

        $this->sub_id_8 = $this->setSub_id_8($url);

        $this->sub_id_9 = $this->setSub_id_9($url);

        $this->sub_id_10 = $this->setSub_id_10($url);

        $this->sub_id_11 = $this->setSub_id_11($url);

        $this->sub_id_12 = $this->setSub_id_12($url);

        $this->sub_id_13 = $this->setSub_id_13($url);

        $this->sub_id_14 = $this->setSub_id_14($url);

        $this->sub_id_15 = $this->setSub_id_15($url);

        $this->sub_id_16 = $this->setSub_id_16($url);

        $this->sub_id_17 = $this->setSub_id_17($url);

        $this->sub_id_18 = $this->setSub_id_18($url);

        $this->sub_id_19 = $this->setSub_id_19($url);

        $this->sub_id_20 = $this->setSub_id_20($url);
    }

    public function setKeyword($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'keyword');
        }

        return $query;

    }

    public function setCost($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'cost');
        }

        return $query;

    }

    public function setCurrency($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'currency');
        }

        return $query;

    }

    public function setExternal_id($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'external_id');
        }

        return $query;

    }

    public function setCreative_id($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'creative_id');
        }

        return $query;

    }

    public function setAd_compaign_id($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'ad_compaign_id');
        }

        return $query;

    }


    public function setSite($url) {

        $query = NULL;

        $parts = parse_url($url);

        if (isset($parts['query'])) {

            parse_str($parts['query'], $query);

            $query = ArrayHelper::getValue($query, 'site');
        }

        return $query;

    }

    public function initFileByOs($key, $type = 'error') {

        $php_os = PHP_OS;

        $filename = "/home/projects/data/b3w-tds/tmp/$key.$type";

        if ($php_os == 'WINNT') {
            $filename = "C:\\tmp\\$key.$type";
        }

        return $filename;
    }

    public function isBot($headers) {

        $result = FALSE;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (!empty($clientInfo['botinfo'])) {

            $result = TRUE;
        }

        return $result;
    }

    public function botInfo($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['botinfo'])) {

            $result = $clientInfo['botinfo'];
        }
        return $result;
    }

    public function getxRequestedWith($headers){

        return $headers["X-Requested-With"];

    }
    public function getReferer() {

        $referer = NULL;

        if (isset(Yii::$app->request->referrer)) {

            $referer = Yii::$app->request->referrer;

        }

        return $referer;
    }

    public function getRequestUrl() {

        $url = Yii::$app->request->url;

        if (isset($url)) {

            return $url;
        }
    }

    public function getUserAgent($headers) {

        if ($headers->has('User-Agent')) {

            $userAgent = $headers['user-agent'];

            return $userAgent;
        }
    }

    public function getServerName() {

        $serverName = Yii::$app->request->serverName;

        if (isset($serverName)) {

            return $serverName;
        }
    }

    protected function ipsToBase() {

        $ip = $this->getIps();

        $ips = implode(", ", $ip);

        return $ips;
    }

    public function getIps() {

        $ip = Yii::$app->request->userIP;

        if (isset($ip)) {

            $ips = self::getIpAddress($ip);

            return $ips['IP_ADDRESS'];
        }
    }

    public function getOperatorId() {

        $op_id = TrafficQueueHelpers::getOperatorIdByIpList($this->getIps());

        return $op_id['id'];
    }

    public function getConnectType() {

        $this->connectType = "Wi-Fi";

        if (isset($this->operatorId)) {

            $this->connectType = "Сотовая связь";
        }
    }

    public function getOperatorName() {

        $op_id = TrafficQueueHelpers::getOperatorIdByIpList($this->getIps());

        return $op_id['title'];
    }

    public function getBrowserName($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['clientInfo']['name'])) {

            $result = $clientInfo['clientInfo']['name'];
        }

        return $result;
    }

    public function getBrowserVer($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['clientInfo']['version'])) {

            $result = $clientInfo['clientInfo']['version'];
        }

        return $result;
    }

    public function getOs($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['osInfo']['name'])) {

            $result = $clientInfo['osInfo']['name'];
        }

        return $result;
    }

    public function getOsPlatform($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['osInfo']['platform'])) {

            $result = $clientInfo['osInfo']['platform'];
        }

        return $result;
    }

    public function getOsVer($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['osInfo']['version'])) {

            $result = $clientInfo['osInfo']['version'];
        }

        return $result;
    }

    public function getDeviceType($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['device'])) {

            $result = $clientInfo['device'];
        }

        //var_dump($result);
        
        return $result;
    }

    public function getDeviceBrand($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['brand'])) {

            $result = $clientInfo['brand'];
        }

        return $result;
    }

    public function getDeviceModel($headers) {

        $result = NULL;

        $clientInfo = TrafficQueueHelpers::DeviceDetector($headers['user-agent']);

        if (isset($clientInfo['model'])) {

            $result = $clientInfo['model'];
        }

        return $result;
    }

    /////////////////////////////

    protected function getIpAddress($theip) {

        $ip_arr_x_forward = Array();

        $ip_arr_x_real = Array();

        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {

            $ips_arr_x_forward = $_SERVER["HTTP_X_FORWARDED_FOR"];

            $ips_arr = explode(",", $ips_arr_x_forward);

            $ip_arr_x_forward = array_map('trim', $ips_arr);

            //debug($ip_arr_x_forward);
        }

        if (isset($_SERVER["HTTP_X_REAL_IP"])) {

            $ips_arr_x_real = $_SERVER["HTTP_X_REAL_IP"];

            $ips_arr = explode(",", $ips_arr_x_real);

            $ip_arr_x_real = array_map('trim', $ips_arr);

            // debug($ips_arr);
        }

        $arr_all_ips = array_merge([$theip], $ip_arr_x_forward, $ip_arr_x_real);


        $all_ip_arr = ['IP_ADDRESS' => $arr_all_ips];

        // debug($ip_arr);

        return $all_ip_arr;
    }

    public function setSubId($validateStream, $stream) {

        // debug($validateStream); die;
        $sub_id = $this->campaign_id . "-" . 0 . "-" . date("YmdHis") . uniqid();

        if ($validateStream == TRUE && $stream == TRUE) {

            $sub_id = $stream->campaign_id . "-" . $stream->stream_id . "-" . date("YmdHis") . uniqid();
        }

        return $sub_id;
    }

    public function setIsUniq() {

        $this->isUniq = TRUE;
    }

    public function setIsUniqStream() {

        $this->isUniqStream = TRUE;
    }

    public function setUniqeCookies($campaign, $stream, $validateStream) {

        if (isset($stream) && $validateStream == TRUE) {
            //уникален для потока
            if (!isset($_COOKIE['uniq_stream_' . $stream['stream_id']])) {

                setcookie("uniq_stream_" . $stream->stream_id, md5($this->subId), time() + (3600 * $campaign->uniqe_period), '/');
                
                $this->setIsUniqStream();
            }
        }

        //debug($_COOKIE); die;
        // уникален для кампании                               
        if (!isset($_COOKIE['uniq_campaign_' . $campaign->id])) {

            setcookie("uniq_campaign_" . $campaign->id, md5($this->subId), time() + (3600 * $campaign->uniqe_period), '/');
            
            $this->setIsUniq();
        }

        // уникален для всех кампаний                            
        if (!isset($_COOKIE['uniq'])) {

            setcookie("uniq", md5($this->subId), time() + (3600 * $campaign->uniqe_period), '/');
        }
    }

    public function saveToDb() {

        $this->file_error = $this->initFileByOs("insert_to_base", "error");

        $insert_responce = \app\models\TrafficQueue::insertDataTraffic(
                        $this->requesProcTime,
                        $this->serverName,
                        $this->stream_url,
                        $this->campaign_id,
                        $this->stream_id,
                        $this->subId,
                        $this->isUniq,
                        $this->isUniqStream,
                        $this->isBot,
                        $this->os,
                        $this->browserName,
                        $this->deviceType,
                        $this->ipAddress,
                        $this->xRequestedWith,
                        $this->userAgent,
                        $this->referer,
                        $this->sources_id,
                        $this->osVer,
                        $this->osPlatform,
                        $this->browserVer,
                        $this->deviceBrand,
                        $this->deviceModel,
                        $this->operatorId,
                        $this->sub_id_1,
                        $this->sub_id_2,
                        $this->sub_id_3,
                        $this->sub_id_4,
                        $this->sub_id_5,
                        $this->sub_id_6,
                        $this->sub_id_7,
                        $this->sub_id_8,
                        $this->sub_id_9,
                        $this->sub_id_10,
                        $this->sub_id_11,
                        $this->sub_id_12,
                        $this->sub_id_13,
                        $this->sub_id_14,
                        $this->sub_id_15,
                        $this->sub_id_16,
                        $this->sub_id_17,
                        $this->sub_id_18,
                        $this->sub_id_19,
                        $this->sub_id_20,
                        $this->keyword,
                        $this->cost,
                        $this->currency,
                        $this->external_id,
                        $this->creative_id,
                        $this->ad_compaign_id,
                        $this->site

        );

        if (!empty($insert_responce)) {

            $err = implode(",", $insert_responce);

            $file_e = @fopen($this->file_error, 'a+');

            fwrite($file_e, date("Y-m-d H:i:s") . " - not saved - " . $err . "\n" . "============================" . "\n");

            fclose($file_e);
        }
    }

}
