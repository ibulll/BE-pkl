<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user', 'category')->withCount('views')->when(request()->search, function ($posts) {
            $posts = $posts->where('title', 'like', '%' . request()->search . '%');
        })->where('user_id', auth()->user()->id)->latest()->paginate(5);

        //append query string to pagination links
        $posts->appends(['search' => request()->search]);

        //return with Api Resource
        return new PostResource(true, 'List Data Posts', $posts);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5000',
            'title' => 'required|unique:posts',
            'category_id' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'user_id' => auth()->guard('api')->user()->id,
            'content' => $request->content
        ]);

        //push notifications firebase
        fcm()
            ->toTopic('push-notifications')
            ->priority('normal')
            ->timeToLive(0)
            ->notification([
                'title' => 'Berita Baru !',
                'body' => 'Disini akan menampilkan judul berita baru',
                'click_action' => 'OPEN_ACTIVITY'
            ])
            ->send();


        if ($post) {
            //return success with Api Resource
            return new PostResource(true, 'Data Post Berhasil Disimpan!', $post);
        }

        //return failed with Api Resource
        return new PostResource(false, 'Data Post Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('category')->whereId($id)->first();

        if ($post) {
            //return success with API Resource
            return new PostResource(true, 'Detail Data Post!', $post);
        }

        //return failed with API Resource
        return new PostResource(false, 'Detail Data Post Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts,title,' . $post->id,
            'category_id' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/posts/' . basename($post->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public\posts', $image->hashName());

            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'user_id' => auth()->guard('api')->user()->id,
                'content' => $request->content
            ]);
        }

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'user_id' => auth()->guard('api')->user()->id,
            'content' => $request->content
        ]);

        if ($post) {
            //return success with API Resource
            return new PostResource(true, 'Data Post Berhasil Diupdate!', $post);
        }

        //return failed with API Resource
        return new PostResource(false, 'Data Post Gagal Diupdate!', null);
    }
    /**
     * Remove the specified resource from storage
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //remove image
        Storage::disk('local')->delete('public/posts/' . basename($post->image));

        if ($post->delete()) {
            //return success with API Resource
            return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
        }

        //return failed with API Resource
        return new PostResource(false, 'Data Post Gagal Dihapus!',  null);
    }
}


