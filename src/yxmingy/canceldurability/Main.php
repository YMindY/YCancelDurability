<?php

namespace yxmingy\canceldurability;

use pocketmine\Player;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use yxmingy\canceldurability\starter\Starter;
use pocketmine\event\Listener;

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
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new FixTask($this), 10*20);
    self::notice("[".self::PLUGIN_NAME."] is Enabled by xMing!");
  }
  public function onDisable()
  {
    self::warning("[".self::PLUGIN_NAME."] is Turned Off.");
  }
  public static function repairClothes(Player $player)
  {
    $inventory = $player->getInventory();
    $armors = $inventory->getArmorContents();
    for ($i=0;$i<4;$i++) {
      $armors[$i]->setDamage(0);
      $inventory->setArmorItem($i, $armors[$i]);
    }
  }
  public static function repairItemInHand(Player $player)
  {
    $inventory = $player->getInventory();
    $item = $inventory->getItemInHand();
    if($item->isTool() || $item->isArmor()){
      $item->setDamage(0);
      $inventory->setItemInHand($item);
    }
  }
  public static function repairInventory(Player $player)
  {
    $inventory = $player->getInventory();
    for($i=0;$i<$inventory->getMaxStackSize();$i++) {
      $item = $inventory->getItem($i);
      if($item->isTool() || $item->isArmor()){
        $item->setDamage(0);
        $inventory->setItem($i, $item);
      }
    }
  }
  public static function repairAll(Player $player) {
    self::repairClothes($player);
    self::repairInventory($player);
    self::repairItemInHand($player);
  }
  public function onDamage(EntityDamageEvent $event)
  {
    if($event instanceof EntityDamageByEntityEvent) {
      $damager = $event->getDamager();
      $hurter = $event->getEntity();
      if($damager instanceof Player) {
        self::repairItemInHand($damager);
      }
      if($hurter instanceof Player) {
        self::repairClothes($hurter);
        self::repairItemInHand($hurter);
      }
    }
  }
  public function onItemHeld(PlayerItemHeldEvent $event)
  {
    self::repairItemInHand($event->getPlayer());
  }
  public function onTouch(PlayerInteractEvent $event) {
    self::repairItemInHand($event->getPlayer());
  }
}