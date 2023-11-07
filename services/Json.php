<?php

namespace app\services;


class Json
{
    public static function saveToFile(string $filePath, $data): bool
    {
        return file_put_contents($filePath, json_encode($data));
    }

}
