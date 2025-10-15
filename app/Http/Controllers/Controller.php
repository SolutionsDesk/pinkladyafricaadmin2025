<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- Add this
use Illuminate\Foundation\Validation\ValidatesRequests; // <-- And this

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests; // <-- And this
}
