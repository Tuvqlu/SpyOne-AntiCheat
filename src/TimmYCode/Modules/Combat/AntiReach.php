<?php

namespace TimmYCode\Modules\Combat;

use pocketmine\event\Event;
use TimmYCode\Modules\ModuleBase;
use TimmYCode\Modules\Module;
use TimmYCode\Punishment\Methods\Message;
use TimmYCode\Punishment\Punishment;
use TimmYCode\Utils\BlockUtil;
use TimmYCode\Utils\PlayerUtil;
use pocketmine\player\Player;

class AntiReach extends ModuleBase implements Module
{

	private array $damagerPos = array(), $targetPos = array();
	private float $distance = 0.0, $distanceAllowed = 4.45;

	public function getName() : String
	{
		return "AntiReach";
	}

	public function warningLimit(): int
	{
		return 1;
	}

	public function punishment(): Punishment
	{
		return new Message("Reach detected");
	}

	public function setup(): void
	{

	}

	public function check2(Event $event, Player $damager, Player $target): string
	{
		if(!$this->isActive() || PlayerUtil::combatInfluenced($damager)) return "";

		$this->damagerPos = PlayerUtil::getPosition($damager);
		$this->targetPos = PlayerUtil::getPosition($target);
		$this->distance = BlockUtil::calculateDistance($this->damagerPos, $this->targetPos);


		if($this->distance > $this->distanceAllowed) {
			$this->addWarning(1, $damager);
			$this->checkAndFirePunishment($this, $damager);
			$damager->sendMessage($this->distance);
			return "Hit too far " . $this->distance;
		}
		return "";
	}

}
