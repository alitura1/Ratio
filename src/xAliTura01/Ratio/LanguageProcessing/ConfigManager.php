<?php

namespace xAliTura01\Ratio\LanguageProcessing;

use xAliTura01\Ratio\Main;

class ConfigManager {

	public const CONFIG_VERSION = "0.2";

	public function __construct() {
		$this->initConfig($this->checkConfigUpdates());
	}

	public function initConfig(bool $forceUpdate = false): void {
		if (!is_dir(ConfigManager::getDataFolder())) {
			@mkdir(ConfigManager::getDataFolder());
		}
		if (!is_dir(ConfigManager::getDataFolder() . "languages")) {
			@mkdir(ConfigManager::getDataFolder() . "languages");
		}
		if (!is_file(ConfigManager::getDataFolder() . "languages/en_EN.yml") || $forceUpdate) {
			Main::getInstance()->saveResource("languages/en_EN.yml", $forceUpdate);
		}
		if (!is_file(ConfigManager::getDataFolder() . "languages/tr_TR.yml") || $forceUpdate) {
			Main::getInstance()->saveResource("languages/tr_TR.yml", $forceUpdate);
		}
		if (!is_file(ConfigManager::getDataFolder() . "languages/vi_VN.yml") || $forceUpdate) {
			Main::getInstance()->saveResource("languages/vi_VN.yml", $forceUpdate);
		}
		if (!is_file(ConfigManager::getDataFolder() . "languages/az_AZ.yml") || $forceUpdate) {
			Main::getInstance()->saveResource("languages/az_AZ.yml", $forceUpdate);
		}
		if (!is_file(ConfigManager::getDataFolder() . "languages/ru_RU.yml") || $forceUpdate) {
			Main::getInstance()->saveResource("languages/ru_RU.yml", $forceUpdate);
	}
		if (!is_file(ConfigManager::getDataFolder() . "/config.yml")) {
			Main::getInstance()->saveResource("/config.yml");
		}
	}

	public static function getDataFolder(): string {
		return Main::getInstance()->getDataFolder();
	}

	public function checkConfigUpdates(): bool {
		$configuration = Main::getInstance()->getConfig()->getAll();
		if (
			!array_key_exists("config-version", $configuration) ||
			version_compare((string)$configuration["config-version"], ConfigManager::CONFIG_VERSION) < 0
		) {
			return true;
		}

		return false;
	}

	public static function getDataPath(): string {
		return Main::getInstance()->getServer()->getDataPath();
	}
}
