<?php

namespace App\Exceptions;

use Exception;

class RestauranteNoEncontradoException extends Exception
{
    protected $message = 'Restaurante no encontrado';
}
