# Astaroth

Методы ***статического класа*** `Utils`:
```php
    /**
    * Транслитерация строк
    */
    public static function translit(string $str): string
    
    /**
     * Удаляет из строки самую первую подстроку
     */
    public static function removeFirstWord($text): string|bool

    /**
     * Выборка необходимой строки по ключу
     */
    public static function getWord(string $string, int $substring): string|bool
    
    /**
     * Такой же explode только на несколько символов
     */
    public static function multiExplode($delimiters, $string): array|bool

    /**
     * Является ли массив ассоциативным
     */
    public static function isAssoc(array $arr): bool

    /**
     * Является ли массив последовательным
     */
    public static function isSeq(array $arr): bool

    /**
     * Является ли массив многомерным
     */
    public static function isMulti(array $array): bool

    /**
     * Регулярка, выбирает все айдишники из текста
     */
    public static function regexId(string $string): array|bool

    /**
     * Простой дебаг в stdout
     */
    public static function var_dumpToStdout($data)

    /**
     * Булев в смайлы
     */
    public static function boolToSmile($bool): string

    /**
     * Строка в unixtime
     * 1 час
     * unixtime + 3600
     */
    public static function strTime(string $string): int|false
```

