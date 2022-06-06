<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alumnos = Alumno::paginate(5);
        return view('alumnos.index', compact('alumnos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('alumnos.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required', 'apellido' => 'required', 'edad' => 'required', 'genero' => 'required', 'imagen' => 'required|image|mimes:jpeg,png,svg|max:1024'
        ]);

         //Guardar imagen en AWS S3
        try{
            //Registrar alumno
            $folder = "imagenes";

            $alumno = new Alumno;
            $alumno->nombre = $request->nombre;
            $alumno->apellido = $request->apellido;
            $alumno->edad = $request->edad;
            $alumno->genero = $request->genero;
            $image_path = Storage::disk('s3')->put($folder, $request->imagen, 'public');

            $alumno->imagen = $image_path;
            $alumno->save();

            return redirect()->route('alumnos.index')
            ->with('success','alumno registrado correctamente!');

        }catch(\Exception $e){

            return redirect()->route('alumnos.index')
            ->with('error','No se pudo registrar el alumno. Error: '.$e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idestudiante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Alumno $alumno)
    {
        return view('alumnos.editar', compact('alumno'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nombre' => 'required', 'apellido' => 'required', 'edad' => 'required', 'genero' => 'required'
        ]);

        try{

            //Registrar alumno
            $folder = "imagenes";

            $alum=Alumno::findOrFail($idestudiante);
            Storage::disk('s3')->delete($alum->imagen);
            $alum->nombre = $request->nombre;
            $alum->apellido = $request->apellido;
            $alum->edad = $request->edad;
            $alum->genero = $request->genero;

            $image_path = Storage::disk('s3')->put($folder, $request->imagen, 'public');

            $alum->imagen = $image_path;

            $alum->update();

            return redirect()->route('alumnos.index')
            ->with('success','alumno registrado correctamente!');

        }catch(\Exception $e){

            return redirect()->route('alumnos.index')
            ->with('error','No se pudo registrar el alumno. Error: '.$e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        Storage::disk('s3')->delete($alumno->imagen);
        return redirect()->route('alumnos.index');
    }
}
