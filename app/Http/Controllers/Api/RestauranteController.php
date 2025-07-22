<?php

namespace App\Http\Controllers\Api;

use App\DTO\RestauranteDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RestauranteRequest;
use App\Http\Resources\RestauranteResource;
use App\Models\Restaurante;
use App\Services\RestauranteService;


class RestauranteController extends Controller
{
    public function __construct(private RestauranteService $servicio){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RestauranteResource::collection($this->servicio->listar());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestauranteRequest $request)
    {
        $validacion = $request->validated();
        
        $dto = RestauranteDTO::fromArray($validacion);
        $restaurante = $this->servicio->crear($dto);

        return new RestauranteResource($restaurante);
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurante $restaurante)
    {
        return new RestauranteResource($this->servicio->mostrar($restaurante->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestauranteRequest $request, Restaurante $restaurante)
    {   
        if($request->isMethod('PATCH')) {
           
            $dto = RestauranteDTO::fromPatch($restaurante,$request->validated());
            
        }else{
            $dto = RestauranteDTO::fromArray($request->validated());
        }
        $restauranteActualizado = $this->servicio->actualizar($restaurante->id, $dto);

        return new RestauranteResource($restauranteActualizado);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurante $restaurante)
    {
        $this->servicio->eliminar($restaurante->id);
        return response()->noContent();
    }
}
