<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->input('search');
        $categoryId  = $request->input('category_id');

        $query = Computer::with('category');

        // Filtro de búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('computer_brand', 'ILIKE', "%{$search}%")
                    ->orWhere('computer_model', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $computers = $query->orderBy('id_computer', 'asc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Computers/Index', [
            'computers'   => $computers,
            'filters'     => [
                'search'      => $search,
                'category_id' => $categoryId
            ],
            'categories' => Category::orderBy('categories_name')
                ->get(['id', 'categories_name']),
        ]);
    }

    /**
     * Formulario de creación de computador.
     */
    public function create()
    {
        return Inertia::render('Computers/Create', [
            // Para select de categoría
            'categories' => Category::orderBy('categories_name')->get(['id', 'categories_name']),
        ]);
    }

    /**
     * Guardar un nuevo computador.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'computer_brand'      => ['required', 'string', 'min:2', 'max:100'],
            'computer_model'      => ['required', 'string', 'min:2', 'max:100'],
            'computer_price'      => ['required', 'numeric', 'min:0'],
            'computer_ram_size'   => ['required', 'integer', 'min:1'],
            'computer_is_laptop'  => ['required', 'boolean'],
            'category_id'         => ['required', 'exists:categories,id'],
        ]);

        Computer::create($validated);

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador creado correctamente.');
    }

    /**
     * Detalle de computador con su categoría.
     */
    public function show(Computer $computer)
    {
        $computer->load('category');

        return Inertia::render('Computers/Show', [
            'computer' => $computer,
        ]);
    }

    /**
     * Formulario de edición de computador.
     */
    public function edit(Computer $computer)
    {
        return Inertia::render('Computers/Edit', [
            'computer'   => $computer->load('category'),
            'categories' => Category::orderBy('categories_name')->get(['id', 'categories_name']),
        ]);
    }

    /**
     * Actualizar computador.
     */
    public function update(Request $request, Computer $computer)
    {
        $validated = $request->validate([
            'computer_brand'      => ['required', 'string', 'min:2', 'max:100'],
            'computer_model'      => ['required', 'string', 'min:2', 'max:100'],
            'computer_price'      => ['required', 'numeric', 'min:0'],
            'computer_ram_size'   => ['required', 'integer', 'min:1'],
            'computer_is_laptop'  => ['required', 'boolean'],
            'category_id'         => ['required', 'exists:categories,id'],
        ]);

        $computer->update($validated);

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador actualizado correctamente.');
    }

    /**
     * Eliminar computador.
     */
    public function destroy(Computer $computer)
    {
        $computer->delete();

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador eliminado correctamente.');
    }
}
