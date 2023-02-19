<?php

namespace app\System\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Config {

    private static string $model = \app\System\Models\Config::class;
    private static ?array $config = null;

    public static function getValue(string $name, mixed $default = null): array|string|null {
        $config = self::getConfig();
        if (str_contains($name, '*')) {
            $data = [];
            foreach ($config as $key => $vo) {
                if (Str::is($name, $key)) {
                    $data[$key] = $vo;
                }
            }
            return $data ?: $default;
        }
        return $config[$name] ?: $default;
    }

    public static function setValue(string $name, mixed $value): void {
        (new self::$model)->updateOrInsert(
            ["name" => $name],
            ["value" => is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $value]
        );
    }

    private static function getConfig(): ?array {
        if (self::$config) {
            return self::$config;
        }
        $list = (new self::$model)->query()->get();
        foreach ($list as $item) {
            self::$config[$item->name] = $item->value;
        }
        return self::$config;
    }

}