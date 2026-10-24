<?php

class DotEnv 
{
    /**
     * Jalur (path) ke file .env
     */
    protected $path;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s file tidak ditemukan.', $path));
        }
        $this->path = $path;
    }

    public function load(): void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file tidak dapat dibaca.', $this->path));
        }

        // Membaca file baris demi baris ke dalam array
        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Abaikan jika baris merupakan komentar (diawali tanda #)
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Pastikan baris mengandung tanda sama dengan (=)
            if (strpos($line, '=') !== false) {
                // Memisahkan key dan value berdasarkan tanda = pertama
                list($name, $value) = explode('=', $line, 2);
                
                $name = trim($name);
                $value = trim($value);

                // Bersihkan tanda kutip pembungkus jika ada (misal: "value" atau 'value')
                $value = trim($value, '"\'');

                // Daftarkan ke sistem environment PHP jika belum ada
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }
}
