@extends('include.master')

@section('title', 'Inventory | Filtros')

@section('page-title', 'Lista de Filtrado')

@section('content')

<div class="row clearfix">
    <!-- Componente para realizar el filtrado -->
    <create-filter 
        :date="{{ json_encode(date('Y-m-d')) }}" 
        :vendors="{{ json_encode($vendors) }}"  <!-- Aseguramos el formato JSON -->
        :categorys="{{ json_encode($categories) }}">
    </create-filter>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <!-- Botón para agregar existencias -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-stock">
                        Agregar Filtrado
                    </button>
                </h2>
            </div>

            <div class="body">
                <!-- Componente para mostrar los resultados del filtro -->
                <view-filter 
                    :vendors="{{ json_encode($vendors) }}" 
                    :categorys="{{ json_encode($categories) }}" 
                    :products="{{ json_encode($products) }}">
                </view-filter>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')

<script type="text/javascript" src="{{ url('public/js/filter.js') }}"></script>

@endpush
