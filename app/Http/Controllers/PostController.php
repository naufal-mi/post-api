<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Contoh validasi untuk gambar
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Simpan nama dan ekstensi gambar
        $nama_gambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = $gambar->getClientOriginalName(); // Nama gambar beserta ekstensinya
        }

        // Simpan post
        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->gambar = $nama_gambar; // Simpan nama gambar beserta ekstensinya ke dalam kolom gambar
        $post->save();

        return response()->json($post, 201);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json($post);
    }

    public function destroy($id)
    {
        // Cari post berdasarkan ID
        $post = Post::find($id);

        // Periksa apakah post ditemukan
        if (!$post) {
            return response()->json(['message' => 'Post tidak ditemukan'], 404);
        }

        // Hapus post dari database
        $post->delete();

        // Beri respons bahwa post berhasil dihapus
        return response()->json(['message' => 'Post berhasil dihapus']);
    }
}
