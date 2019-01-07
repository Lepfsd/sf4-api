<?php

namespace App\Service;

use Twilio\Rest\Client;
use Twilio\Http\Response;

class SmsTwiloGenerator
{
    public $token = "1c8b823e87c3c178d7b5691cd5ef5ea7"; //2c50666b6436411156057d5c4c5fed7e
    public $sid = "ACf92731ad1dc3c2489ef65fe507724350";  //AC12cb0fdafd7cdbbb9ee43103ecbb0db6
    //+16602052057 +19564460889  +584140868116
    public function __construct()
    {
        // debemos trata las variables sid y token como variables de enviroment una vez la api se encuentre en produccion para asi usarlo globalmente
        //seria lo mas a propiado y efeiciente actualmente estoy utilizando la de text
        $this->client= new Client($this->sid,$this->token);

    }
    public function getSendsms($from, $to, $body){
        
        $listmovil[] = $to;
        foreach ($listmovil as $index ){
			$sms= $this->client->messages->create($index,array(
				"from" => $from,
				"body" => $body
			));
            return $sms->sid;
        }
    }
}