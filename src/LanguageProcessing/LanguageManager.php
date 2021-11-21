<?php

namespace xAliTura01\Ratio\LanguageProcessing;

use Exception;
use pocketmine\player\Player;
use xAliTura01\Ratio\Main;
use pocketmine\command\CommandSender;

class LanguageManager {

	private const DEFAULT_LANGUAGE = 'eyJwbGF5ZXItbWVzc2FnZSI6Ikx1Y2t5IGZvciB5b3UsIHlvdSBicm9rZSB0aGUgYmxvY2sgYW5kIGJyb3VnaHQgb3V0IHNvbWV0aGluZyBzcGVjaWFsISIsICJ2aXAtbWVzc2FnZSI6IlN1cGVyLCB5b3UgZ290IHNvbWV0aGluZyBzcGVjaWFsIHdpdGggbHVjayBhbmQgZXh0cmEgdmlwIHN1cHBvcnQhIn0=';

	public static string $defaultLang;
	public static array $languages = [];
	public static array $players = [];
	private static bool $forceDefaultLang = false;

	public function __construct() {
		$configuration = Main::getInstance()->getConfig()->getAll();

		LanguageManager::$defaultLang = $configuration["language"];
		if ($langResources = glob(ConfigManager::getDataFolder() . "/languages/*.yml")) {
			foreach ($langResources as $langResource) {
				LanguageManager::$languages[basename($langResource, ".yml")] = yaml_parse_file($langResource);
			}
		}

		if (!isset(LanguageManager::$languages[LanguageManager::$defaultLang])) {
			LanguageManager::$languages[LanguageManager::$defaultLang] = json_decode(base64_decode(LanguageManager::DEFAULT_LANGUAGE), true);
		}

		if (isset($configuration["force-default-language"])) {
			LanguageManager::$forceDefaultLang = (bool)$configuration["force-default-language"];
		}
	}

	/**
	 * @param string[] $params
	 */
	public static function translateMessage(CommandSender $sender, string $messageIndex, array $params = []): string {
		try {
			$lang = LanguageManager::$defaultLang;
			if ($sender instanceof Player && isset(LanguageManager::$players[$sender->getName()])) {
				$lang = LanguageManager::$players[$sender->getName()];
			}

			if (empty(LanguageManager::$languages[$lang]) || LanguageManager::$forceDefaultLang) {
				$lang = LanguageManager::$defaultLang;
			}

			if (empty(LanguageManager::$languages[$lang])) {
				$lang = "en_EN";
			}

			$message = LanguageManager::$languages[$lang][$messageIndex];

			foreach ($params as $index => $param) {
				$message = str_replace("{%$index}", $param, $message);
			}


		} catch (Exception $exception) {
			Main::getInstance()->getLogger()->error("LanguageManager error: " . $exception->getMessage() . " Try remove language resources and restart the server.");
			return "";
		}

		return $message;
	}
}