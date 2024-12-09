@extends('include.master')

@section('title','Inventory | Filtros de Productos')

@section('page-title','Filtros de productos')

@section('content')

<div class="row clearfix">
    <!-- Componente para crear un nuevo producto, si aplica a la funcionalidad de filtro -->
    <create-product :categorys="{{ json_encode($category) }}"></create-product>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-product">
                        Producto nuevo
                    </button>
                </h2>
            </div>

            <!-- Componente para visualizar los productos filtrados -->
            <view-product :categorys="{{ json_encode($category) }}" :filters="{{ json_encode($filters) }}"></view-product>
            
            <!-- Tabla de filtros -->
            <div class="body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Existencia Actual en Uso</th>
                            <th>Desperdicio</th>
                            <th>Filtrado Supervisor</th>
                            <th>Fecha de Filtrado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filters as $filter)
                            <tr>
                                <td>{{ $filter->category->name }}</td>
                                <td>{{ $filter->product->product_name }}</td>
                                <td>{{ $filter->vendor->name }}</td>
                                <td>{{ $filter->current_quantity_in_use }}</td>
                                <td>{{ $filter->waste ?? 'N/A' }}</td>
                                <td>{{ $filter->supervisor_filtered }}</td>
                                <td>{{ $filter->filter_date ? \Carbon\Carbon::parse($filter->filter_date)->format('d-m-Y') : 'N/A' }}</td>
                                <td>
                                    <!-- Acciones de editar y eliminar -->
                                    <a href="{{ route('filter.edit', $filter->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('filter.delete', $filter->id) }}" method="POST" style="display:inline;">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="pagination-container">
                    {{ $filters->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')

@endpush
