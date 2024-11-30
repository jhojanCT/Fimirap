@extends('include.master')

@section('title','Inventory | filtros')

@section('page-title','Lista de Filtrado')

@section('content')
    <div class="row clearfix">
        <create-filter 
            :date="{{ json_encode(date('Y-m-d')) }}" 
            :vendors="{{ $vendors }}" 
            :categorys="{{ $categories }}"
            @edit-filter="editFilter"
            @delete-filter="deleteFilter"
        ></create-filter>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-stock">
                            Agregar Filtrado
                        </button>
                    </h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Existencia inicial</th>
                                <th>Existencia actual</th>
                                <th>Entrada por</th>
                                <th>Fecha de entrada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->category->name }}</td>
                                    <td>{{ $stock->product->product_name }}</td>
                                    <td>{{ $stock->vendor->name }}</td>
                                    <td>{{ $stock->stock_quantity }}</td>
                                    <td>{{ $stock->current_quantity }}</td>
                                    <td>{{ $stock->user->name }}</td>
                                    <td>{{ $stock->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <!-- Botón de editar -->
                                        <button @click="editStock({{ $stock->id }})" class="btn btn-warning btn-sm">Editar</button>
                                        <!-- Botón de eliminar -->
                                        <button @click="deleteStock({{ $stock->id }})" class="btn btn-danger btn-sm">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación (si es necesario) -->
                {{ $stocks->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para agregar nuevo stock (Filtro) -->
    <div class="modal fade" id="create-stock" tabindex="-1" role="dialog" aria-labelledby="create-stock-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="create-stock-label">Agregar Filtro</h4>
                </div>
                <div class="modal-body">
                    <create-filter 
                        :date="{{ json_encode(date('Y-m-d')) }}" 
                        :vendors="{{ $vendors }}" 
                        :categorys="{{ $categories }}"
                        @created="reloadStockList"
                    ></create-filter>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="createStock">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Definir los métodos para editar, eliminar y agregar filtro
        new Vue({
            el: '#app', // Asegúrate de que el ID de tu div principal sea 'app' o modifica este selector
            methods: {
                editStock(id) {
                    // Abrir modal con los detalles del stock para editar
                    axios.get('/stocks/' + id + '/edit')
                        .then(response => {
                            const stock = response.data;
                            // Puedes cargar los datos en el modal de edición
                            this.$emit('edit-filter', stock); // Usar el Event Bus si lo prefieres
                            $('#edit-stock-modal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error al obtener los datos para editar:', error);
                        });
                },

                deleteStock(id) {
                    if (confirm('¿Estás seguro de eliminar este filtro?')) {
                        axios.delete('/stocks/' + id)
                            .then(response => {
                                alert('Filtro eliminado correctamente');
                                this.reloadStockList();
                            })
                            .catch(error => {
                                console.error('Error al eliminar el filtro:', error);
                            });
                    }
                },

                reloadStockList() {
                    // Recargar la lista de stocks
                    axios.get('/stocks')
                        .then(response => {
                            this.stocks = response.data;
                        })
                        .catch(error => {
                            console.error('Error al recargar la lista:', error);
                        });
                }
            }
        });
    </script>
@endsection
