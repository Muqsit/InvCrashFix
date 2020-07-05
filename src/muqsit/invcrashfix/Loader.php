<?php

declare(strict_types=1);

namespace muqsit\invcrashfix;

use muqsit\simplepackethandler\SimplePacketHandler;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\plugin\PluginBase;

final class Loader extends PluginBase{

	protected function onEnable() : void{
		static $send = false;
		SimplePacketHandler::createInterceptor($this)
			->interceptIncoming(static function(ContainerClosePacket $packet, NetworkSession $session) use(&$send) : bool{
				$send = true;
				$session->sendDataPacket($packet);
				$send = false;
				return true;
			})
			->interceptOutgoing(static function(ContainerClosePacket $packet, NetworkSession $session) use(&$send) : bool{
				return $send;
			});
	}
}