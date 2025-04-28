<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingrediente::where('activo', 1);

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $busqueda . '%']);
        }

        $ingredientes = $query->paginate(10);

        return view('Administrador.ingredientes.index', compact('ingredientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        //verificamos si ya existe un ingrediente con ese nombre
        $existe = Ingrediente::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->exists();

        if ($existe) {
            return redirect()
                ->route('ingredientes.create')
                ->with('ingrediente_duplicado', $request->nombre);
        }

        //creamos un nuevo ingrediente
        Ingrediente::create($request->only(['nombre', 'descripcion']));

        return redirect()
            ->route('ingredientes.create')
            ->with('ingrediente_creado', $request->nombre);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $ingrediente = Ingrediente::findOrFail($id);

        //verificamos si otro ingrediente ya tiene ese nombre
        $existe = Ingrediente::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id', '!=', $ingrediente->id)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('ingredientes.edit', $ingrediente->id)
                ->withInput()
                ->with('ingrediente_duplicado', $request->nombre);
        }

        //actualizamos los datos
        $ingrediente->update($request->only(['nombre', 'descripcion']));

        return redirect()
            ->route('ingredientes.index')
            ->with('ingrediente_editado', $ingrediente->nombre);
    }

    public function destroy($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);
        $ingrediente->activo = 0;
        $ingrediente->save();

        return redirect()
            ->route('ingredientes.index')
            ->with('ingrediente_deshabilitado', $ingrediente->nombre);
    }

    public function create()
    {
        return view('Administrador.ingredientes.create');
    }

    public function edit($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);
        return view('Administrador.ingredientes.edit', compact('ingrediente'));
    }

    public function deshabilitadas()
    {
        $ingredientes = Ingrediente::where('activo', 0)->paginate(10);
        return view('Administrador.ingredientes.deshabilitadas', compact('ingredientes'));
    }

    public function habilitar($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);

        //verificamos si ya existe otro ingrediente activo con el mismo nombre
        $existe = Ingrediente::whereRaw('LOWER(nombre) = ?', [strtolower($ingrediente->nombre)])
            ->where('activo', 1)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('ingredientes.deshabilitadas')
                ->with('error_habilitar', $ingrediente->nombre);
        }

        $ingrediente->activo = 1;
        $ingrediente->save();

        return redirect()
            ->route('ingredientes.index')
            ->with('ingrediente_habilitado', $ingrediente->nombre);
    }
}