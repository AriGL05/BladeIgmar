<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ViewController extends Controller
{
    public function index(){
        $personas = Persona::all();
        return view('persons.index',["personas"=>$personas]);
    }
    public function show(){
        return view('persons.show');
    }
    public function store(Request $request){
        $request->validate([
            "nombre" => "required|min:3|max:20",
            "ap_paterno" => "required|min:3|max:20",
            "ap_materno" => "required|min:3|max:20",
            "sexo" => "max:20"
        ]);
        $persona= new Persona();
        $persona->name=$request->nombre;
        $persona->ap_paterno=$request->ap_paterno;
        $persona->ap_materno=$request->ap_materno;
        $persona->sexo = $request->sexo;
        $persona->save();
        return redirect()->route('persons.index');
    }
    public function edit(int $id){
        $persona=Persona::find($id);
        return view('persons.editform',["persona"=>$persona]);
    }
    public function update(Request $request,$id){
        $request->validate([
            "nombre" => "required|min:3|max:20",
            "ap_paterno" => "required|min:3|max:20",
            "ap_materno" => "required|min:3|max:20",
            "sexo" => "max:20"
        ]);
        Log::stack(['single','slack'])->info($id);
        $id = (int)$id;
        Log::stack(['single','slack'])->info($id);
        $persona=Persona::find($id);
        if($persona){
            $persona->name=$request->nombre;
            $persona->ap_paterno=$request->ap_paterno;
            $persona->ap_materno=$request->ap_materno;
            $persona->sexo = $request->sexo;
            $persona->save();
            return redirect()->route('persons.index');
        }
        return response()->json(["msg"=>"Persona no encontrado"],404);
    }
    public function delete(int $id){
        $persona=Persona::find($id);
        if($persona){
            $persona->delete();
            return redirect()->route('persons.index');
        }
        return redirect()->route('persons.index');
    }

}
