<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

class Utils
{


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public static function JsonOnline(array $data): ?string
    {
        $client = new \GuzzleHttp\Client(
            [
                "base_uri" => "https://jsoneditoronline.herokuapp.com/v1/",
                "headers" =>
                    [
                        "Content-Type" => "application/json",
                    ]
            ]
        );


        $response = $client->post("docs",
            [
                \GuzzleHttp\RequestOptions::BODY =>
                    json_encode([
                        "name" => uniqid('', true),
                        "data" => json_encode($data, JSON_THROW_ON_ERROR)
                    ], JSON_THROW_ON_ERROR)
            ]
        );


        $contents = json_decode($response->getBody()->getContents(), true);
        if ($contents["ok"] === true) {
            return "https://jsoneditoronline.org/?id=" . $contents["id"];
        }

        return null;
    }

    /**
     * Транслитерация кириллицы в латиницу
     * @param string $str
     * @return string
     */
    public static function translit(string $str): string
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
     * Православный explode с возможностью использовать несколько символов
     * @param $delimiters
     * @param $string
     * @return array|bool
     */
    public static function multiExplode($delimiters, $string): array|bool
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $ready);
    }

    /**
     * Регулярка чтоб выбрать все айдишники из текста
     * @param string $string
     * @return array|bool
     */
    public static function regexId(string $string): array|bool
    {
        preg_match_all('/\[(?:id|club)([0-9]*)\|.*?]/', $string, $match);
        return $match[1];
    }

    /**
     * Простой дебаг в stdout
     * @param $data
     */
    public static function var_dumpToStdout($data): void
    {
        file_put_contents('php://stdout', var_export($data, true));
    }

    /**
     * Строка в unixtime
     * 1 час
     * unixtime + 3600
     * @param string $string
     * @return int|false
     */
    public static function strTime(string $string): int|false
    {
        $exp = explode(' ', $string);
        $strtime = end($exp);
        $prev = prev($exp);

        $int = (int)$prev;

        return match ($strtime) {
            'с', 'сек', 'секунд', 'секунда', 'секунды', 's', 'second', 'seconds' => time() + (1 * $int),
            'м', 'мин', 'минут', 'минута', 'минуты', 'm', 'minute', 'minutes' => time() + (60 * $int),
            'ч', 'час', 'часов', 'часа', 'hour', 'hours' => time() + (3600 * $int),
            'д', 'дн', 'дней', 'дня', 'd', 'day', 'days' => time() + (86400 * $int),
            default => false,
        };
    }

    public static function logToMessage(int $id, string $error_level, \Exception|string $e): void
    {
        \Astaroth\Support\Facades\Message\MessageConstructor::create(static function (\Astaroth\VkUtils\Contracts\IMessageBuilder $message) use ($id, $e, $error_level) {

            if ($e instanceof \Exception) {
                return $message->setMessage(
                    sprintf(
                        "Logger:\nError Level - %s\nError Code - %s\nMessage - %s",
                        $error_level,
                        $e->getCode(),
                        $e->getMessage()
                    ))
                    ->setPeerId($id);
            }

            return $message->setMessage(
                sprintf(
                    "Logger:\nError Level - %s\nMessage - %s",
                    $error_level,
                    $e
                ))
                ->setPeerId($id);

        });
    }
}