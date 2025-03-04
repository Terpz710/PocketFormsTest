<?php

declare(strict_types=1);

namespace terpz710\pocketformstest\commands;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use terpz710\pocketformstest\Loader;

use CortexPE\Commando\BaseCommand;

class ModalCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("pocketformstest.cmd");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        Loader::sendModalForm($sender);
    }
}