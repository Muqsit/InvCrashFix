<?php

declare(strict_types=1);

namespace muqsit\invcrashfix;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\plugin\PluginBase;

final class Loader extends PluginBase implements Listener{

	/** @var bool */
	private $cancel_send = true;

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param DataPacketSendEvent $event
	 * @priority NORMAL
	 * @ignoreCancelled true
	 */
	public function onDataPacketSend(DataPacketSendEvent $event) : void{
		if($this->cancel_send && $event->getPacket() instanceof ContainerClosePacket){
			$event->setCancelled();
		}
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 * @priority NORMAL
	 * @ignoreCancelled true
	 */
	public function onDataPacketReceive(DataPacketReceiveEvent $event) : void{
		if($event->getPacket() instanceof ContainerClosePacket){
			$this->cancel_send = false;
			$event->getPlayer()->sendDataPacket($event->getPacket(), false, true);
			$this->cancel_send = true;
		}
	}
}