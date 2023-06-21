<?php

namespace wock\ultrepair\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\FizzSound;
use wock\ultrepair\UltraRepair;

class FixCommand extends Command implements PluginOwned {

    /** @var UltraRepair */
    private UltraRepair $plugin;

    /** @var array */
    private array $cooldowns;

    public function __construct(UltraRepair $plugin){
        parent::__construct("fix", "Repair items", "/fix [all]", ["repair"]);
        $this->setPermission("ultrarepair.fix");
        $this->plugin = $plugin;
        $this->cooldowns = [];
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("You must run this command in-game.");
            return false;
        }

        $player = $sender;
        $item = $player->getInventory()->getItemInHand();
        $position = $sender->getPosition();
        $cooldownTime = $this->plugin->getConfig()->get("cooldown", 60); // Default cooldown time of 60 seconds

        $messages = $this->plugin->getConfig()->get("messages", []);

        if (empty($args)) {
            if ($item->isNull()) {
                $sender->sendMessage(str_replace("&", "§", $messages["no_item"]));
                return true;
            }

            if ($item instanceof Durable) {
                if ($this->hasCooldown($player)) {
                    $remainingTime = $this->getRemainingCooldownTime($player);
                    $cooldownMessage = str_replace("{remaining_time}", $remainingTime, $messages["cooldown"]);
                    $sender->sendMessage(str_replace("&", "§", $cooldownMessage));
                    return true;
                }

                $item->setDamage(0);
                $player->getInventory()->setItemInHand($item);
                $sender->sendMessage(str_replace("&", "§", $messages["item_repaired"]));
                $sender->getWorld()->addSound($position, new AnvilUseSound());

                if (!$player->hasPermission("ultrarepair.bypasscooldown")) {
                    $this->addCooldown($player, $cooldownTime); // Add cooldown for repairing a single item
                }
            } else {
                $sender->sendMessage(str_replace("&", "§", $messages["invalid_item"]));
                $sender->getWorld()->addSound($position, new FizzSound());
            }
        } elseif ($args[0] === "all") {
            if ($this->hasCooldown($player, true)) {
                $remainingTime = $this->getRemainingCooldownTime($player, true);
                $cooldownMessage = str_replace("{remaining_time}", $remainingTime, $messages["cooldown_all"]);
                $sender->sendMessage(str_replace("&", "§", $cooldownMessage));
                return true;
            }

            $this->repairAllItems($player);
            $sender->sendMessage(str_replace("&", "§", $messages["all_items_repaired"]));
            $sender->getWorld()->addSound($position, new AnvilUseSound());

            if (!$player->hasPermission("ultrarepair.bypasscooldown")) {
                $this->addCooldown($player, $cooldownTime, true);
            }
        }

        return true;
    }

    private function repairAllItems(Player $player) {
        $inventory = $player->getInventory();
        $armorInventory = $player->getArmorInventory();
        foreach ($inventory->getContents() as $slot => $item) {
            if (!$item->isNull()) {
                if ($item instanceof Durable) {
                    $item->setDamage(0);
                    $inventory->setItem($slot, $item);
                }
            }
        }
        for ($slot = 0; $slot < 9; $slot++) {
            $item = $inventory->getItem($slot);
            if (!$item->isNull()) {
                if ($item instanceof Durable) {
                    $item->setDamage(0);
                    $inventory->setItem($slot, $item);
                }
            }
        }
        foreach ($armorInventory->getContents() as $slot => $item) {
            if (!$item->isNull()) {
                if ($item instanceof Durable) {
                    $item->setDamage(0);
                    $armorInventory->setItem($slot, $item);
                }
            }
        }
    }

    private function addCooldown(Player $player, int $seconds, bool $allItems = false) {
        $name = $player->getName();
        $this->cooldowns[$name] = [
            "time" => time() + $seconds,
            "allItems" => $allItems,
        ];
    }

    private function hasCooldown(Player $player, bool $allItems = false): bool {
        $name = $player->getName();
        if (isset($this->cooldowns[$name])) {
            $cooldown = $this->cooldowns[$name];
            if ($allItems && $cooldown["allItems"]) {
                return time() < $cooldown["time"];
            } elseif (!$allItems && !$cooldown["allItems"]) {
                return time() < $cooldown["time"];
            }
        }
        return false;
    }

    private function getRemainingCooldownTime(Player $player, bool $allItems = false): int {
        $name = $player->getName();
        if (isset($this->cooldowns[$name])) {
            $cooldown = $this->cooldowns[$name];
            if ($allItems && $cooldown["allItems"]) {
                return $cooldown["time"] - time();
            } elseif (!$allItems && !$cooldown["allItems"]) {
                return $cooldown["time"] - time();
            }
        }
        return 0;
    }

    public function getOwningPlugin(): UltraRepair
    {
        return $this->plugin;
    }
}
