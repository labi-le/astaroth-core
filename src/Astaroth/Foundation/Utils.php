<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Debug\Dump;
use Astaroth\Support\Facades\Create;
use Astaroth\VkUtils\Builders\Message;
use Exception;
use Throwable;
use JsonException;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt_array;
use function explode;
use function file_put_contents;
use function json_decode;
use function json_encode;
use function preg_match_all;
use function print_r;
use function sprintf;
use function str_replace;
use function strstr;
use function uniqid;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const DIRECTORY_SEPARATOR;
use const JSON_THROW_ON_ERROR;

final class Utils
{

    /**
     * @throws JsonException
     * @noinspection JsonEncodingApiUsageInspection
     */
    public static function jsonOnline(array $param): ?string
    {
        $ch = curl_init("https://jsoneditoronline.herokuapp.com/v1/docs/");
        curl_setopt_array($ch,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER =>
                    [
                        "Content-Type:application/json"
                    ],
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS =>
                    json_encode([
                        "name" => uniqid("", true),
                        "data" => json_encode($param, JSON_THROW_ON_ERROR)
                    ])
            ]);

        /** @psalm-suppress PossiblyInvalidArgument */
        $data = @json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $data["ok"] === true ? "https://jsoneditoronline.org/?id=" . $data["id"] : null;
    }

    /**
     * Транслитерация кириллицы в латиницу
     * @param string $str
     * @return string
     */
    public static function transliteration(string $str): string
    {
        $tr = [
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
            "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
            "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
            "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
            "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
            "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
            "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "yi", "ь" => "'", "э" => "e", "ю" => "yu", "я" => "ya"
        ];
        return strtr($str, $tr);
    }

    /**
     * Удаляет из строки самую первую подстроку
     * @param $text
     * @return string|bool
     */
    public static function removeFirstWord($text): string|bool
    {
        return strstr($text, " ");
    }

    /**
     * Explode с возможностью использовать несколько символов
     */
    public static function multiExplode(array $delimiters, string $haystack): array|bool
    {
        $ready = str_replace($delimiters, $delimiters[0], $haystack);
        return explode($delimiters[0], $ready);
    }

    /**
     * Регулярка, чтоб выбрать все айдишники из текста
     */
    public static function regexId(string $string): array|bool
    {
        preg_match_all('/\[(?:id|club)(\d*)\|.*?]/', $string, $match);
        return $match[1];
    }

    /**
     * Простой дебаг в stdout
     * Будет полезно для callback
     * @param mixed ...$data
     * @see Dump::toStdOut()
     *
     * @deprecated
     */
    public static function var_dumpToStdout(mixed ...$data): void
    {
        foreach ($data as $out) {
            file_put_contents('php://stdout', print_r($out, true));
        }
    }

    /**
     * @throws Throwable
     */
    public static function logToMessage(int $id, string $error_level, Exception|string $e): void
    {
        $message = new Message();
        $message->setPeerId($id);

        if ($e instanceof Exception) {
            $message->setMessage(
                sprintf(
                    "Logger:\nError Level - %s\nError Code - %s\nMessage - %s",
                    $error_level,
                    $e->getCode(),
                    $e->getMessage()
                )
            );
        } else {
            $message->setMessage(sprintf("Logger:\nError Level - %s\nMessage - %s", $error_level, $e));
        }

        Create::new($message);
    }

    public static function replaceNamespaceToPath(string $namespace): string
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
    }
}