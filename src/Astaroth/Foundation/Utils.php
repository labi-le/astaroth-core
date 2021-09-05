<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Support\Facades\Create;
use Astaroth\VkUtils\Builders\Message;

class Utils
{

    /**
     * @param array $param
     * @return string|null
     * @throws \JsonException
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
        $tr = array(
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
        );
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
     * @param array $delimiters
     * @param string $haystack
     * @return array|bool
     */
    public static function multiExplode(array $delimiters, string $haystack): array|bool
    {
        $ready = str_replace($delimiters, $delimiters[0], $haystack);
        return explode($delimiters[0], $ready);
    }

    /**
     * Регулярка, чтоб выбрать все айдишники из текста
     * @param string $string
     * @return array|bool
     * @noinspection NotOptimalRegularExpressionsInspection
     */
    public static function regexId(string $string): array|bool
    {
        preg_match_all('/\[(?:id|club)([0-9]*)\|.*?]/', $string, $match);
        return $match[1];
    }

    /**
     * Простой дебаг в stdout
     * Будет полезно для callback
     * @param $data
     */
    public static function var_dumpToStdout($data): void
    {
        file_put_contents('php://stdout', var_export($data, true));
    }

    /**
     * @throws \Throwable
     */
    public static function logToMessage(int $id, string $error_level, \Exception|string $e): void
    {
        $message = new Message();
        $message->setPeerId($id);

        if ($e instanceof \Exception) {
            $message->setMessage(
                sprintf(
                    "Logger:\nError Level - %s\nError Code - %s\nMessage - %s",
                    $error_level,
                    $e->getCode(),
                    $e->getMessage()
                )
            );
        } else {
            $message->setMessage(
                sprintf(
                    "Logger:\nError Level - %s\nMessage - %s",
                    $error_level,
                    $e
                )
            );
        }

        Create::new($message);
    }
}