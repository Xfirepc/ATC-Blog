<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Gestión de Categorías
 *
 * APIs para gestionar las categorías del blog
 */
class CategoryController extends BaseController
{
    /**
     * Listar todas las categorías
     *
     * Muestra un listado de todas las categorías disponibles.
     *
     * @queryParam page int Número de página para la paginación. Example: 1
     * @return JsonResponse
     */
    public function index()
    {
        $categories = Category::withCount('posts')->paginate(10);
        return $this->sendResponse($categories, 'Categorías obtenidas con éxito');
    }

    /**
     * Crear una nueva categoría
     *
     * Crea una nueva categoría en el sistema.
     * Requiere autenticación.
     *
     * @bodyParam name string required Nombre de la categoría. Example: Tecnología
     * @bodyParam description string Descripción de la categoría. Example: Artículos sobre tecnología
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->all());
        return $this->sendResponse($category, 'Categoría creada con éxito');
    }

    /**
     * Mostrar una categoría específica
     *
     * Obtiene los detalles de una categoría específica por su ID.
     *
     * @urlParam id required El ID de la categoría. Example: 1
     * @return JsonResponse
     */
    public function show(Category $category)
    {
        return $this->sendResponse($category->loadCount('posts'), 'Categoría obtenida con éxito');
    }

    /**
     * Actualizar una categoría
     *
     * Actualiza los datos de una categoría existente.
     * Requiere autenticación.
     *
     * @urlParam id required El ID de la categoría. Example: 1
     * @bodyParam name string Nombre de la categoría. Example: Tecnología Actualizada
     * @bodyParam description string Descripción de la categoría. Example: Nueva descripción
     * @return JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());
        return $this->sendResponse($category, 'Categoría actualizada con éxito');
    }

    /**
     * Eliminar una categoría
     *
     * Elimina una categoría específica del sistema.
     * Requiere autenticación.
     *
     * @urlParam id required El ID de la categoría. Example: 1
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->sendResponse(null, 'Categoría eliminada con éxito');
    }

    /**
     * Obtener posts de una categoría
     *
     * Obtiene todos los posts asociados a una categoría específica.
     *
     * @urlParam id required El ID de la categoría. Example: 1
     * @queryParam page int Número de página para la paginación. Example: 1
     * @return JsonResponse
     */
    public function posts(Category $category)
    {
        $posts = $category->posts()->with(['user', 'comments'])->paginate(10);
        return $this->sendResponse($posts, 'Posts de la categoría obtenidos con éxito');
    }
}
