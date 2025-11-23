<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreComputerRequest;
use App\Http\Requests\UpdateComputerRequest;
use Illuminate\Support\Str;

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

    public function create()
    {
        return Inertia::render('Computers/Create', [
            // Para select de categoría
            'categories' => Category::orderBy('categories_name')->get(['id', 'categories_name']),
        ]);
    }

    public function store(StoreComputerRequest $request)
    {
        $validated = $request->validated();

        Computer::create($validated);

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador creado correctamente.');
    }

    public function show(Computer $computer)
    {
        $computer->load('category');

        return Inertia::render('Computers/Show', [
            'computer' => $computer,
        ]);
    }

    public function edit(Computer $computer)
    {
        return Inertia::render('Computers/Edit', [
            'computer'   => $computer->load('category'),
            'categories' => Category::orderBy('categories_name')->get(['id', 'categories_name']),
        ]);
    }

    public function update(UpdateComputerRequest $request, Computer $computer)
    {
        $computer->update($request->validated());

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador actualizado correctamente.');
    }

    public function destroy(Computer $computer)
    {
        $computer->delete();

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador eliminado correctamente.');
    }
}
