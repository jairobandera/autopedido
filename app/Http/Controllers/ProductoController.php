<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Ingrediente;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::where('activo', 1)->with(['categorias', 'ingredientes']);

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . $busqueda . '%']);
        }

        $productos = $query->paginate(10);

        return view('Administrador.productos.index', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string|max:255',
            'categoria_ids' => 'required|array',
            'categoria_ids.*' => 'exists:categorias,id',
            'ingrediente_ids' => 'nullable|array',
            'ingrediente_ids.*' => 'exists:ingredientes,id',
        ]);

        //verificamos si ya existe un producto con ese nombre
        $existe = Producto::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->exists();

        if ($existe) {
            return redirect()
                ->route('productos.create')
                ->with('producto_duplicado', $request->nombre);
        }

        //asignamos placeholder si imagen está vacía
        $data = $request->only(['nombre', 'descripcion', 'precio', 'imagen']);
        $data['imagen'] = $request->imagen ?: 'https://via.placeholder.com/150';

        //creamos un nuevo producto
        $producto = Producto::create($data);
        $producto->categorias()->sync($request->categoria_ids);
        if ($request->ingrediente_ids) {
            $producto->ingredientes()->sync($request->ingrediente_ids);
        }

        return redirect()
            ->route('productos.create')
            ->with('productocreado', $request->nombre);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string|max:255',
            'categoria_ids' => 'required|array',
            'categoria_ids.*' => 'exists:categorias,id',
            'ingrediente_ids' => 'nullable|array',
            'ingrediente_ids.*' => 'exists:ingredientes,id',
        ]);

        $producto = Producto::findOrFail($id);

        //verificamos si otro producto ya tiene ese nombre
        $existe = Producto::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id', '!=', $producto->id)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('productos.edit', $producto->id)
                ->withInput()
                ->with('producto_duplicado', $request->nombre);
        }

        //asignamos placeholder si imagen está vacía
        $data = $request->only(['nombre', 'descripcion', 'precio', 'imagen']);
        $data['imagen'] = $request->imagen ?: 'https://via.placeholder.com/150';

        //actualizamos datos
        $producto->update($data);
        $producto->categorias()->sync($request->categoria_ids);
        $producto->ingredientes()->sync($request->ingrediente_ids ?? []);

        return redirect()
            ->route('productos.index')
            ->with('producto_editado', $producto->nombre);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->activo = 0;
        $producto->save();

        return redirect()
            ->route('productos.index')
            ->with('producto_deshabilitado', $producto->nombre);
    }

    public function create()
    {
        $categorias = Categoria::where('activo', 1)->get();
        $ingredientes = Ingrediente::where('activo', 1)->get();
        return view('Administrador.productos.create', compact('categorias', 'ingredientes'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('activo', 1)->get();
        $ingredientes = Ingrediente::where('activo', 1)->get();
        return view('Administrador.productos.edit', compact('producto', 'categorias', 'ingredientes'));
    }

    public function deshabilitadas()
    {
        $productos = Producto::where('activo', 0)->with(['categorias', 'ingredientes'])->paginate(10);
        return view('Administrador.productos.deshabilitadas', compact('productos'));
    }

    public function habilitar($id)
    {
        $producto = Producto::findOrFail($id);

        //verificamos si ya existe otro producto activo con el mismo nombre
        $existe = Producto::whereRaw('LOWER(nombre) = ?', [strtolower($producto->nombre)])
            ->where('activo', 1)
            ->exists();

        if ($existe) {
            return redirect()
                ->route('productos.deshabilitadas')
                ->with('error_habilitar', $producto->nombre);
        }

        $producto->activo = 1;
        $producto->save();

        return redirect()
            ->route('productos.index')
            ->with('producto_habilitado', $producto->nombre);
    }
}