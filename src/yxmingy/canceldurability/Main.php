<?php

namespace yxmingy\canceldurability;

use pocketmine\plugin\PluginBase;
use yxmingy\canceldurability\starter\Starter;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\event\entity\EntityInventoryChangeEvent;

class Main extends PluginBase implements Listener
{
  use Starter;
  const PLUGIN_NAME = "YCancelArmorDurability";
  public function onLoad()
  {
    self::info("[".self::PLUGIN_NAME."] is Loading...");
  }
  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    self::notice("[".self::PLUGIN_NAME."] is Enabled by xMing!");
  }
  public function onDisable()
  {
    self::warning("[".self::PLUGIN_NAME."] is Turned Off.");
  }
  
  public static function setItemUnbreakable(Item &$item)
  {
    if($item->isArmor() || $item->isTool()) {
      $tag = $item->getNamedTag();
      if($tag == null) {
        $tag = new CompoundTag("",[
          new IntTag("Unbreakable",1)
        ]);
        $item->setNamedTag($tag);
      }elseif(($utag=$item->getNamedTagEntry("Unbreakable") !== null) && $utag <= 0) {
        $tag->offsetSet("Unbreakable", 1);
        $item->setNamedTag($tag);
      }
    }
  }
  public static function setAllUnbreakable(Player $player)
  {
    $inventory = $player->getInventory();
    $items = $inventory->getContents();
    for($i=0;$i<$inventory->getMaxStackSize();$i++) {
      if(isset($items[$i])) self::setItemUnbreakable($items[$i]);
    }
    $inventory->setContents($items);
    $items = $inventory->getArmorContents();
    for($i=0;$i<4;$i++) {
      if(isset($items[$i])) self::setItemUnbreakable($items[$i]);
    }
    $inventory->setArmorContents($items);
  }
  public function onJoin(PlayerJoinEvent $event)
  {
    self::setAllUnbreakable($event->getPlayer());
  }
  public function onInventoryChange(EntityInventoryChangeEvent $event) {
    if($event->getEntity() instanceof Player) {
      $item = $event->getNewItem();
      self::setItemUnbreakable($item);
      if($item->isArmor() || $item->isTool()) {
        $event->setNewItem($item);
      }
    }
  }

}