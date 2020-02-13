<?php

namespace src;

use iarcadia\time\Time;

/**
 * Class PreJsonParser
 * @package src
 */
class PreJsonParser
{
    /** @var string Content of the parser. */
    public $content;

    /**
     * PreJsonParser constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->content = PreJsonParser::getFileContent($filename);
    }

    /**
     * Convert the prejson file content to json.
     * @return string
     */
    public function json(): string
    {
        $this->replaceRefProperty();
        $this->replaceRefPropertySimpleArray();
        $this->replaceTimeObject();
        $this->replaceColorObject();
        $this->replaceAssetObject();

        $this->content = json_encode(json_decode($this->content), JSON_PRETTY_PRINT);

        return $this->content;
    }

    /**
     * Minify a file.
     * @param string $content
     * @return string
     */
    public static function minify(string $content): string
    {
        $content = preg_replace('/^\s+/m', '', $content);
        $content = preg_replace('/: /', ':', $content);
        $content = preg_replace('/\\n|\\r|\\t/', '', $content);

        return $content;
    }

    /**
     * Replace REFPROP with a unique slug.
     * @return void
     */
    protected function replaceRefProperty(): void
    {
        $this->content = preg_replace_callback('/^(\s+)"REFPROP\((.+?)\)":\s*"(.+?)"/m', function ($matches) {
            $t = $matches[1];
            $filename = $matches[2];
            $slug = $matches[3];

            return '"__file":"\\\\data\\\\' . $filename . '\\\\' . $filename . '.json",' . PHP_EOL .
                $t . '"__slug":"' . $slug . '"';
        }, $this->content);
    }

    /**
     * Replace REGPROP with a simple array.
     * @return void
     */
    protected function replaceRefPropertySimpleArray(): void
    {
        $this->content = preg_replace_callback('/"REFPROP\((.+?)\)":\s*\[(.+?)]/s', function ($matches) {
            $filename = $matches[1];
            $slugs = $matches[2];

            return '"__file":"\\\\data\\\\' . $filename . '\\\\' . $filename . '.json","__slugs":[' . $slugs . ']';
        }, $this->content);
    }

    /**
     * Replace TIMEOBJECT.
     * @return void
     */
    protected function replaceTimeObject(): void
    {
        $this->content = preg_replace_callback('/"TIMEOBJECT\((\d{1,2}):(\d{2}):(\d{2})\)"/', function ($matches) {
            $i = (int)$matches[1];
            $s = (int)$matches[2];
            $c = (int)$matches[3];
            $time = Time::create(0, $i, $s, $c * 10);

            return '{"ms": ' . $time->getTotalMilliseconds() . ', "formated": "' . $time->format('i:ss:cc') . '"}';
        }, $this->content);
    }

    /**
     * Replace COLOROBJECT.
     * @return void
     */
    protected function replaceColorObject(): void
    {
        $this->content = preg_replace_callback('/"COLOROBJECT\((\d{1,3}),(\d{1,3}),(\d{1,3})\)"/', function ($matches) {
            $r = (int)$matches[1];
            $g = (int)$matches[2];
            $b = (int)$matches[3];

            return '{"rgb":{"red":' . $r . ',"green":' . $g . ',"blue":' . $b . '},"hexadecimal":"' . sprintf('%02x%02x%02x', $r, $g, $b) . '"}';
        }, $this->content);
    }

    /**
     * Replace ASSETOBJECT.
     * @return void
     */
    protected function replaceAssetObject(): void
    {
        $this->content = preg_replace_callback('/"ASSETOBJECT\((.+?)\)"/', function ($matches) {
            $filename = $matches[1];
            $type = 'null';

            if (pathinfo($filename, PATHINFO_EXTENSION) === 'png') {
                $type = '"image/png"';
            }

            return '{"__type":' . $type . ',"__file":"\\\\assets\\\\img\\\\' . $filename . '"}';
        }, $this->content);
    }

    /**
     * Get prejson file content.
     * @param string $filename
     * @return string
     */
    public static function getFileContent(string $filename): string
    {
        return file_get_contents(__DIR__ . '/../data/' . $filename . '/' . $filename . '.prejson');
    }
}
