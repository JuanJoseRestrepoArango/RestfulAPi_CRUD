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
use App\Swagger\RestauranteSchemas;


/**
 * @OA\Info(
 *     title="API de Restaurantes",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="api_key",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY",
 *     description="Clave API para autorización"
 * )
 *
 * @OA\Tag(
 *     name="Restaurantes",
 *     description="Operaciones sobre restaurantes"
 * )
 */


class RestauranteController extends Controller
{

    public function __construct(private RestauranteService $servicio){}

    /**
     * @OA\Get(
     *     path="/api/restaurantes",
     *     tags={"Restaurantes"},
     *     summary="Listar restaurantes",
     *     security={{"api_key":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de restaurantes",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Operacion Exitosa"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="El Cielo"),
     *                     @OA\Property(property="direccion", type="string", example="Calle 10 # 5-67"),
     *                     @OA\Property(property="telefono", type="string", example="3001112233"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        return ApiResponse::exito(RestauranteResource::collection($this->servicio->listar()));
    }

    /**
     * @OA\Post(
     *     path="/api/restaurantes",
     *     tags={"Restaurantes"},
     *     summary="Crear un restaurante",
     *     security={{"api_key":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "direccion", "telefono"},
     *             @OA\Property(property="nombre", type="string", example="El Cielo"),
     *             @OA\Property(property="direccion", type="string", example="Cra 9 # 20-15"),
     *             @OA\Property(property="telefono", type="string", example="3001112233")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurante creado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Restaurante creado correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="El Cielo"),
     *                 @OA\Property(property="direccion", type="string", example="Calle 10 # 5-67"),
     *                 @OA\Property(property="telefono", type="string", example="3001112233"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     )
     * )
     */

    public function store(RestauranteRequest $request)
    {
        
        $validacion = $request->validated();
    
        $dto = RestauranteDTO::fromArray($validacion);
        $restaurante = $this->servicio->crear($dto);

        return ApiResponse::exito(new RestauranteResource($restaurante), 'Restaurante creado correctamente');
    
        
    }

  /**
     * @OA\Get(
     *     path="/api/restaurantes/{id}",
     *     tags={"Restaurantes"},
     *     summary="Obtener un restaurante por ID",
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del restaurante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurante encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Operacion Exitosa"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="El Cielo"),
     *                 @OA\Property(property="direccion", type="string", example="Calle 10 # 5-67"),
     *                 @OA\Property(property="telefono", type="string", example="3001112233"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        
        $restaurante = $this->servicio->mostrar($id);

        return ApiResponse::exito(new RestauranteResource($restaurante));
          
    }

    /**
     * @OA\Put(
     *     path="/api/restaurantes/{id}",
     *     summary="Actualizar completamente un restaurante",
     *     tags={"Restaurantes"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "direccion", "telefono"},
     *             @OA\Property(property="nombre", type="string", example="El Cielo"),
     *             @OA\Property(property="direccion", type="string", example="Cra 9 # 20-15"),
     *             @OA\Property(property="telefono", type="string", example="3001112233")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurante actualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Restaurante actualizado correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="El Cielo"),
     *                 @OA\Property(property="direccion", type="string", example="Calle 10 # 5-67"),
     *                 @OA\Property(property="telefono", type="string", example="3001112233"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Patch(
     *     path="/api/restaurantes/{id}",
     *     summary="Actualizar parcialmente un restaurante",
     *     tags={"Restaurantes"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "direccion", "telefono"},
     *             @OA\Property(property="nombre", type="string", example="El Cielo"),
     *             @OA\Property(property="direccion", type="string", example="Cra 9 # 20-15"),
     *             @OA\Property(property="telefono", type="string", example="3001112233")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurante actualizado parcialmente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Restaurante actualizado correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="El Cielo"),
     *                 @OA\Property(property="direccion", type="string", example="Calle 10 # 5-67"),
     *                 @OA\Property(property="telefono", type="string", example="3001112233"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/restaurantes/{id}",
     *     summary="Eliminar un restaurante",
     *     tags={"Restaurantes"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Restaurante eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Error al eliminar el restaurante"
     *     )
     * )
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
