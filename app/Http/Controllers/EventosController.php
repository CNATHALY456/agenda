<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener los eventos del usuario autenticado
        $eventos = Evento::where('user_id', Auth::id())->get();
        return view('eventos.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Mostrar el formulario de creación de eventos
        return view('eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del evento
        $request->validate([
            'title' => 'required|max:255',
            'descripcion' => 'required',
            'color' => 'required|max:20',
            'textColor' => 'required|max:20',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        // Crear el evento y asociarlo al usuario autenticado
        $datosEvento = $request->all();
        $datosEvento['user_id'] = Auth::id();
        Evento::create($datosEvento);

        return redirect()->route('eventos.index')->with('success', 'Evento creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Obtener los eventos del usuario autenticado y devolverlos como JSON
        $eventos = Evento::where('user_id', Auth::id())->get();
        return response()->json($eventos);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Mostrar el formulario de edición de eventos
        $evento = Evento::findOrFail($id);

        // Verificar que el evento pertenece al usuario autenticado
        if ($evento->user_id != Auth::id()) {
            return redirect()->route('eventos.index')->with('error', 'No tienes permiso para editar este evento.');
        }

        return view('eventos.edit', compact('evento'));
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
        // Validar los datos del evento
        $request->validate([
            'title' => 'required|max:255',
            'descripcion' => 'required',
            'color' => 'required|max:20',
            'textColor' => 'required|max:20',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        // Verificar que el evento pertenece al usuario autenticado
        $evento = Evento::findOrFail($id);
        if ($evento->user_id != Auth::id()) {
            return redirect()->route('eventos.index')->with('error', 'No tienes permiso para actualizar este evento.');
        }

        // Actualizar el evento
        $datosEvento = $request->except(['_token', '_method']);
        $evento->update($datosEvento);

        return redirect()->route('eventos.index')->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Verificar que el evento pertenece al usuario autenticado
        $evento = Evento::findOrFail($id);
        if ($evento->user_id != Auth::id()) {
            return redirect()->route('eventos.index')->with('error', 'No tienes permiso para eliminar este evento.');
        }

        // Eliminar el evento
        $evento->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado correctamente.');
    }
}
