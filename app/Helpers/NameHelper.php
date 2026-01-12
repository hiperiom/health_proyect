<?php

namespace App\Helpers;

class NameHelper
{
    /**
     * Extract the first name from a full name string.
     *
     * @param string $fullNameString
     * @return string
     */
    public static function extractFirstName(string $fullNameString): string
    {
        // 1. Limpiar espacios iniciales/finales
        $fullNameString = trim($fullNameString);

        // 2. Dividir la cadena en palabras (usando uno o más espacios como delimitador)
        $words = preg_split('/\s+/', $fullNameString);

        // 3. Devolver la primera palabra si existe, o una cadena vacía
        return $words[0] ?? '';
    }
}
