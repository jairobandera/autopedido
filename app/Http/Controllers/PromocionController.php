<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use Illuminate\Http\Request;
use App\Models\Producto;
use Carbon\Carbon;

class PromocionController extends Controller
{
    public function index(Request $request)
    {
        // 1) desactivamos expiradas
        $this->deactivateExpired();

        // 2) consultamos solo activas
        $query = Promocion::where('activo', 1);
        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $query->whereRaw('LOWER(nombre) LIKE ?', ["%{$busqueda}%"]);
        }
        $promociones = $query
            ->orderBy('created_at', 'desc')   // <-- o 'id','desc' si preferís
            ->paginate(10);

        return view('Administrador.promociones.index', compact('promociones'));
    }

    public function create()
    {
        return view('Administrador.promociones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descuento' => 'required|numeric|min:0|max:100',
            'codigo' => 'nullable|string|max:50',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'products' => 'array',
            'products.*' => 'integer|exists:productos,id',
        ]);

        // Duplicados por nombre
        $existe = Promocion::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])->exists();
        if ($existe) {
            return redirect()
                ->route('promociones.create')
                ->with('promocion_duplicada', $request->nombre);
        }

        // 1) Creo la promoción
        $promo = Promocion::create($request->only([
            'nombre',
            'descuento',
            'codigo',
            'fecha_inicio',
            'fecha_fin'
        ]));

        // 2) Asocio los productos (pivot producto_promocion)
        if ($request->filled('products')) {
            $promo->productos()->sync($request->input('products'));
        }

        // 3) Redirijo con éxito
        return redirect()
            ->route('promociones.create')
            ->with('promocion_creada', $promo->nombre);
    }

    public function edit($id)
    {
        $promo = Promocion::findOrFail($id);
        return view('Administrador.promociones.edit', compact('promo'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descuento' => 'required|numeric|min:0|max:100',
            'codigo' => 'nullable|string|max:50',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'products' => 'array',
            'products.*' => 'integer|exists:productos,id',
        ]);

        $promo = Promocion::findOrFail($id);

        // Validación de nombre duplicado
        $existe = Promocion::whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('id', '!=', $promo->id)
            ->exists();
        if ($existe) {
            return redirect()
                ->route('promociones.edit', $promo->id)
                ->withInput()
                ->with('promocion_duplicada', $request->nombre);
        }

        // 1) Actualizo los campos de la promoción
        $promo->fill($request->only([
            'nombre',
            'descuento',
            'codigo',
            'fecha_inicio',
            'fecha_fin'
        ]));
        $promo->save();

        // 2) Sincronizo los productos seleccionados (pivot producto_promocion)
        $promo->productos()->sync($request->input('products', []));

        // 3) Redirijo con éxito
        return redirect()
            ->route('promociones.index')
            ->with('promocion_editada', $promo->nombre);
    }


    public function destroy($id)
    {
        $promo = Promocion::findOrFail($id);
        $promo->activo = 0;
        $promo->save();

        return redirect()
            ->route('promociones.index')
            ->with('promocion_deshabilitada', $promo->nombre);
    }

    public function deshabilitadas()
    {
        // desactivamos expiradas también aquí, por si quedaron activas
        $this->deactivateExpired();
        $promociones = Promocion::where('activo', 0)->get();
        return view('Administrador.promociones.deshabilitadas', compact('promociones'));
    }

    public function habilitar($id)
    {
        $promo = Promocion::findOrFail($id);

        $existe = Promocion::whereRaw('LOWER(nombre) = ?', [strtolower($promo->nombre)])
            ->where('activo', 1)
            ->exists();
        if ($existe) {
            return redirect()
                ->route('promociones.deshabilitadas')
                ->with('error_habilitar', $promo->nombre);
        }

        $promo->activo = 1;
        $promo->save();

        return redirect()
            ->route('promociones.index')
            ->with('promocion_habilitada', $promo->nombre);
    }

    private function deactivateExpired(): void
    {
        Promocion::where('activo', 1)
            ->whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<', Carbon::today())
            ->update(['activo' => 0]);
    }
    public function productosListar(Request $request)
    {
        $q = $request->query('q', '');
        $query = Producto::where('activo', 1)
            ->when($q, fn($qr) => $qr->where('nombre', 'like', "%{$q}%"));
        $productos = $query->paginate(8)->withQueryString();
        return response()->json([
            'data' => $productos->items(),
            'links' => $productos->links()->render(),
        ]);
    }

    public function productos(Promocion $promo)
    {
        // cargamos solo id, nombre y precio
        $lista = $promo
            ->productos()
            ->select('productos.id', 'productos.nombre', 'productos.precio')
            ->get();

        return response()->json($lista);
    }
}