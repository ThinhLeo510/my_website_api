<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Models\Admin;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
// use Illuminate\Database\Eloquent\Collection;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //create a new post
        $validator = Validator::make($request->all(),[
            'title'=>['required','string','max:255'],
            'content'=>['required','string'],
            'created_by'=>['required','numeric']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $post=Post::create([
            'title'=> $request->title,
            'content'=> $request->content,
            'created_by'=>$request->created_by
        ]);

        return response()->json([
            'message' => 'Created post successfully',
            'data'=>$post
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show a post
        $post=Post::find($id);
        if($post){
            return response()->json([
                'data'=> $post
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update a post
        $validator = Validator::make($request->all(),[
            'title'=>['string','max:255'],
            'content'=>['string'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $post=Post::find($id);
        if($post){
            $post->title = trim($request->title);
            $post->content = trim($request->content);
            $post->save();

            return response()->json([
                'message'=>'Updated successfully',
                'data'=>$post
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //soft delete a post
        $post=Post::find($id);
        if($post){
            $post->delete();
            return response()->json([
                'message'=>'Deleted successfully'
            ], 200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    public function restore($id){

        $post=Post::onlyTrashed()->find($id);
        if($post){
            $post->restore();
            return response()->json([
                'message'=>'Restored successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'Data not found'
            ]);
        }
    }

    public function getListPostDeleted()
    {

        $postDeleted = Post::onlyTrashed()->get();
        if ($postDeleted) {
            return response()->json([
                'data' => $postDeleted
            ], 200);
        } else {
            return response()->json([
                'data' => 'nothing to show'
            ]);
        }
    }
}
