<?php

namespace App\Http\Controllers;

use App\Services\PhpDocScanner;
// use Illuminate\Http\Request;

class PhpDocTestController extends Controller
{
    public function index(PhpDocScanner $scanner)
    {

        $paths = [
            app_path('Http/Controllers'),
            app_path('Http/Requests'),
            app_path('Http/Middleware'),
            app_path('Services'),
            app_path('Repositories'),
            app_path('Models'),
        ];

        $results = [];
        foreach ($paths as $path) {
            $results = array_merge($results, $scanner->scanDirectory($path));
        }

        foreach ($results as $doc) {
            dump([
                'Fichier' => $doc['file'],
                'Résumé' => $doc['summary'],
                'Description' => $doc['description'],
            ]);
        }
    }
}
