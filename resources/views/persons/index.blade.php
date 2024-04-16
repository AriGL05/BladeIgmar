@extends('templates.app')
@section('title')
    Tabla de Personas
@endSection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Ap_Paterno</th>
                            <th scope="col">Ap_Materno</th>
                            <th scope="col">Sexo</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personas as $persona)
                            <tr>
                                <td>{{$persona->id}}</td>
                                <td>{{$persona->name}}</td>
                                <td>{{$persona->ap_paterno}}</td>
                                <td>{{$persona->ap_materno}}</td>
                                <td>{{$persona->sexo}}</td>
                                <td>
                                    <a href="/edit/{{$persona->id}}" class="btn btn-primary">Editar</a>
                                    <form action="/delete/{{$persona->id}}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endSection