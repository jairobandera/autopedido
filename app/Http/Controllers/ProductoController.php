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
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string|max:255',
            'categoria_ids' => 'required|array|min:1',
            'categoria_ids.*' => 'exists:categorias,id',
            'ingrediente_ids' => 'nullable|array',
            'ingrediente_ids.*' => 'exists:ingredientes,id',
            'ingrediente_obligatorio' => 'nullable|array',
            'ingrediente_obligatorio.*' => 'in:1',
        ]);

        //verificamos si ya existe un producto con ese nombre
        if (Producto::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->exists()) {
            return redirect()
                ->route('productos.create')
                ->withInput()
                ->with('producto_duplicado', $request->nombre);
        }

        //asignamos placeholder si imagen está vacía
        $data = $request->only(['nombre', 'descripcion', 'precio', 'imagen']);
        $data['imagen'] = $request->imagen ?: 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';

        //creamos el producto
        $producto = Producto::create($data);

        //sincronizamos categorías
        $producto->categorias()->sync($request->categoria_ids);

        //sincronizamos ingredientes con obligatoriedad
        if ($request->ingrediente_ids) {
            $syncData = [];
            foreach ($request->ingrediente_ids as $ingrediente_id) {
                $syncData[$ingrediente_id] = [
                    'es_obligatorio' => isset($request->ingrediente_obligatorio[$ingrediente_id]) ? 1 : 0
                ];
            }
            $producto->ingredientes()->sync($syncData);
        }

        return redirect()->route('productos.index')->with('producto_creado', $producto->nombre);

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
            'ingrediente_obligatorio' => 'nullable|array',
            'ingrediente_obligatorio.*' => 'in:1',
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
        $data['imagen'] = $request->imagen ?: 'https://cdn-icons-png.flaticon.com/512/1404/1404945.png';

        //actualizamos los datos
        $producto->update($data);

        //sincronizamos categorías
        $producto->categorias()->sync($request->categoria_ids);

        //sincronizamos los ingredientes con obligatoriedad
        if ($request->ingrediente_ids) {
            $syncData = [];
            foreach ($request->ingrediente_ids as $ingrediente_id) {
                $syncData[$ingrediente_id] = [
                    'es_obligatorio' => isset($request->ingrediente_obligatorio[$ingrediente_id]) ? 1 : 0
                ];
            }
            $producto->ingredientes()->sync($syncData);
        } else {
            $producto->ingredientes()->sync([]);
        }

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
        $producto = Producto::with(['categorias', 'ingredientes'])->findOrFail($id);
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
