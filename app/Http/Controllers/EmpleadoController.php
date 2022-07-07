<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request; // Libreria para obtener instancia de solicitud HTTP
use Illuminate\Support\Facades\Storage; // Libreria para obtener instancia de almacenamiento 

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datos['empleados'] = Empleado::paginate(5);  // Se almacenan los datos en una variable empleados
        return view('empleado.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('empleado.create');
            
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

        $campos = [
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];

        $mensaje = [
            'required'=>'El :attribute es requerido',
            'Foto.required'=>'La foto es requerida',

        ];
        $this->validate($request, $campos, $mensaje);


        //$datosEmpleado = request()->all();
        $datosEmpleado = request()->except('_token');  // Se toman los datos exceptuando el campo token

        if($request->hasFile('Foto')) {
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads', 'public'); // subir la foto en la carpeta uploads
        }

        Empleado::insert($datosEmpleado);  // inserta los datos en el modelo Empleado

        //return response()->json($datosEmpleado);
        return redirect('empleado')->with('mensaje','Empleado agregado con éxito');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $empleado=Empleado::findOrFail($id);  // Recupera información
        return view('empleado.edit', compact('empleado')); // Envia al formulario
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $campos = [
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
        ];

        $mensaje = [
            'required'=>'El :attribute es requerido',

        ];

        if($request->hasFile('Foto')) {
            $campos = ['Foto'=>'required|max:10000|mimes:jpeg,png,jpg'];
            $mensaje = ['Foto.required'=>'La foto es requerida'];
        }

        $this->validate($request, $campos, $mensaje);

        $datosEmpleado = request()->except(['_token','_method']); // No recepciona token y método 
        if($request->hasFile('Foto')) {
            $empleado=Empleado::findOrFail($id);  // Recupera información 
            Storage::delete('public/'.$empleado->Foto); // Se elimina la Foto
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads', 'public'); // subir la foto en la carpeta uploads si existe
        }
        Empleado::where('id','=',$id)->update($datosEmpleado);  // actualiza los datosEmpleado en el Modelo

        $empleado=Empleado::findOrFail($id);  // Recupera información 
        
        //return view('empleado.edit', compact('empleado')); // Envia al formulario con los datos actualizados

        return redirect('empleado')->with('mensaje','Empleado Modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empleado=Empleado::findOrFail($id);  // Recupera información 
        if(Storage::delete('public/'.$empleado->Foto)) // Se borra la foto de la carpeta uploads
        {
            Empleado::destroy($id);
        }
        
        
        return redirect('empleado')->with('mensaje','Empleado Borrado');
    }
}
