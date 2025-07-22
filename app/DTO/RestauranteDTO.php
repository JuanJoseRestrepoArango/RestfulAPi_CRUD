<?php

namespace App\DTO;

class RestauranteDTO{

    public function __construct(public string $nombre,public string $direccion,public string $telefono){}

    public static function fromArray(array $datos){
        return new self(
            $datos['nombre'],
            $datos['direccion'],
            $datos['telefono']
        );
    }

    public static function fromPatch($datosViejos,array $datos){
        $datosActuales = $datosViejos->toArray();
        $datosNuevos = array_merge($datosActuales, $datos);
        return new self(
            $datosNuevos['nombre'],
            $datosNuevos['direccion'],
            $datosNuevos['telefono']
        );
    }

    public function toArray(){
        return get_object_vars($this);
    }
}