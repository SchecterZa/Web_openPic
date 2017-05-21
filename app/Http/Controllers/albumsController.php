<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;
use File;

class albumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
      $id = Auth::user();
      $id = $id->id;
      $album = DB::table('products_photos')->select('album_name')->distinct('album_name')->where('uid',$id)->get();
      $album = json_decode($album,true);
      $path_photo = array();
      for($i = 0;$i<count($album);$i++){
       $path = DB::table('products_photos')->select('photo_path')->where('album_name',$album[$i]['album_name'])->get();
       $path = json_decode($path,true);
         $path_photo[0][$i] = $album[$i]['album_name'];
        for($k = 0;$k<count($path);$k++)
          $path_photo[$album[$i]['album_name']][$k] = "/".$path[$k]['photo_path'];

      }
        return view('album',compact('path_photo'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(){

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($album_name)
    {
      $id = Auth::user();
      $id = $id->id;
      $database = DB::table('products_photos')->select('photo_path','grid')->where('uid',$id)->where('album_name',$album_name)->get();
      $database = json_decode($database,true);
      $photos = array();

      for($i = 0;$i<count($database);$i++){
        $photos['photo_path'][$i] = '/'.$database[$i]['photo_path'];
        $photos['grid'][$i] = $database[$i]['grid'];
      }

        return view('albumManagement',compact('photos'));
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(){
    $getpath = $_POST['photos_path'];
    $getgrid = $_POST['grid'];
      $i = 0;
      foreach ($getpath as $path) {
        $pathDe = substr($path,1,strlen($path));
        $pathforG = substr($pathDe,0,strlen($pathDe)-1);
       DB::table('products_photos')->where('photo_path',$pathforG)->update(['grid'=>$getgrid[$i]]);
        if(File::exists($pathDe)){
        DB::table('products_photos')->where('photo_path',$pathDe)->delete();
          File::delete($pathDe);
        }
        $i++;
      }


    return "เรียบโร้ยยย";



    }

}
