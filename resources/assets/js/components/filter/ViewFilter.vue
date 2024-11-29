<template>
  <div class="wrap">
    <div class="body">
      <!-- Filtros de Categoría, Producto y Proveedor -->
      <div class="row mb-3">
        <div class="col-md-4">
          <select class="form-control select2" data-live-search="true" @change="getProducts" v-model="category">
            <option value="">Todas las categorías</option>
            <option v-for="cat in categorys" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>

        <div class="col-md-4">
          <select class="form-control select2" data-live-search="true" @change="updateData" v-model="product">
            <option value="">Todos los productos</option>
            <option v-for="pd in products" :key="pd.id" :value="pd.id">{{ pd.product_name }}</option>
          </select>
        </div>

        <div class="col-md-4">
          <select class="form-control select2" data-live-search="true" @change="updateData" v-model="vendor">
            <option value="">Todos los proveedores</option>
            <option v-for="vd in vendors" :key="vd.id" :value="vd.id">{{ vd.name }}</option>
          </select>
        </div>
      </div>

      <!-- Indicador de carga -->
      <div v-if="isLoading" class="text-center">
        <h4>Cargando datos...</h4>
        <div class="spinner-border text-primary" role="status"></div>
      </div>

      <!-- Tabla de Resultados Filtrados -->
      <div v-else class="table-responsive">
        <table class="table table-condensed table-hover table-bordered">
          <thead>
            <tr>
              <th>Categoría</th>
              <th>Producto</th>
              <th>Proveedor</th>
              <th>Comprobante</th>
              <th>Existencia inicial</th>
              <th>Existencia actual</th>
              <th>Precio de compra</th>
              <th>Precio de venta</th>
              <th>Entrada por</th>
              <th>Fecha de entrada</th>
              <th>Agregar</th>
              <th>Editar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(stock, index) in stocks.data" :key="index">
              <td>{{ stock.category.name }}</td>
              <td>{{ stock.product.product_name }}</td>
              <td>{{ stock.vendor.name }}</td>
              <td>{{ stock.chalan_no }}</td>
              <td>{{ stock.stock_quantity }}</td>
              <td>{{ stock.current_quantity }}</td>
              <td>{{ stock.buying_price }}</td>
              <td>{{ stock.selling_price }}</td>
              <td>{{ stock.user.name }}</td>
              <td>{{ stock.created_at | moment('LL') }}</td>
              <td>
                <button @click="editQty(stock.id)" class="btn bg-blue btn-circle waves-effect waves-circle">
                  <i class="material-icons">add</i>
                </button>
              </td>
              <td>
                <button @click="editStock(stock.id)" class="btn bg-blue btn-circle waves-effect waves-circle">
                  <i class="material-icons">edit</i>
                </button>
              </td>
              <td>
                <button @click="deleteStock(stock.id)" class="btn bg-pink btn-circle waves-effect waves-circle">
                  <i class="material-icons">delete</i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <pagination v-if="stocks.meta" :pageData="stocks" @page-clicked="getData"></pagination>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../vue-asset";
import mixin from "../../mixin.js";
import MomentMixin from "../../moment_mixin.js";
import editStock from "./editStock.vue";
import UpdateQuantity from "./UpdateQuantity.vue";
import Pagination from "../pagination/pagination.vue";

export default {
  props: ["categorys", "vendors"],
  mixins: [mixin, MomentMixin],
  components: {
    "edit-stock": editStock,
    "update-qty": UpdateQuantity,
    pagination: Pagination,
  },
  data() {
    return {
      stocks: { data: [], meta: {} },
      category: "",
      product: "",
      vendor: "",
      products: [],
      isLoading: false,
    };
  },
  created() {
    this.getData();
    EventBus.$on("stock-updated", this.getData);
  },
  methods: {
    getData(page = 1) {
      this.isLoading = true;
      axios
        .get(`${base_url}stock-list`, {
          params: { page, product: this.product, category: this.category, vendor: this.vendor },
        })
        .then(({ data }) => {
          this.stocks = data;
          this.isLoading = false;
        })
        .catch((err) => {
          console.error(err);
          this.isLoading = false;
        });
    },
    getProducts() {
      if (!this.category) {
        this.products = [];
        this.product = "";
        this.getData();
        return;
      }
      axios
        .get(`${base_url}category/product/${this.category}`)
        .then(({ data }) => {
          this.products = data;
        })
        .catch(console.error);
      this.getData();
    },
    updateData() {
      this.getData();
    },
    editStock(id) {
      EventBus.$emit("edit-stock", id);
    },
    editQty(id) {
      EventBus.$emit("edit-qty", id);
    },
    deleteStock(id) {
      Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`${base_url}stock/delete/${id}`)
            .then(({ data }) => {
              this.getData();
              this.$toast.success(data.message || "Stock eliminado correctamente.");
            })
            .catch(console.error);
        }
      });
    },
  },
};
</script>
