<?php

namespace App\Http\Controllers;

use App\Models\ReglaPunto;
use Illuminate\Http\Request;

class ReglaPuntoController extends Controller
{
    /**
     * Mostrar listado de tramos de puntos.
     */
    public function index()
    {
        // Carga ordenada de más reciente a más antiguo
        $reglas = ReglaPunto::orderBy('monto_min')->get();

        return view('Administrador.reglas-puntos.index', compact('reglas'));
    }

    /**
     * Almacenar nuevo tramo en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'monto_min' => 'required|numeric|min:0',
            'monto_max' => 'required|numeric|gt:monto_min',
            'puntos_base' => 'required|integer|min:1',
        ]);

        ReglaPunto::create($data);

        return redirect()
            ->route('reglas-puntos.index')
            ->with('success', 'Regla de puntos creada correctamente.');
    }

    /**
     * Mostrar el formulario para editar un tramo.
     */
    public function edit(ReglaPunto $reglas_punto)
    {
        return view('Administrador.reglas-puntos.edit', [
            'reglaPunto' => $reglas_punto
        ]);
    }

    /**
     * Actualizar el tramo especificado.
     */
    public function update(Request $request, ReglaPunto $reglas_punto)
    {
        $data = $request->validate([
            'monto_min' => 'required|numeric|min:0',
            'monto_max' => 'required|numeric|gt:monto_min',
            'puntos_base' => 'required|integer|min:1',
        ]);

        $reglas_punto->update($data);

        return redirect()
            ->route('reglas-puntos.index')
            ->with('success', 'Regla de puntos actualizada correctamente.');
    }

    /**
     * Eliminar un tramo.
     */
    public function destroy(ReglaPunto $reglas_punto)
    {
        $reglas_punto->delete();

        return redirect()
            ->route('reglas-puntos.index')
            ->with('success', 'Regla de puntos eliminada.');
    }
}
