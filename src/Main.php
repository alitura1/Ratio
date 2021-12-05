<?php

namespace xAliTura01\Ratio;

use ReflectionClass;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use __64FF00\PureChat\PurePerms;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use xAliTura01\Ratio\LanguageProcessing\ConfigManager;
use xAliTura01\Ratio\LanguageProcessing\LanguageManager;

class Main extends PluginBase implements Listener {

	private static Main $instance;
	public LanguageManager $languageManager;
	public ConfigManager $configManager;

	public function onLoad() : void {
		$start = !isset(Main::$instance);
		Main::$instance = $this;
	}

	protected function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->configManager = new ConfigManager();
		$this->languageManager = new LanguageManager();
		$this->saveResource("Ratio.mcpack", true);
		$manager = $this->getServer()->getResourcePackManager();
		$pack = new ZippedResourcePack($this->getDataFolder() . "Ratio.mcpack");
		$reflection = new ReflectionClass($manager);
		$property = $reflection->getProperty("resourcePacks");
		$property->setAccessible(true);
		$currentResourcePacks = $property->getValue($manager);
		$currentResourcePacks[] = $pack;
		$property->setValue($manager, $currentResourcePacks);
		$property = $reflection->getProperty("uuidList");
		$property->setAccessible(true);
		$currentUUIDPacks = $property->getValue($manager);
		$currentUUIDPacks[strtolower($pack->getPackId())] = $pack;
		$property->setValue($manager, $currentUUIDPacks);
		$property = $reflection->getProperty("serverForceResources");
		$property->setAccessible(true);
		$property->setValue($manager, true);
	}

	public static function getInstance() : Main {
		return Main::$instance;
	}

	public function blockBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$ratidoPlayer1 = rand(1,95);
		$ratidoPlayer2 = rand(1,75);
		$ratidoPlayer3 = rand(1,60);
		$ratioVip1 = rand(1,20);
		$ratioVip2 = rand(1,30);
		$ratioVip3 = rand(1,40);
		if ($event->getBlock()->getId() == 4) {
			switch ($ratidoPlayer1) {
				case 1:
					$itemSpawn = $event->getDrops();
					$itemSpawn[] = VanillaItems::DIAMOND()->setCount(1);
					$event->setDrops($itemSpawn);
					$this->playMusic($player);
					$player->sendMessage(LanguageManager::translateMessage($player, "player-message"));
					break;
			}
			switch ($ratidoPlayer2) {
				case 1:
					$itemSpawn = $event->getDrops();
					$itemSpawn[] = VanillaItems::IRON_INGOT()->setCount(1);
					$event->setDrops($itemSpawn);
					$this->playMusic($player);
					$player->sendMessage(LanguageManager::translateMessage($player, "player-message"));
					break;
				case 2:
					$itemSpawn = $event->getDrops();
					$itemSpawn[] = VanillaItems::GOLD_INGOT()->setCount(1);
					$event->setDrops($itemSpawn);
					$this->playMusic($player);
					$player->sendMessage(LanguageManager::translateMessage($player, "player-message"));
					break;
			}
			switch ($ratidoPlayer3) {
				case 1:
					$itemSpawn = $event->getDrops();
					$itemSpawn[] = VanillaItems::GHAST_TEAR()->setCount(1);
					$event->setDrops($itemSpawn);
					$this->playMusic($player);
					$player->sendMessage(LanguageManager::translateMessage($player, "player-message"));
					break;
                                case 2:
                                        $itemSpawn = $event->getDrops();
					$itemSpawn[] = VanillaItems::IRON_INGOT()->setCount(1);
					$event->setDrops($itemSpawn);
					$this->playMusic($player);
					$player->sendMessage(LanguageManager::translateMessage($player, "player-message"));
					break;
			}
			if ($player->hasPermission("ratio.vip")) {
				switch ($ratioVip1) {
					case 1:
						$itemSpawn = $event->getDrops();
						$itemSpawn[] = VanillaItems::DIAMOND()->setCount(1);
						$event->setDrops($itemSpawn);
						$this->playMusic($player);
						$player->sendMessage(LanguageManager::translateMessage($player, "vip-message"));
						break;
					case 2:
						$itemSpawn = $event->getDrops();
						$itemSpawn[] = VanillaItems::GHAST_TEAR()->setCount(1);
						$event->setDrops($itemSpawn);
						$this->playMusic($player);
						$player->sendMessage(LanguageManager::translateMessage($player, "vip-message"));
						break;
				}
				switch ($ratioVip2) {
					case 1:
						$itemSpawn = $event->getDrops();
						$itemSpawn[] = VanillaItems::GOLD_INGOT()->setCount(1);
						$event->setDrops($itemSpawn);
						$this->playMusic($player);
						$player->sendMessage(LanguageManager::translateMessage($player, "vip-message"));
						break;
					case 2:
						$itemSpawn = $event->getDrops();
						$itemSpawn[] = VanillaItems::IRON_INGOT()->setCount(1);
						$event->setDrops($itemSpawn);
						$this->playMusic($player);
						$player->sendMessage(LanguageManager::translateMessage($player, "vip-message"));
						break;
				}
				switch($ratioVip3) {
					case 1:
						$itemSpawn = $event->getDrops();
						$itemSpawn[] = VanillaItems::DIAMOND()->setCount(1);
						$event->setDrops($itemSpawn);
						$this->playMusic($player);
						$player->sendMessage(LanguageManager::translateMessage($player, "vip-message"));
						break;
				}
			}
		}
	}

	public function playMusic(Player $player)
	{
		$packet = new PlaySoundPacket();
		$soundName = $this->getConfig()->get("sound");
		$position = $player->getPosition();
		$packet->soundName = $soundName;
		$packet->x = $position->getX();
		$packet->y = $position->getY();
		$packet->z = $position->getZ();
		$packet->volume = 1;
		$packet->pitch = 1;
		$player->getNetworkSession()->sendDataPacket($packet);
	}
}
