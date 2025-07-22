<?php

namespace App\Services;

use App\Repositories\RestauranteRepository;

class RestauranteService{
    public function __construct(private RestauranteRepository $repository){}

    public function listar(){
        return $this->repository->all();
    }

    public function crear($dto){
        return $this->repository->create($dto->toArray());
    }

    public function mostrar($id){
        return $this->repository->find($id);
    }

    public function actualizar($id,$dto){
        $restaurante = $this->repository->find($id);
        return $this->repository->update($restaurante,$dto->toArray());
    }

    public function eliminar($id){
        $restaurante = $this->repository->find($id);
        return $this->repository->delete($restaurante);
    }


}