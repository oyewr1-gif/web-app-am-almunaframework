<?php

/**
 * Helper untuk mencegah XSS (Cross-Site Scripting) saat mencetak data ke browser
 * 
 * @param string|null $string Data teks mentah yang akan dicetak
 * @return string Data yang sudah dikonversi menjadi entitas HTML aman
 */

// Gunakan untuk menggantikan echo pada INPUT
function esc(?string $string): string 
{
    if ($string === null) {
        return '';
    }
    
    // ENT_QUOTES memastikan tanda kutip tunggal (') dan ganda (") ikut di-encode secara aman
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Helper opsional: Shortcut untuk mencetak langsung (Sama dengan echo esc($str))
 */
function e(?string $string): void 
{
    echo esc($string);
}

/**
 * Membuat dan menyimpan CSRF Token ke dalam Session jika belum ada
 * 
 * @return string Token CSRF
 */
function csrf_token(): string 
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        // Membuat string acak yang aman secara kriptografi
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Membuat input field tersembunyi (hidden input) untuk form HTML
 * 
 * @return string Tag input HTML
 */
function csrf_field(): string 
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Memvalidasi apakah CSRF Token yang dikirim pengguna valid
 * 
 * @return bool True jika valid, False jika tidak
 */
function csrf_validate(): bool 
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Periksa request POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Menggunakan hash_equals untuk mencegah serangan Timing Attack
        return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
    
    return true; // Lewati jika bukan POST request
}
