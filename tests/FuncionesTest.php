<?php
require_once('../html/funciones.php');
use PHPUnit\Framework\TestCase;

class FuncionesTest extends TestCase {
    /**
     * @dataProvider dniProvider
     */
    public function testValidarDNI($dni, $expected) {
        $this->assertEquals($expected, Funciones::validarDNI($dni));
    }

    public function dniProvider() {
        return [
            ['12345678Z', true],
            ['87654321X', true],
            ['12345678A', false], // Letra incorrecta
            ['00000000T', true], // Casos límites
            ['00000001R', true], // Casos límites
            ['11111111H', true],
            ['22222222J', true],
            ['33333333P', true],
            ['44444444Z', true],
            ['55555555Q', true],
            ['66666666S', true],
            ['77777777M', true],
            ['88888888Y', true],
            ['99999999R', true],
            ['abcdefghijk', false], // Formato incorrecto
            ['', false], // Vacío
        ];
    }
}
?>