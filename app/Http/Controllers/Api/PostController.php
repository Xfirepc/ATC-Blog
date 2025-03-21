<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @group Gestión de Posts
 *
 * APIs para gestionar los posts del blog
 */
class PostController extends BaseController
{
    /**
     * Listar todos los posts
     *
     * Muestra un listado de todos los posts disponibles.
     *
     * @queryParam page int Número de página para la paginación. Example: 1
     * @queryParam category int ID de la categoría para filtrar. Example: 1
     * @queryParam user int ID del usuario para filtrar. Example: 1
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category', 'comments']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('user')) {
            $query->where('user_id', $request->user);
        }

        $posts = $query->latest()->paginate(10);
        return $this->sendResponse($posts, 'Posts obtenidos con éxito');
    }

    /**
     * Crear un nuevo post
     *
     * Crea un nuevo post en el sistema.
     * Requiere autenticación.
     *
     * @bodyParam title string required Título del post. Example: Mi primer post
     * @bodyParam content string required Contenido del post. Example: Este es el contenido...
     * @bodyParam category_id int required ID de la categoría. Example: 1
     * @bodyParam image string URL de la imagen. Example: https://ejemplo.com/imagen.jpg
     * @bodyParam is_published boolean Estado de publicación. Example: true
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|url',
            'is_published' => 'boolean'
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $request->image,
            'is_published' => $request->is_published ?? false
        ]);

        return $this->sendResponse($post->load(['user', 'category']), 'Post creado con éxito');
    }

    /**
     * Mostrar un post específico
     *
     * Obtiene los detalles de un post específico por su ID.
     *
     * @urlParam id required El ID del post. Example: 1
     * @return JsonResponse
     */
    public function show(Post $post)
    {
        return $this->sendResponse($post->load(['user', 'category', 'comments.user']), 'Post obtenido con éxito');
    }

    /**
     * Actualizar un post
     *
     * Actualiza los datos de un post existente.
     * Requiere autenticación y ser el autor.
     *
     * @urlParam id required El ID del post. Example: 1
     * @bodyParam title string Título del post. Example: Título actualizado
     * @bodyParam content string Contenido del post. Example: Contenido actualizado
     * @bodyParam category_id int ID de la categoría. Example: 2
     * @bodyParam image string URL de la imagen. Example: https://ejemplo.com/nueva-imagen.jpg
     * @bodyParam is_published boolean Estado de publicación. Example: true
     * @return JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        // Verificar permisos
        if ($request->user()->id !== $post->user_id) {
            return $this->sendError('No tienes permisos para actualizar este post', [], 403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('posts')->ignore($post->id)],
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|url',
            'is_published' => 'boolean'
        ]);

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $request->image,
            'is_published' => $request->is_published ?? $post->is_published
        ]);

        return $this->sendResponse($post->load(['user', 'category']), 'Post actualizado con éxito');
    }

    /**
     * Eliminar un post
     *
     * Elimina un post específico del sistema.
     * Requiere autenticación y ser el autor.
     *
     * @urlParam id required El ID del post. Example: 1
     * @return JsonResponse
     */
    public function destroy(Request $request, Post $post)
    {
        // Verificar permisos
        if ($request->user()->id !== $post->user_id) {
            return $this->sendError('No tienes permisos para eliminar este post', [], 403);
        }

        $post->delete();
        return $this->sendResponse(null, 'Post eliminado con éxito');
    }

    /**
     * Obtener comentarios de un post
     *
     * Obtiene todos los comentarios asociados a un post específico.
     *
     * @urlParam id required El ID del post. Example: 1
     * @queryParam page int Número de página para la paginación. Example: 1
     * @return JsonResponse
     */
    public function comments(Post $post)
    {
        $comments = $post->comments()->with('user')->paginate(10);
        return $this->sendResponse($comments, 'Comentarios del post obtenidos con éxito');
    }
} 