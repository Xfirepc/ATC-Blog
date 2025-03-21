<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Gestión de Comentarios
 *
 * APIs para gestionar los comentarios del blog
 */
class CommentController extends BaseController
{
    /**
     * Listar todos los comentarios
     *
     * Muestra un listado de todos los comentarios disponibles.
     *
     * @queryParam page int Número de página para la paginación. Example: 1
     * @queryParam post int ID del post para filtrar. Example: 1
     * @queryParam user int ID del usuario para filtrar. Example: 1
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'post']);

        if ($request->has('post')) {
            $query->where('post_id', $request->post);
        }

        if ($request->has('user')) {
            $query->where('user_id', $request->user);
        }

        $comments = $query->latest()->paginate(10);
        return $this->sendResponse($comments, 'Comentarios obtenidos con éxito');
    }

    /**
     * Crear un nuevo comentario
     *
     * Crea un nuevo comentario en un post.
     * Requiere autenticación.
     *
     * @bodyParam post_id int required ID del post. Example: 1
     * @bodyParam content string required Contenido del comentario. Example: Excelente artículo
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|min:1|max:1000'
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $request->post_id,
            'content' => $request->content,
            'is_approved' => true // Todos los comentarios se aprueban automáticamente
        ]);

        return $this->sendResponse($comment->load(['user', 'post']), 'Comentario creado con éxito');
    }

    /**
     * Mostrar un comentario específico
     *
     * Obtiene los detalles de un comentario específico por su ID.
     *
     * @urlParam id required El ID del comentario. Example: 1
     * @return JsonResponse
     */
    public function show(Comment $comment)
    {
        return $this->sendResponse($comment->load(['user', 'post']), 'Comentario obtenido con éxito');
    }

    /**
     * Actualizar un comentario
     *
     * Actualiza los datos de un comentario existente.
     * Requiere autenticación y ser el autor.
     *
     * @urlParam id required El ID del comentario. Example: 1
     * @bodyParam content string Contenido del comentario. Example: Contenido actualizado
     * @return JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        // Verificar permisos
        if ($request->user()->id !== $comment->user_id) {
            return $this->sendError('No tienes permisos para actualizar este comentario', [], 403);
        }

        $request->validate([
            'content' => 'required|string|min:1|max:1000'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        return $this->sendResponse($comment->load(['user', 'post']), 'Comentario actualizado con éxito');
    }

    /**
     * Eliminar un comentario
     *
     * Elimina un comentario específico del sistema.
     * Requiere autenticación y ser el autor.
     *
     * @urlParam id required El ID del comentario. Example: 1
     * @return JsonResponse
     */
    public function destroy(Request $request, Comment $comment)
    {
        // Verificar permisos
        if ($request->user()->id !== $comment->user_id) {
            return $this->sendError('No tienes permisos para eliminar este comentario', [], 403);
        }

        $comment->delete();
        return $this->sendResponse(null, 'Comentario eliminado con éxito');
    }
} 