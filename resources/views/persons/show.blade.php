@extends('templates.app')
@section('title')
    Formulario
@endSection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-index">
                    Formulario de Personas
                </div>
                <div class="card-body">
                    <form action="/persona" method="POST">
                        @csrf
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"value="{{ old('nombre') }}">
                        </div>
                        <div class="mb-3">
                            <label for="ap_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" name="ap_paterno" id="ap_paterno"value="{{ old('ap_paterno') }}">
                        </div>
                        <div class="mb-3">
                            <label for="ap_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" name="ap_materno" id="ap_materno"value="{{ old('ap_materno') }}">
                        </div>
                        <div class="mb-3">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select class="form-select" aria-label="sexo" name="sexo">
                                <option value="Masculino"selected>Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endSection