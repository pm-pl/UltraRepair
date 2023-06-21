<?php

namespace wock\ultrarepair;

use pocketmine\plugin\PluginBase;
use wock\ultrarepair\Commands\FixCommand;

class UltraRepair extends PluginBase{

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->registerCommands();
    }

    public function registerCommands(){
        $this->getServer()->getCommandMap()->registerAll("ultrarepair", [
            new FixCommand($this)
        ]);
    }
}
