<?php

namespace App\Repositories;

use App\Models\Restaurante;

class RestauranteRepository{
    public function all(){
        return Restaurante::all();
    }
    public function create($datos){
        return Restaurante::create($datos);
    }
    public function find($id){
        return Restaurante::find($id);
    }
    public function update($restaurante,$datos){
        $restaurante->update($datos);
        return $restaurante;
    }
    public function delete($restaurante){
        return $restaurante->delete();
    }
}