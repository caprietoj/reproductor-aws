<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index()
    {
        $s3 = Storage::disk('s3');
        $directories = [
            '1. Introducción',
            '2. Iniciando Proyecto',
            '3. CRUD (Create Read Update Delete)',
            '4. Session  Login  JWT',
            '5. Motor de Base de datos MySQL  MongoDB',
            '6. Documentando API OpenAPI  Swagger',
            '7. Testing (Jest) Pruebas',
            '8. Despliegue de proyecto',
            '9. TypeScript',
        ];

        $videos = [];

        foreach ($directories as $directory) {
            // Usa 'allFiles' o 'files' según sea necesario para obtener los archivos dentro del directorio
            $filesInDirectory = $s3->allFiles($directory);

            // Filtra solo archivos .mp4
            $mp4FilesInDirectory = array_filter($filesInDirectory, function ($file) {
                return Str::endsWith($file, '.mp4');
            });

            // Genera URLs para los archivos .mp4
            $videos[$directory] = array_map(function ($file) use ($s3) {
                return $s3->url($file);
            }, $mp4FilesInDirectory);
        }

        return view('dashboard')->with('videos', $videos);
    }
}
