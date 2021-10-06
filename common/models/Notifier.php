<?php

namespace uusm\models;

use yii\httpclient\Client;

class Notifier extends \yii\base\Model
{
	protected string $bot_id = "1400168915:AAHvPKlmhlVrfFr9yq39eZ1MMT0NfVSF4gg";
	protected array $chat = ["443353023"];
	public string $message;

	public function send()
	{
		$client = new Client();
		foreach ($this->chat as $chat_id) {
			$response = $client->createRequest()
				->setMethod("GET")
				->setUrl("https://api.telegram.org/bot{$this->bot_id}/sendMessage")
				->setData([
					"chat_id" => $chat_id,
					"text" => $this->message
				])
				->send();
		}
	}
}