<?php

namespace App\Http\Controllers\Api;

use App\DTO\RestauranteDTO;
use App\Exceptions\RestauranteNoEncontradoException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RestauranteRequest;
use App\Http\Resources\RestauranteResource;
use App\Models\Restaurante;
use App\Services\RestauranteService;
use Illuminate\Database\QueryException;
use Throwable;

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
        try{
            $validacion = $request->validated();
        
            $dto = RestauranteDTO::fromArray($validacion);
            $restaurante = $this->servicio->crear($dto);

            return new RestauranteResource($restaurante);
        }catch(QueryException $query_error){
           return response()->json([
                'error' => 'Error al crear el restaurante: ' . $query_error->getMessage()
            ], 500);
        }catch(Throwable $error){
            return response()->json([
                'error' => 'Error inesperado: ' . $error->getMessage()
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $restaurante = $this->servicio->mostrar($id);

            return new RestauranteResource($restaurante);
        } catch (RestauranteNoEncontradoException $error_restaurante) {
            return response()->json([
                'error' => $error_restaurante->getMessage()
            ], 404);
        } catch (Throwable $error) {
            return response()->json([
                'error' => 'Error inesperado: ' . $error->getMessage()
            ], 500);
        }       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestauranteRequest $request,  $id)
    {   
        try {
            if($request->isMethod('PATCH')) {
           
                $dto = RestauranteDTO::fromPatch($this->servicio->mostrar($id),$request->validated());
            
            }else{
                $dto = RestauranteDTO::fromArray($request->validated());
            }

            $restauranteActualizado = $this->servicio->actualizar($id, $dto);

            return new RestauranteResource($restauranteActualizado); 
        }catch (RestauranteNoEncontradoException $error_restaurante) {
            return response()->json([
                'error' => $error_restaurante->getMessage()
            ], 404);
        }catch(QueryException $query_error){
           return response()->json([
                'error' => 'Error al crear el restaurante: ' . $query_error->getMessage()
            ], 500);
        } catch (Throwable $error) {
            return response()->json([
                'error' => 'Error inesperado: ' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->servicio->eliminar($id);
            return response()->noContent();
        } catch (RestauranteNoEncontradoException $error_restaurante) {
            return response()->json([
                'error' => $error_restaurante->getMessage()
            ], 404);
        } catch (Throwable $error) {
            return response()->json([
                'error' => 'Error inesperado: ' . $error->getMessage()
            ], 500);
        }  
    }
}
