<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048', //validacion para archivo de imagen
            'imagen_url' => 'nullable|url|max:255', //validacion para URL
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

        //procesamos la imagen
        $data = $request->only(['nombre', 'descripcion', 'precio']);
        $defaultPlaceholder = 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';

        if ($request->hasFile('imagen')) {
            //subir imagen desde el sistema
            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $data['imagen'] = '/storage/' . $path; //guardar ruta relativa
        } elseif ($request->filled('imagen_url')) {
            //usar URL proporcionada
            $data['imagen'] = $request->imagen_url;
        } else {
            //usar placeholder por defecto
            $data['imagen'] = $defaultPlaceholder;
        }

        //crear el producto
        $producto = Producto::create($data);

        //sincronizar categorías
        $producto->categorias()->sync($request->categoria_ids);

        //sincronizar ingredientes obligatorios
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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048', //validacion para archivo de imagen
            'imagen_url' => 'nullable|string|max:255',
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

        //procesamos la imagen
        $data = $request->only(['nombre', 'descripcion', 'precio']);
        $defaultPlaceholder = 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';

        if ($request->hasFile('imagen')) {
            //subimos nueva imagen desde el sistema
            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $data['imagen'] = '/storage/' . $path;

            //eliminamos imagen anterior si no es el placeholder o una URL
            if ($producto->imagen && !filter_var($producto->imagen, FILTER_VALIDATE_URL) && $producto->imagen !== $defaultPlaceholder) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $producto->imagen));
            }
        } elseif ($request->filled('imagen_url')) {
            //usamos URL o ruta proporcionada
            $data['imagen'] = $request->imagen_url;
        } elseif ($request->has('imagen_url') && trim($request->imagen_url) === '') {
            //si el campo imagen_url fue enviado pero está vacío, usar placeholder
            $data['imagen'] = $defaultPlaceholder;

            //borrar imagen anterior si era local y no el placeholder
            if ($producto->imagen && !filter_var($producto->imagen, FILTER_VALIDATE_URL) && $producto->imagen !== $defaultPlaceholder) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $producto->imagen));
            }
        } else {
            //mantener imagen existente
            $data['imagen'] = $producto->imagen;
        }


        //actualizar los datos
        $producto->update($data);

        //sincronizar categorías
        $producto->categorias()->sync($request->categoria_ids);

        //sincronizar los ingredientes con obligatoriedad
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
