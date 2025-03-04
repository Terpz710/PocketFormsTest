<?php

declare(strict_types=1);

namespace terpz710\pocketformstest;

use pocketmine\plugin\PluginBase;

use terpz710\simpleforms\SimpleForm;
use terpz710\simpleforms\ModalForm;
use terpz710\simpleforms\CustomForm;

use CortexPE\Commando\PacketHooker;

final class Loader extends PluginBase {

    protected function onEnable() : void{
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->registerAll("PocketFormsTest", [
            new SimpleCommand($this, "simpleform", "Simple form command"),
            new ModalCommand($this, "modalform", "Modal form command"),
            new SimpleCommand($this, "customform", "Custom form command")
        ]);
    }

    public static function sendSimpleForm(Player $player) : void{
        $form = (new SimpleForm())
            ->setTitle("Main Menu")
            ->setContent("Select an option:")
            ->addButton("Warp to Spawn")
            ->addButton("Check Balance")
            ->addButton("Exit")
            ->setCallback(function(Player $player, $data) {
                if ($data !== null) {
                    switch ($data) {
                        case 0:
                            $player->teleport($player->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
                            $player->sendMessage("Teleported to spawn!");
                            break;
                        case 1:
                            $player->sendMessage("Your balance: $" . number_format(1000));
                            break;
                        case 2:
                            $player->sendMessage("You exited the menu.");
                            break;
                    }
                }
            });

        $player->sendForm($form);
    }

    public static function sendModalForm(Player $player) : void{
        $form = (new ModalForm())
            ->setTitle("Exit Confirmation")
            ->setContent("Are you sure you want to leave?")
            ->setButton1("Yes")
            ->setButton2("No")
            ->setCallback(function(Player $player, bool $data) {
                if ($data) {
                    $player->kick("You have left the server.");
                } else {
                    $player->sendMessage("You chose to stay!");
                }
            });

        $player->sendForm($form);
    }

    public static function sendCustomForm(Player $player) : void{
        $form = (new CustomForm())
            ->setTitle("Settings Menu")
            ->addLabel("Welcome to the settings menu!")
            ->addInput("Enter your nickname:", "Nickname", $player->getName())
            ->addToggle("Enable notifications?", true)
            ->addDropdown("Select your role:", ["Warrior", "Mage", "Archer"], 0)
            ->addSlider("Set your volume:", 0, 100, 5, 50)
            ->addStepSlider("Choose a difficulty:", ["Easy", "Normal", "Hard"], 1)
            ->setCallback(function(Player $player, $data) {
                if ($data !== null) {
                    $player->sendMessage("Nickname: " . $data[1]);
                    $player->sendMessage("Notifications: " . ($data[2] ? "Enabled" : "Disabled"));
                    $player->sendMessage("Role: " . ["Warrior", "Mage", "Archer"][$data[3]]);
                    $player->sendMessage("Volume: " . $data[4]);
                    $player->sendMessage("Difficulty: " . ["Easy", "Normal", "Hard"][$data[5]]);
                }
            });

        $player->sendForm($form);
    }
}
