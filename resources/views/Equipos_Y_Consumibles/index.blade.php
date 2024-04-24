@endphp

@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
    
<div
    class="table-responsive"
>
    <table
        class="table table-primary"
    >
        <thead>
            <tr>
                <th scope="col">Equipo</th>
                <th scope="col">Categoria</th>
                <th scope="col">Modelo</th>
                <th scope="col">Marca</th>
                <th scope="col">Condición</th>
                <th scope="col">Extras</th>
            </tr>
        </thead>
        <tbody>
            <tr class="">
                <td scope="row">R1C1</td>
                <td>R1C2</td>
                <td>R1C3</td>
                <td>R1C2</td>
                <td>R1C3</td>
                <td>R1C2</td>
            </tr>
            <tr class="">
                <td scope="row">Item</td>
                <td>Item</td>
                <td>Item</td>
                <td>Item</td>
                <td>Item</td>
                <td>Item</td>
            </tr>
        </tbody>
    </table>
</div>





@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop
