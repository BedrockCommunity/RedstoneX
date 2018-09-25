<?php

declare(strict_types = 1);

namespace redstonex\commands;

use pocketmine\command\{
	Command, CommandSender, defaults\VanillaCommand
};
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\lang\TranslationContainer;
use pocketmine\Player;
use redstonex\RedstoneX;

class RedstonePowaa extends VanillaCommand {

	/**
	 * KillCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.redstonepowaa.description",
			"%pocketmine.command.redstonepowaa.usage",
      ["rpower"]
		);
		$this->setPermission("pocketmine.command.redstonepowaa");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $currentAlias
	 * @param array $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) >= 2 or count($args) < 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if(count($args) === 1){
			if(!$sender->hasPermission("pocketmine.command.redstonepowaa")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			switch($args[0]){
				case 'activate':
          $sender->sendMessage("Activated max redstone power");
          RedstoneX::$redstoneMaxPower = true;

					return true;
				case 'deactivate':
          $sender->sendMessage("Deactivated max redstone power");
          RedstoneX::$redstoneMaxPower = false;

					return true;

				default;
          $sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

					return true;
			}
		}

		return true;
	}
}
