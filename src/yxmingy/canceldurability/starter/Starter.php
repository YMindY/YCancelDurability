<?php
namespace yxmingy\canceldurability\starter;
use pocketmine\Server;
trait Starter
{
  protected static $instance;
  protected function assignInstance()
  {
    self::$instance=$this;
  }
  public static function getInstance()
  {
    return self::$instance;
  }
  public static function info(string $message)
  {
    Server::getInstance()->getLogger()->info($message);
  }
  public static function notice(string $message)
  {
    Server::getInstance()->getLogger()->notice($message);
  }
  public static function warning(string $message)
  {
    Server::getInstance()->getLogger()->warning($message);
  }
  public static function error(string $message)
  {
    Server::getInstance()->getLogger()->error($message);
  }
}