<?

class funciones{
    public static function validarDNI($dni_str) {
        // Eliminar espacios en blanco y convertir a mayúsculas
        $dni_str = strtoupper(str_replace(' ', '', $dni_str));
    
        // Extraer el número y la letra del DNI
        $numero = substr($dni_str, 0, -1);
        $letra_introducida = substr($dni_str, -1);
    
        // Array con las letras posibles del DNI
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $letra_correcta = $letras[$numero % 23];
    
        // Verificar si la letra introducida es correcta
        return $letra_correcta === $letra_introducida;
    }
}