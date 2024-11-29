<template>
  <div>
    <!-- Filtros -->
    <div class="row mb-3">
      <div class="col-md-4">
        <select class="form-control" v-model="selectedCategory" @change="filterData">
          <option value="">Todas las categorías</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>
      <div class="col-md-4">
        <select class="form-control" v-model="selectedProduct" @change="filterData">
          <option value="">Todos los productos</option>
          <option v-for="product in products" :key="product.id" :value="product.id">
            {{ product.product_name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>Categoría</th>
            <th>Producto</th>
            <th>Proveedor</th>
            <th>Stock Inicial</th>
            <th>Stock Actual</th>
            <th>Desperdicio</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="stock in filteredStocks" :key="stock.id">
            <td>{{ stock.category.name }}</td>
            <td>{{ stock.product.product_name }}</td>
            <td>{{ stock.vendor?.name || 'N/A' }}</td>
            <td>{{ stock.stock_quantity }}</td>
            <td>{{ stock.current_quantity }}</td>
            <td>{{ stock.waste_quantity }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    categories: Array,
    products: Array,
    vendors: Array,
    stocks: Array,
  },
  data() {
    return {
      selectedCategory: "",
      selectedProduct: "",
      filteredStocks: this.stocks, // Datos filtrados
    };
  },
  methods: {
    filterData() {
      // Filtrar los datos en el frontend
      this.filteredStocks = this.stocks.filter(stock => {
        return (
          (!this.selectedCategory || stock.category.id === parseInt(this.selectedCategory)) &&
          (!this.selectedProduct || stock.product.id === parseInt(this.selectedProduct))
        );
      });
    },
  },
};
</script>

<style scoped>
.table {
  font-size: 14px;
}
</style>
