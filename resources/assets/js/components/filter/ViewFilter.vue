<template>
    <div class="filter-view">
      <h4>Filtrar Productos</h4>
      <div class="alert alert-info" v-if="filteredProducts.length === 0">No hay productos para mostrar.</div>
  
      <div v-for="product in filteredProducts" :key="product.id" class="product-item">
        <p><strong>{{ product.product_name }}</strong></p>
        <p>Cantidad actual: {{ product.current_qty }}</p>
        <p>Desperdicio: {{ product.waste_qty }}</p>
        <button @click="editProduct(product.id)" class="btn btn-warning">Actualizar</button>
      </div>
    </div>
  </template>
  
  <script>
  import EventBus from "@/utils/EventBus"; // Asegúrate de importar EventBus si lo usas
  
  export default {
    data() {
      return {
        filteredProducts: []
      };
    },
    created() {
      EventBus.$on("filter-updated", (products) => {
        this.filteredProducts = products;
      });
    },
    methods: {
      editProduct(id) {
        EventBus.$emit("edit-product", id);
      }
    }
  };
  </script>
  
  <style scoped>
  .filter-view {
    margin-top: 20px;
  }
  .product-item {
    margin-bottom: 15px;
  }
  </style>
  