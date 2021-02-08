<?php

namespace MDev\XWorlds;

use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\command\SimpleCommandMap;
use pocketmine\command\CommandMap;
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener{
    const PREFIX = "§l§cXWorlds §r§8: ";
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("§c§lXWorlds §r§8: §aPlugin got activated.");
    }
    public function onDisable()
    {
        $this->getLogger()->info("§c§lXWorlds §r§8: §cPlugin got deactivated.");
    }
    public function onJoin(PlayerJoinEvent $event){
        //Nothing..
    }

    /*
          888  888          d8888 8888888b. 8888888
          888  888         d88888 888   Y88b  888
        888888888888      d88P888 888    888  888
          888  888       d88P 888 888   d88P  888
          888  888      d88P  888 8888888P"   888
        888888888888   d88P   888 888         888
          888  888    d8888888888 888         888
          888  888   d88P     888 888       8888888
    */
    //Soon...

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        switch($cmd->getName()) {
            case "xworlds":
                if(!isset($args[0])) {
                    $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds <create:tp:load:unload:list:info>");
                    return true;
                }
                if(isset($args[0])) {
                    $options = ["create", "tp", "load", "unload", "list", "info"];
                    if (!in_array($args[0], $options)) {
                        $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds <create:tp:load:unload:list:info>");
                        return true;
                    }
                    if ($args[0] == "create") {
                        if (!$sender->hasPermission("xworlds.create")) {
                            $sender->sendMessage(self::PREFIX . "§cYou have no permissions to do that.");
                            return true;
                        }
                        if (!isset($args[1])) {
                            $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds create <Name> <Generator>");
                            return true;
                        }
                        if (!isset($args[2])) {
                            $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds create <Name> <Generator>");
                            $sender->sendMessage(self::PREFIX . "§cAll generators: normal, flat, void, nether.");
                            return true;
                        }
                        $generators = ["normal", "flat", "void", "nether"];
                        if (!in_array($args[2], $generators)) {
                            $sender->sendMessage(self::PREFIX . "§cInvaild generator. All generators: normal, flat, void, nether.");
                            return true;
                        }

                        if ($args[2] == "normal") {
                            if ($this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world already exist.");
                                return true;
                            }
                            $normalgen = GeneratorManager::getGenerator("Normal");
                            $this->getServer()->generateLevel($args[1], null, $normalgen);
                            $this->getServer()->loadLevel($args[1]);
                            $sender->sendMessage(self::PREFIX . "§aWorld §e" . $args[1] . " §awas created successfully.");
                            $world = $this->getServer()->getLevelByName($args[1]);
                            $sender->teleport($world->getSafeSpawn());
                        }
                        if ($args[2] == "flat") {
                            if ($this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world already exist.");
                                return true;
                            }
                            $flatgen = GeneratorManager::getGenerator("Flat");
                            $this->getServer()->generateLevel($args[1], null, $flatgen);
                            $this->getServer()->loadLevel($args[1]);
                            $sender->sendMessage(self::PREFIX . "§aWorld§e " . $args[1] . " §awas created successfully.");
                            $world = $this->getServer()->getLevelByName($args[1]);
                            $sender->teleport($world->getSafeSpawn());
                        }
                        if ($args[2] == "void") {
                            if ($this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world already exist.");
                                return true;
                            }
                            $voidgen = GeneratorManager::getGenerator("Void");
                            $this->getServer()->generateLevel($args[1], null, $voidgen);
                            $this->getServer()->loadLevel($args[1]);
                            $sender->sendMessage(self::PREFIX . "§aWorld§e " . $args[1] . " §awas created successfully.");
                            $world = $this->getServer()->getLevelByName($args[1]);
                            $sender->teleport($world->getSafeSpawn());
                        }
                        if ($args[2] == "nether") {
                            if ($this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world already exist.");
                                return true;
                            }
                            $nethergen = GeneratorManager::getGenerator("Nether");
                            $this->getServer()->generateLevel($args[1], null, $nethergen);
                            $this->getServer()->loadLevel($args[1]);
                            $sender->sendMessage(self::PREFIX . "§aWorld§e " . $args[1] . " §awas created successfully.");
                            $world = $this->getServer()->getLevelByName($args[1]);
                            $sender->teleport($world->getSafeSpawn());
                        }
                    }
                    //Next.
                    if ($args[0] == "tp") {
                        if (!$sender->hasPermission("xworlds.tp")) {
                            $sender->sendMessage(self::PREFIX . "§cYou have no permissions to do that.");
                            return true;
                        }
                        if (!isset($args[1])) {
                            $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds tp <World>");
                            return true;
                        }
                        if (isset($args[1])) {
                            if (!$this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world doesn't exist.");
                                return true;
                            }
                            if (!$this->getServer()->isLevelLoaded($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world isn't loaded.");
                                if ($sender->isOp()) {
                                    $sender->sendMessage("§cYou can load it with /xworlds load " . $args[1]);
                                }
                                return true;
                            }
                            $tp = $this->getServer()->getLevelByName($args[1])->getSafeSpawn();
                            $sender->teleport($tp);
                            $sender->sendMessage(self::PREFIX . "§aTeleport in world §e" . $args[1] . "§a...");
                        }
                    }
                    if ($args[0] == "load") {
                        if (!$sender->hasPermission("xworlds.load")) {
                            $sender->sendMessage(self::PREFIX . "§cYou have no permissions to do that.");
                            return true;
                        }
                        if (!isset($args[1])) {
                            $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds load <World>");
                            return true;
                        }
                        if (isset($args[1])) {
                            if (!$this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world doesn't exist.");
                                return true;
                            }
                            if($this->getServer()->isLevelLoaded($args[1])){
                                $sender->sendMessage(self::PREFIX . "§cWorld §e" . $args[1] . " §cis already loaded.");
                                return true;
                            }
                            $this->getServer()->loadLevel($args[1]);
                            $sender->sendMessage(self::PREFIX . "§aLoaded world §e" . $args[1] . "§a.");
                        }
                    }
                    if ($args[0] == "unload") {
                        if (!$sender->hasPermission("xworlds.unload")) {
                            $sender->sendMessage(self::PREFIX . "§cYou have no permissions to do that.");
                            return true;
                        }
                        if (!isset($args[1])) {
                            $sender->sendMessage(self::PREFIX . "§cUsage: /xworlds unload <World>");
                            return true;
                        }
                        if (isset($args[1])) {
                            if (!$this->getServer()->isLevelGenerated($args[1])) {
                                $sender->sendMessage(self::PREFIX . "§cThis world doesn't exist.");
                                return true;
                            }
                            if(!$this->getServer()->isLevelLoaded($args[1])){
                                $sender->sendMessage(self::PREFIX . "§cWorld §e" . $args[1] . " §cis already unloaded.");
                                return true;
                            }
                            $level = $this->getServer()->getLevelByName($args[1]);
                            $this->getServer()->unloadLevel($level);
                            $sender->sendMessage(self::PREFIX . "§aUnloaded world §e" . $args[1] . "§a.");
                        }
                    }
                    if ($args[0] == "list") {
                        if (!$sender->hasPermission("xworlds.list")) {
                            $sender->sendMessage(self::PREFIX . "§cYou have no permissions to do that.");
                            return true;
                        }
                        $sender->sendMessage(self::PREFIX . "§7All Worlds§8:");
                        $levelNamesArray = scandir($this->getServer()->getDataPath() . "worlds/");
                        foreach ($levelNamesArray as $levelName) {
                            if ($levelName === "." || $levelName === "..") {
                                continue;
                            }
                            if($this->getServer()->isLevelLoaded($levelName)){
                                $isloaded = "§aloaded";
                                $sender->sendMessage("§7" . $levelName . " §8> " . $isloaded);
                            }
                            if(!$this->getServer()->isLevelLoaded($levelName)){
                                $isloaded = "§cunloaded";
                                $sender->sendMessage("§7" . $levelName . " §8> " . $isloaded);
                            }
                        }
                    }
                    if ($args[0] == "info") {
                        $sender->sendMessage(self::PREFIX . "§7Informations about §c§lXWorlds§r§8:");
                        $sender->sendMessage("§7Author§8: §eMDev");
                        $sender->sendMessage("§7Discord§8: §ehttps://discord.gg/WDZEAqARE7");
                        $sender->sendMessage("§7Version§8: §e1.0.0");
                    }
                }
        }
        return true;
    }
}