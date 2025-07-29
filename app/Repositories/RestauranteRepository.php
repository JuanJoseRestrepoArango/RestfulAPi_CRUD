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

    public function findForUpdate($id){
        return Restaurante::where('id', $id)->lockForUpdate()->first();
    }
    public function buscarPorTelefono($telefono){
        return Restaurante::where('telefono', $telefono)->first();
    }

    public function update($restaurante,$datos){
        $restaurante->update($datos);
        return $restaurante;
    }
    public function delete($restaurante){
        return $restaurante->delete();
    }
}