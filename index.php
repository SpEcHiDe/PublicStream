<?php
// #!/usr/bin/env php
/**
 * Example bot.
 * https://t.me/TrollVoiceBot?start=1266
 *
 * Copyright 2016-2020 Daniil Gentili
 * (https://daniil.it)
 * This file is part of MadelineProto.
 * MadelineProto is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * MadelineProto is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU General Public License along with MadelineProto.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Daniil Gentili <daniil@daniil.it>
 * @copyright 2016-2020 Daniil Gentili <daniil@daniil.it>
 * @license   https://opensource.org/licenses/AGPL-3.0 AGPLv3
 *
 * @link https://docs.madelineproto.xyz MadelineProto documentation
 */

/*
 * Various ways to load @MadeLineProto
 */
if (file_exists(__DIR__ . "/vendor/autoload.php")) {
    include __DIR__ . "/vendor/autoload.php";
} else {
    if (!file_exists("madeline.php")) {
        copy("https://phar.madelineproto.xyz/madeline.php", "madeline.php");
    }
    /**
     * @psalm-suppress MissingFile
     */
    include "madeline.php";
}

/**
 * required environment variables
 */

$TG_API_ID = getenv("TG_API_ID");
$TG_API_HASH = getenv("TG_API_HASH");
$TG_BOT_TOKEN = getenv("TG_BOT_TOKEN");

/**
 * @MadeLineProto Settings
 */

$settings = new \danog\MadelineProto\Settings;

$settings->getConnection()
    ->setMinMediaSocketCount(20)
    ->setMaxMediaSocketCount(1000);
// IMPORTANT: for security reasons, upload by URL will still be allowed
$settings->getFiles()->setAllowAutomaticUpload(true);

$settings->getAppInfo()
    ->setApiId($TG_API_ID)
    ->setApiHash($TG_API_HASH);

/**
 * start @MadeLineProto
 */

$MadelineProto = new \danog\MadelineProto\API(
    'bot.madeline',
    $settings
);
$MadelineProto->botLogin($TG_BOT_TOKEN);
$MadelineProto->start();

/**
 * get required parameters as query string, in Web Browser
 */
$chat_id = $_REQUEST["chat_id"];
$message_id = $_REQUEST["message_id"];

/**
 * https://docs.madelineproto.xyz/API_docs/methods/messages.getMessages.html
 */

$MessageMedia = $MadelineProto->channels->getMessages([
    'channel' => $chat_id,
    'id' => [
        $message_id
    ],
]);

/**
 * https://docs.madelineproto.xyz/docs/FILES.html#download-to-browser
 */
$MadelineProto->downloadToBrowser(
    $MessageMedia["messages"][0]
);
