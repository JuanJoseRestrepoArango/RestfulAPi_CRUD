<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\DTO\RestauranteDTO;
use App\Models\Restaurante;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\RestauranteService;
use Illuminate\Database\QueryException;
use App\Http\Requests\RestauranteRequest;
use App\Http\Resources\RestauranteResource;
use App\Exceptions\RestauranteNoEncontradoException;
use Exception;

class RestauranteController extends Controller
{

    public function __construct(private RestauranteService $servicio){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::exito(RestauranteResource::collection($this->servicio->listar()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestauranteRequest $request)
    {
        
        $validacion = $request->validated();
    
        $dto = RestauranteDTO::fromArray($validacion);
        $restaurante = $this->servicio->crear($dto);

        return ApiResponse::exito(new RestauranteResource($restaurante), 'Restaurante creado correctamente');
    
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
        $restaurante = $this->servicio->mostrar($id);

        return ApiResponse::exito(new RestauranteResource($restaurante));
          
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestauranteRequest $request,  $id)
    {   
        
        if($request->isMethod('PATCH')) {
        
            $dto = RestauranteDTO::fromPatch($this->servicio->mostrar($id)->toArray(),$request->validated());
        
        }else{
            $dto = RestauranteDTO::fromArray($request->validated());
        }

        $restauranteActualizado = $this->servicio->actualizar($id, $dto);

        return ApiResponse::exito(new RestauranteResource($restauranteActualizado),'Restaurante actualizado correctamente'); 
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       try{
            $this->servicio->eliminar($id);
            return response()->noContent();
       }catch(Exception $e){
            return ApiResponse::error('Error al eliminar el restaurante', 409);
       }
    }
}
