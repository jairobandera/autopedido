<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::where('activo', 1);

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $busqueda . '%']);
        }

        $categorias = $query->get();

        return view('Administrador.categorias.index', compact('categorias'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        // Verificar si ya existe una categoría con ese nombre (ignorando mayúsculas/minúsculas)
        $existe = Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->exists();

        if ($existe) {
            return redirect()
                ->route('categorias.create')
                ->with('categoria_duplicada', $request->nombre);
        }

        // Crear nueva categoría
        Categoria::create($request->only('nombre'));

        return redirect()
            ->route('categorias.create')
            ->with('categoria_creada', $request->nombre);
    }


    public function show($id)
    {
        $categoria = Categoria::with('productos')->find($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
        }

        return response()->json($categoria);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $categoria = Categoria::findOrFail($id);

        // Verificar si otra categoría ya tiene ese nombre (ignorando mayúsculas/minúsculas)
        $existe = Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id', '!=', $categoria->id)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('categorias.edit', $categoria->id)
                ->withInput()
                ->with('categoria_duplicada', $request->nombre);
        }

        // Guardar si no hay duplicado
        $categoria->nombre = $request->nombre;
        $categoria->save();

        return redirect()
            ->route('categorias.index')
            ->with('categoria_editada', $categoria->nombre);
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->activo = 0;
        $categoria->save();

        return redirect()
            ->route('categorias.index')
            ->with('categoria_deshabilitada', $categoria->nombre);

    }

    public function create()
    {
        return view('Administrador.categorias.create');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('Administrador.categorias.edit', compact('categoria'));
    }

    public function buscar(Request $request)
    {
        $query = Categoria::where('activo', 1);

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $busqueda . '%']);
        }

        $categorias = $query->get();

        return view('Administrador.categorias.index', compact('categorias'));
    }

    public function deshabilitadas()
    {
        $categorias = Categoria::where('activo', 0)->get();
        return view('Administrador.categorias.deshabilitadas', compact('categorias'));
    }

    public function habilitar($id)
    {
        $categoria = Categoria::findOrFail($id);

        // Verificar si ya existe otra categoría activa con el mismo nombre
        $existe = Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($categoria->nombre)])
            ->where('activo', 1)
            ->exists();

        if ($existe) {
            return redirect()->route('categorias.deshabilitadas')
                ->with('error_habilitar', $categoria->nombre);
        }

        $categoria->activo = 1;
        $categoria->save();

        return redirect()
            ->route('categorias.index')
            ->with('categoria_habilitada', $categoria->nombre);

    }





}
