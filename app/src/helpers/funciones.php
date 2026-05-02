<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function logger() : Logger {
    static $log=null;
    if($log===null){
        $log = new Logger('app');
        $log->pushHandler(new StreamHandler(__DIR__.'/../../logs/app.log', Logger::DEBUG));
    }
    return $log;
}

/**
 * Devuelve clases Tailwind de color según el género del libro.
 */
function colorGenero(string $genero): array {
    $generoLower = mb_strtolower(trim($genero));

    $colores = [
        'ficción'       => ['bg' => 'bg-purple-100',  'text' => 'text-purple-700',  'border' => 'border-purple-300'],
        'ficcion'       => ['bg' => 'bg-purple-100',  'text' => 'text-purple-700',  'border' => 'border-purple-300'],
        'fantasía'      => ['bg' => 'bg-indigo-100',  'text' => 'text-indigo-700',  'border' => 'border-indigo-300'],
        'fantasia'      => ['bg' => 'bg-indigo-100',  'text' => 'text-indigo-700',  'border' => 'border-indigo-300'],
        'ciencia ficción' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700',    'border' => 'border-cyan-300'],
        'ciencia ficcion' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700',    'border' => 'border-cyan-300'],
        'terror'        => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'border' => 'border-red-300'],
        'horror'        => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'border' => 'border-red-300'],
        'romance'       => ['bg' => 'bg-pink-100',    'text' => 'text-pink-700',    'border' => 'border-pink-300'],
        'aventura'      => ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'border' => 'border-orange-300'],
        'aventuras'     => ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'border' => 'border-orange-300'],
        'misterio'      => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'border' => 'border-amber-300'],
        'thriller'      => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'border' => 'border-amber-300'],
        'historia'      => ['bg' => 'bg-yellow-100',  'text' => 'text-yellow-700',  'border' => 'border-yellow-300'],
        'histórico'     => ['bg' => 'bg-yellow-100',  'text' => 'text-yellow-700',  'border' => 'border-yellow-300'],
        'historico'     => ['bg' => 'bg-yellow-100',  'text' => 'text-yellow-700',  'border' => 'border-yellow-300'],
        'poesía'        => ['bg' => 'bg-rose-100',    'text' => 'text-rose-700',    'border' => 'border-rose-300'],
        'poesia'        => ['bg' => 'bg-rose-100',    'text' => 'text-rose-700',    'border' => 'border-rose-300'],
        'biografía'     => ['bg' => 'bg-teal-100',    'text' => 'text-teal-700',    'border' => 'border-teal-300'],
        'biografia'     => ['bg' => 'bg-teal-100',    'text' => 'text-teal-700',    'border' => 'border-teal-300'],
        'educación'     => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'border' => 'border-blue-300'],
        'educacion'     => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'border' => 'border-blue-300'],
        'infantil'      => ['bg' => 'bg-lime-100',    'text' => 'text-lime-700',    'border' => 'border-lime-300'],
        'comedia'       => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-300'],
        'humor'         => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-300'],
        'drama'         => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'border' => 'border-slate-300'],
        'autoayuda'     => ['bg' => 'bg-green-100',   'text' => 'text-green-700',   'border' => 'border-green-300'],
    ];

    return $colores[$generoLower] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300'];
}

/**
 * Devuelve un texto relativo tipo "hace 3 días", "hace 2 horas", etc.
 */
function tiempoRelativo(?string $fecha): string {
    if (empty($fecha)) return '';

    $ahora = new DateTime();
    $created = new DateTime($fecha);
    $diff = $ahora->diff($created);

    if ($diff->y > 0) return "hace " . $diff->y . ($diff->y == 1 ? " año" : " años");
    if ($diff->m > 0) return "hace " . $diff->m . ($diff->m == 1 ? " mes" : " meses");
    if ($diff->d > 0) return "hace " . $diff->d . ($diff->d == 1 ? " día" : " días");
    if ($diff->h > 0) return "hace " . $diff->h . ($diff->h == 1 ? " hora" : " horas");
    if ($diff->i > 0) return "hace " . $diff->i . ($diff->i == 1 ? " minuto" : " minutos");

    return "ahora mismo";
}

/**
 * Devuelve un array de badges (emoji + texto) según la actividad del usuario.
 */
function obtenerBadges(int $librosSubidos, int $librosLeidos, int $valoraciones): array {
    $badges = [];

    // Badges por libros subidos
    if ($librosSubidos >= 1)  $badges[] = ['emoji' => '📖', 'texto' => 'Contribuidor',   'color' => 'bg-blue-100 text-blue-700'];
    if ($librosSubidos >= 5)  $badges[] = ['emoji' => '📚', 'texto' => 'Bibliotecario',   'color' => 'bg-indigo-100 text-indigo-700'];
    if ($librosSubidos >= 10) $badges[] = ['emoji' => '🏛️', 'texto' => 'Gran biblioteca', 'color' => 'bg-purple-100 text-purple-700'];

    // Badges por libros leídos (prestados)
    if ($librosLeidos >= 1)  $badges[] = ['emoji' => '📕', 'texto' => 'Lector',       'color' => 'bg-green-100 text-green-700'];
    if ($librosLeidos >= 5)  $badges[] = ['emoji' => '🐛', 'texto' => 'Ratón de biblioteca', 'color' => 'bg-emerald-100 text-emerald-700'];
    if ($librosLeidos >= 10) $badges[] = ['emoji' => '🧠', 'texto' => 'Devoralibros', 'color' => 'bg-teal-100 text-teal-700'];

    // Badges por valoraciones
    if ($valoraciones >= 1)  $badges[] = ['emoji' => '⭐', 'texto' => 'Crítico',       'color' => 'bg-yellow-100 text-yellow-700'];
    if ($valoraciones >= 5)  $badges[] = ['emoji' => '🌟', 'texto' => 'Gran crítico',  'color' => 'bg-amber-100 text-amber-700'];

    return $badges;
}
