<?php

DEFINE("ACCESS_TOKEN","YDD9P7LxFOm4QRxH6k6vvNL4hiJdkZPZdXGr8biDB9RXWEX/RWCBl5JmIXQF/xexMMhDHHryHDiPPyUtiHgFPf7BtaIdK2bXUGTrITBa2loTalSN1zT3JxHBNtSmAQksGy/p4gRk8oHUwlQJXKvuPwdB04t89/1O/w1cDnyilFU=
");
DEFINE("SECRET_TOKEN","9478c29d783d6c8208ef8139da883d83");

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\Constant\HTTPHeader;

//LINESDK�̓ǂݍ���
require_once(__DIR__."/vendor/autoload.php");

//LINE���瑗���Ă�����true�ɂȂ�
if(isset($_SERVER["HTTP_".HTTPHeader::LINE_SIGNATURE])){

//LINEBOT��POST�ő����Ă������f�[�^�̎擾
  $inputData = file_get_contents("php://input");

//LINEBOTSDK�̐ݒ�
  $httpClient = new CurlHTTPClient(ACCESS_TOKEN);
  $Bot = new LINEBot($HttpClient, ['channelSecret' => SECRET_TOKEN]);
  $signature = $_SERVER["HTTP_".HTTPHeader::LINE_SIGNATURE]; 
  $Events = $Bot->parseEventRequest($InputData, $Signature);

//��ʂɃ��b�Z�[�W��������ƕ������̃f�[�^�������ɑ����Ă��邽�߁Aforeach�����Ă���B
�@�@�@�@foreach($Events as $event){
    $SendMessage = new MultiMessageBuilder();
    $TextMessageBuilder = new TextMessageBuilder("���ۂ�I");
    $SendMessage->add($TextMessageBuilder);
    $Bot->replyMessage($event->getReplyToken(), $SendMessage);
  }
}

