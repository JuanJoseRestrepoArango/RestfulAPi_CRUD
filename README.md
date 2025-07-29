# API RESTful de Restaurantes con Laravel y Docker

Esta es una API RESTful para gestionar restaurantes, desarrollada en Laravel y preparada para ejecutarse fácilmente usando Docker.

---

## Requisitos

- Tener instalado [Docker](https://www.docker.com/get-started)
- Tener instalado [Docker Compose](https://docs.docker.com/compose/install/)

---

## Instalación y ejecución

1. Clona este repositorio:

```
git clone https://github.com/JuanJoseRestrepoArango/RestfulAPi_CRUD.git
cd RestfulAPi_CRUD
``` 
2 .Construye y levanta los contenedores (la primera vez descargará y configurará todo automáticamente):

```
docker-compose up -d --build
```
¡Listo! El contenedor de la aplicación automáticamente:

- Copiará el archivo .env.example a .env

- Esperará que MySQL esté listo

- Generará una API_KEY única si no existe

- Instalará las dependencias con Composer si no están instaladas

- Ejecutará las migraciones de la base de datos

- Generará la clave de la aplicación Laravel

- Iniciará Apache y servirá la aplicación en el puerto 8000

---

## Acceder a la API

1. La API estará disponible en:

```
http://localhost:8000/api/restaurantes
```

---

## Endpoints disponibles

| Método | Ruta                        | Descripción                     |
|--------|-----------------------------|---------------------------------|
| GET    | /api/restaurantes           | Listar todos los restaurantes   |
| POST   | /api/restaurantes           | Crear un nuevo restaurante      |
| GET    | /api/restaurantes/{id}      | Obtener un restaurante por ID   |
| PUT    | /api/restaurantes/{id}      | Actualizar un restaurante       |
| PATCH  | /api/restaurantes/{id}      | Actualizar parcialmente         |
| DELETE | /api/restaurantes/{id}      | Eliminar un restaurante         |

---

## Autenticación

Para todas las peticiones se debe enviar el header:

```
X-API-KEY: <valor_de_api_key_generado>
```

El valor se genera automáticamente la primera vez que levantas el contenedor y queda guardado en el archivo .env.

Para ver el API_KEY puedes ejecutar:

```
docker exec -it api_restfull_laravel_app cat /var/www/html/.env | grep API_KEY
```

O puede encontrarla en el archivo .env.

---

### Headers recomendados para peticiones

| Key           | Value                  |
|---------------|------------------------|
| Content-Type  | application/json       |
| Accept        | application/json       |
| X-API-KEY     | <tu_api_key_aqui>      |

## Ejemplo de uso con Postman

Para crear un restaurante (POST):

json
```
{
    "nombre": "Restaurante Ejemplo",
    "direccion": "Calle Falsa 123",
    "telefono": "+1234567890"
}
```

Para actualizar parcialmente un restaurante (PATCH):

json
```
{
    "telefono": "+0987654321"
}
```

---

## Validaciones

- nombre: obligatorio, string, máximo 255 caracteres

- direccion: obligatorio, string, máximo 255 caracteres

- telefono: obligatorio, formato numérico con símbolos permitidos, máximo 20 caracteres

---

## Manejo de errores
La API devuelve respuestas JSON con esta estructura:

```
{
  "success": false,
  "message": "Descripción del error"
}
```
Y códigos HTTP adecuados (404 para no encontrado, 422 para validación, 500 para errores internos, etc).

---

## Estructura del proyecto

- app/DTO/RestauranteDTO.php — Objeto de transferencia de datos

- app/Exceptions/RestauranteNoEncontradoException.php — Excepción personalizada

- app/Helpers/ApiResponse.php — Helpers para respuestas JSON

- app/Http/Controllers/Api/RestauranteController.php — Controlador API

- app/Http/Middleware/ApiKeyMiddleware.php — Middleware para validar API Key

- app/Http/Requests/RestauranteRequest.php — Validaciones de request

- app/Http/Resources/RestauranteResource.php — Transformación de respuesta JSON

- app/Models/Restaurante.php — Modelo Eloquent

- app/Repositories/RestauranteRepository.php — Repositorio de acceso a datos

- app/Services/RestauranteService.php — Lógica de negocio

- database/migrations/xxxx_xx_xx_create_restaurantes_table.php — Migración base de datos

- routes/api.php — Definición de rutas API

---

## Docker

Dockerfile — Imagen PHP 8.3 con Apache y extensiones necesarias para Laravel

docker-compose.yml — Define servicios app (PHP+Apache) y db (MySQL 8.0)

entrypoint.sh — Script para preparar entorno y lanzar Apache

## Comandos útiles

- Ver logs del contenedor app:

```
docker logs -f api_restfull_laravel_app
```

- Ejecutar comandos artisan dentro del contenedor:

```
docker exec -it api_restfull_laravel_app php artisan migrate
```

- Detener y eliminar contenedores:

```
docker-compose down
```