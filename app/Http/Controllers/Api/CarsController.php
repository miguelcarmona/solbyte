<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;


class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Car::with('category');

        if ($request->has('sort')) {
            $query->orderBy($request->input('sort'), $request->input('order', 'asc'));
        }

        if ($request->has('filter')) {
            $query->where($request->input('filter.field'), 'like', '%' . $request->input('filter.value') . '%');
        }

        return response()->json($query->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => ['required', 'exists:categories,id'],
            'marca' => ['required', 'string', 'max:50'],
            'modelo' => ['required', 'string', 'max:50'],
            'matricula' => ['required', 'string', 'max:10'],
            'color' => ['required', 'string', 'max:25'],
            'año_fabricacion' => ['nullable', 'integer', 'between:1800,' . date('Y')],
            'otros_datos' => ['nullable', 'string'],
            'imagen_principal' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('imagen_principal')) {
            $validated['imagen_principal'] = $request->file('imagen_principal')->store('car_images', 'public');
        }

        $car = Car::create($validated);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car->load('category', 'images');
        return response()->json($car);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'categoria_id' => ['required', 'exists:categories,id'],
            'marca' => ['required', 'string', 'max:50'],
            'modelo' => ['required', 'string', 'max:50'],
            'matricula' => ['required', 'string', 'max:10'],
            'color' => ['required', 'string', 'max:25'],
            'año_fabricacion' => ['nullable', 'integer', 'between:1800,' . date('Y')],
            'otros_datos' => ['nullable', 'string'],
            'imagen_principal' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('imagen_principal')) {
            // Eliminar la imagen antigua si existe
            if ($car->imagen_principal) {
                Storage::disk('public')->delete($car->imagen_principal);
            }
            $validated['imagen_principal'] = $request->file('imagen_principal')->store('car_images', 'public');
        }

        $car->update($validated);

        return response()->json($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        // Eliminar la imagen principal si existe
        if ($car->imagen_principal) {
            Storage::disk('public')->delete($car->imagen_principal);
        }

        $car->delete();

        return response()->json(null, 204);
    }

    /**
     * Exporta los datos a CSV
     */
    public function exportCsv()
    {
        $cars = Car::with('category')->get();

        $response = new StreamedResponse(function() use ($cars) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, ['ID', 'Categoría', 'Marca', 'Modelo', 'Matrícula', 'Color', 'Año de Fabricación', 'Otros Datos']);

            foreach ($cars as $car) {
                fputcsv($handle, [
                    $car->id,
                    $car->category->nombre,
                    $car->marca,
                    $car->modelo,
                    $car->matricula,
                    $car->color,
                    $car->año_fabricacion,
                    $car->otros_datos,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="cars.csv"');

        return $response;
    }
}
