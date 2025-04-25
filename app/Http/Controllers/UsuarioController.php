<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::where('activo', 1);

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $busqueda . '%']);
        }

        $usuarios = $query->paginate(10);

        return view('Administrador.usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contrasena' => 'required|string|min:8',
            'rol' => 'required|in:Administrador,Cajero,Cocina,Cliente',
        ]);

        //verificamos si ya existe un usuario con ese nombre (ignorando mayúsculas/minúsculas)
        $existe = Usuario::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->exists();

        if ($existe) {
            return redirect()
                ->route('usuarios.create')
                ->with('usuario_duplicado', $request->nombre);
        }

        //creamos un nuevo usuario
        Usuario::create($request->only(['nombre', 'contrasena', 'rol']));

        return redirect()
            ->route('usuarios.create')
            ->with('usuario_creado', $request->nombre);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contrasena' => 'nullable|string|min:8',
            'rol' => 'required|in:Administrador,Cajero,Cocina,Cliente',
        ]);

        $usuario = Usuario::findOrFail($id);

        //verificamos si otro usuario ya tiene ese nombre (ignorando mayúsculas/minúsculas)
        $existe = Usuario::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id', '!=', $usuario->id)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('usuarios.edit', $usuario->id)
                ->withInput()
                ->with('usuario_duplicado', $request->nombre);
        }

        //actualizamos los datos
        $usuario->nombre = $request->nombre;
        $usuario->rol = $request->rol;
        if ($request->filled('contrasena')) {
            $usuario->contrasena = $request->contrasena;
        }
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('usuario_editado', $usuario->nombre);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->activo = 0;
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('usuario_deshabilitado', $usuario->nombre);
    }

    public function create()
    {
        return view('Administrador.usuarios.create');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('Administrador.usuarios.edit', compact('usuario'));
    }

    public function deshabilitadas()
    {
        $usuarios = Usuario::where('activo', 0)->paginate(10);
        return view('Administrador.usuarios.deshabilitadas', compact('usuarios'));
    }

    public function habilitar($id)
    {
        $usuario = Usuario::findOrFail($id);

        //verificamos si ya existe otro usuario activo con el mismo nombre
        $existe = Usuario::whereRaw('LOWER(nombre) = ?', [strtolower($usuario->nombre)])
            ->where('activo', 1)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('usuarios.deshabilitadas')
                ->with('error_habilitar', $usuario->nombre);
        }

        $usuario->activo = 1;
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('usuario_habilitado', $usuario->nombre);
    }
}