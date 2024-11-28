<div class="row clearfix">
    <!-- Componente para realizar el filtrado -->
    <create-filter 
        :date="{{ json_encode(date('Y-m-d')) }}" 
        :vendors="{{ $vendors }}"  <!-- Corregido de $vendor a $vendors -->
        :categorys="{{ $categories }}">
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

            <!-- Componente para mostrar el filtro -->
            <view-filter 
                :vendors="{{ $vendors }}"  <!-- Corregido de $vendor a $vendors -->
                :categorys="{{ $categories }}" 
                :products="{{ $products }}">
            </view-filter>
        </div>
    </div>
</div>
