<?php
namespace yxmingy\canceldurability;

use pocketmine\scheduler\PluginTask;

class FixTask extends PluginTask
{
  public function onRun($currentTick)
  {
    foreach ($this->owner->getServer()->getOnlinePlayers() as $player) {
      Main::repairAll($player);
    }
  }

}

