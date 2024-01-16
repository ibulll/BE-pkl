<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get  categories
        $categories = category::when(request()->search, function($categories) {
            return $categories->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $categories->appends(['search' => request()->search ]);

        //return with Api Resource
        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
        'image'         => 'required|mimes:jpeg,jpg,png|max:2000',
        'name'          => 'required|unique:categories',
       ]);

       if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
       }

       //upload image
       $image = $request->file('image');
       $image->storeAs('public/categories', $image->hashName());

       //create category
       $category = category::create([
          'image'    => $image->hashName(),
           'name'      => $request->name,
           'slug'      => Str::slug($request->name, '-'),
       ]);
     
       if($category) {
       //return data with api resource
       return new CategoryResource(true,'Data Category Berhasil Disimpan!',$category);
       }

       //
       return new CategoryResource(false,'Data Category Gagal Disimpan!', null);
    } 
    
    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::whereid($id)->first();

        if($category){
            //return success with Api Resource
            return new CategoryResource(true,'Detail Data Category!',$category);
        }

        //returb=n failed with Api Resource
        return new CategoryResource(false,'Data Category Gagal Disimpan!', null);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, Category $category)
    {
         $validator = Validator::make($request->all(),[
            'name'=> 'required|unique:categories,name,'.$category->id,
         ]);

         if ($validator->fails()) {
              return response()->json($validator->messages(), 422);
         }

         //check image update
         if($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('/public/images/categories/'.basename($category->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            //update category with new image
            $category->update([
                'image'=> $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);

        }

        //update category without image
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);

        if($category) {
            //return success with Api Resource
            return new CategoryResource(true,'Data Categori Berhasil Diupdate!', $category);
        }

        //return failed with Api Resource
        return new CategoryResource(false,'Data Category Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //remove image
        Storage::disk('local')->delete('/public/categories/'.basename($category->image));

        if($category->delete()) {
            //return success with API Resource
            return new CategoryResource(true, 'Data Category Berhasil Dihapus', null);
        }

        //return failed with API Resource
        return new UserResource(false, 'Data Category Gagal Dihapus', null);
    }

          public function all()
        {
            //get categories
            $categories = Category::latest()->get();

            //return with API Resource
            return new CategoryResource(true, 'List Data Categories', $categories);
        }
}
    
    


    

