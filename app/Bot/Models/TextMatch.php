<?php

declare(strict_types=1);


namespace Bot\Models;


use Bot\bootstrap;

class TextMatch
{
    public function __construct(private string $haystack, private string $needle)
    {

    }

    /**
     * Проверка подстроки по шаблону
     * @return bool
     */
    public function compare(): bool
    {
        if (mb_substr($this->needle, 0, 1) === '|') {
            $needle = mb_substr($this->needle, 1);
            return $this->similarTo($needle, $this->haystack) >= bootstrap::SIMILAR_PERCENT;
        }

        if (mb_substr($this->needle, 0, 2) === "[|") {
            $needle = mb_substr($this->needle, 2);
            return $this->startAs($needle, $this->haystack);
        }

        if (mb_substr($this->needle, -2, 2) === "|]") {
            $needle = mb_substr($this->needle, 0, 2);
            return $this->endAs($needle, $this->haystack);
        }

        if (mb_substr($this->needle, 0, 1) === "{" && mb_substr($this->needle, -1, 1) === "}") {
            $needle = mb_substr($this->needle, 1, -1);
            return $this->contains($needle, $this->haystack);
        }

        return $this->needle === $this->haystack;
    }

    /**
     * Похоже на
     * @param string $text
     * @param $original
     * @return int
     */
    private function similarTo(string $text, $original): int
    {
        similar_text($text, $original, $percent);
        return (int)$percent;
    }

    /**
     * Начинается с
     * @param string $text
     * @param $original
     * @return bool
     */
    private function startAs(string $text, $original): bool
    {
        $word = explode(' ', $text)[0];
        $wordFromBot = explode(' ', $original)[0];
        return $word === $wordFromBot;
    }

    /**
     * Заканчивается на
     * @param string $text
     * @param string $original
     * @return bool
     */
    private function endAs(string $text, string $original): bool
    {
        $word = explode(' ', $text);
        $end_word = end($word);

        $wordFromBot = explode(' ', $original);
        $end_wordFromBot = end($wordFromBot);

        return $end_word === $end_wordFromBot;
    }

    /**
     * Содержит
     * @param string $text
     * @param string $original
     * @return bool
     */
    private function contains(string $text, string $original): bool
    {
        return str_contains($original, $text);
    }
}