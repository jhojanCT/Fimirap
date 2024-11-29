@extends('include.master')

@section('title', 'Inventory | Filtrado')

@section('page-title', 'Lista de Control de Calidad')

@section('content')

<div class="container-fluid">
    <!-- Sección de creación de filtrados -->
    <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Gestión de Filtros y Atributos
                </h2>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-filter">
                    Agregar Filtrado
                </button>
            </div>
            <div class="body">
                <!-- Aquí se incluye el componente de creación de filtros -->
                <create-filter 
                    :date="{{ json_encode(date('Y-m-d')) }}" 
                    :vendors="{{ $vendors }}" 
                    :categorys="{{ $categories }}">
                </create-filter>
            </div>
        </div>
    </div>
</div>

</div>

@endsection

@push('script')
<script type="text/javascript" src="{{ url('public/js/filter.js') }}"></script>
@endpush
