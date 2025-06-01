<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\ActividadEconomica;
use App\Models\TipoContribuyente;
use App\Models\TipoDocumento;
use App\Models\Municipio;
use App\Models\Pais; 

class ClienteController extends Controller
{
    

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'tipo_cliente' => 'required|numeric',
            'id_tipo_documento' => 'required_if:tipo_cliente,1',
            'direccion' => 'required|string',
            'correo' => 'nullable|email'
        ]);

        $cliente = Cliente::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente.'
            ]);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'tipo_cliente' => 'required|numeric',
            'dui_nit' => 'required_if:tipo_cliente,2,3,4',
            'nrc' => 'nullable|string',
            'nombre_comercial' => 'nullable|string',
            'direccion' => 'required|string',
            'correo' => 'nullable|email'
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
    
   

    public function selectTipo(Request $request)
    {
    $tipo = $request->input('tipo_cliente');
    
    switch ($tipo) {
        case 1:
            return redirect()->route('clientes.create.consumidor-final');
        case 2:
            return redirect()->route('clientes.create.empresa');
        case 3:
            return redirect()->route('clientes.create.extranjero');
        case 4:
            return redirect()->route('clientes.create.proveedor');
        default:
            return redirect()->back()->with('error', 'Tipo de cliente no válido');
        }
    }

    public function createConsumidorFinal()
    {
        $tiposDocumento = TipoDocumento::where('estado', '1')->get();
        return view('clientes.tipos.consumidor-final', compact('tiposDocumento'));
    }


    public function createEmpresa()
    {
        $departamentos = Departamento::where('estado', '1')->get();
        $actividadesEconomicas = ActividadEconomica::where('estado', '1')->get();
        $tiposContribuyente = TipoContribuyente::where('estado', '1')->get();
        $tiposDocumento = TipoDocumento::where('estado', '1')->get();

        return view('clientes.tipos.empresa', compact(
            'departamentos',
            'actividadesEconomicas',
            'tiposContribuyente',
            'tiposDocumento'
        ));
    }


    public function createExtranjero()
    {
        $tiposDocumentoExtranjero = TipoDocumento::where('estado', '1')
            ->whereIn('id_tipo_documento', [4, 5]) // IDs para pasaporte y carnet residencia
            ->get();
        
        $paises = Pais::where('estado', '1')->orderBy('pais')->get();
    
        return view('clientes.tipos.extranjero', compact('tiposDocumentoExtranjero', 'paises'));
    }


    public function createProveedor()
    {
        $departamentos = Departamento::where('estado', '1')->get();
        $tiposDocumento = TipoDocumento::where('estado', '1')->get();

        return view('clientes.tipos.proveedor', compact(
            'departamentos',
            'tiposDocumento'
        ));
    }


    public function getFormularioPorTipo($tipo)
{
    switch ($tipo) {
        case 1: // Consumidor final
            $tiposDocumento = TipoDocumento::where('estado', '1')->get();
            return view('clientes.tipos.consumidor-final', compact('tiposDocumento'))->render();

        case 2: // Empresa
            $departamentos = Departamento::where('estado', '1')->get();
            $actividadesEconomicas = ActividadEconomica::where('estado', '1')->get();
            $tiposContribuyente = TipoContribuyente::where('estado', '1')->get();
            $tiposDocumento = TipoDocumento::where('estado', '1')->get();
            
            return view('clientes.tipos.empresa', compact(
                'departamentos',
                'actividadesEconomicas',
                'tiposContribuyente',
                'tiposDocumento'
            ))->render();

        case 3: // Cliente extranjero
            $paises = Pais::where('estado', '1')->get();
            $tiposDocumento = TipoDocumento::where('estado', '1')->get();
            
            return view('clientes.tipos.extranjero', compact(
                'paises',
                'tiposDocumento'
            ))->render();

        case 4: // Proveedor
            $departamentos = Departamento::where('estado', '1')->get();
            $tiposDocumento = TipoDocumento::where('estado', '1')->get();
            
            return view('clientes.tipos.proveedor', compact(
                'departamentos',
                'tiposDocumento'
            ))->render();
    }
}



// métodos de tipos de clientes para usar paginación
public function consumidorFinal(Request $request)
{
    $perPage = $request->input('perPage', 5);
    $clientes = Cliente::where('tipo_cliente', 1)->paginate($perPage);
    return view('clientes.tipos.consumidor-final', compact('clientes'));
}

public function empresa(Request $request)
{
    $perPage = $request->input('perPage', 5);
    $clientes = Cliente::where('tipo_cliente', 2)->paginate($perPage);
    return view('clientes.tipos.empresa', compact('clientes'));
}

public function extranjero(Request $request)
{
    $perPage = $request->input('perPage', 5);
    $clientes = Cliente::where('tipo_cliente', 3)->paginate($perPage);
    return view('clientes.tipos.extranjero', compact('clientes'));
}

public function proveedor(Request $request)
{
    $perPage = $request->input('perPage', 5);
    $clientes = Cliente::where('tipo_cliente', 4)->paginate($perPage);
    return view('clientes.tipos.proveedor', compact('clientes'));
}


public function index(Request $request)
{
    $perPage = $request->input('perPage', 5);
    
    $query = Cliente::query()->orderBy('created_at', 'desc');
    
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('nombre_comercial', 'like', "%{$search}%")
              ->orWhere('nombre', 'like', "%{$search}%");
    }
    
    $clientes = $query->paginate($perPage);
    
    return view('clientes.index', compact('clientes'));
}


}