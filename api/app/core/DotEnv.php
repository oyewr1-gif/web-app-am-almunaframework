<?php

class DotEnv {
    public static function load($path) {
        $file = $path . '.env';

        if (!file_exists($file)) {
            return false;
        }

        // Baca file baris demi baris
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Abaikan jika baris tersebut adalah komentar (#)
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Pisahkan antara KEY dan VALUE berdasarkan tanda sama dengan (=)
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Masukkan ke dalam environment system PHP
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
