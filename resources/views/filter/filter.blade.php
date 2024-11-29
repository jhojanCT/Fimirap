@extends('include.master')


@section('title','Inventory | Filtrado')


@section('page-title','Lista de Control de calidad')


@section('content')






<div class="row clearfix">
    <create-filter :date="{{ json_encode(date('Y-m-d')) }}" :vendors="{{ $vendors }}" :categorys="{{ $categories }}"></create-filter>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-filter">
                        Agregar Filtrado
                    </button>
                </h2>
            </div>

            <view-filter :vendors="{{ $vendors }}" :categorys="{{ $categories }}" :products="{{ $products }}"></view-filter>
        </div>
    </div>
</div>

@endsection

@push('script')

<script type="text/javascript" src="{{ url('public/js/filter.js') }}"></script>

@endpush