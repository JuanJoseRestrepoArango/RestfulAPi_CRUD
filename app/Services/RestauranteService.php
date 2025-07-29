<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\RestauranteRepository;
use App\Exceptions\RestauranteNoEncontradoException;

class RestauranteService{
    public function __construct(private RestauranteRepository $repository){}

    public function listar(){
        return $this->repository->all();
    }

    public function crear($dto){
        $existe = $this->repository->buscarPorTelefono($dto->telefono);
        if($existe){
            throw new Exception('Ya existe un restaurante con este telefono');
        }
        return $this->repository->create($dto->toArray());
    }

    public function mostrar($id){
        $restaurante =  $this->repository->find($id);
        if(!$restaurante){
            throw new RestauranteNoEncontradoException();
        }
        return $restaurante;
    }

    public function actualizar($id,$dto){

        return DB::transaction(function () use ($id, $dto) {
            $restaurante = $this->repository->findForUpdate($id);
            if (!$restaurante) {
                throw new RestauranteNoEncontradoException();
            }
            return $this->repository->update($restaurante, $dto->toArray());
        });
    
    }

    public function eliminar($id){
        $restaurante = $this->repository->find($id);
        if(!$restaurante){
            throw new RestauranteNoEncontradoException();
        }
        return $this->repository->delete($restaurante);
    }


}