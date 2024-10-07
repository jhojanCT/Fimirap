<template>
  <div class="container">

    <div class="row" v-if="isLoading">
      <h1 class="loading-message">Cargando datos... Un momento por favor.</h1>
    </div>

    <div class="row" v-if="!isLoading">
      <div v-for="(item, index) in infoItems" :key="index" class="col-lg-4 col-md-6 col-sm-12">
        <div class="info-card">
          <div class="info-icon">
            <i class="material-icons">{{ item.icon }}</i>
          </div>
          <div class="info-content">
            <div class="info-title">{{ item.title }}</div>
            <div class="info-value">{{ item.value }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../vue-asset";
import mixin from "../../mixin";

export default {
  data() {
    return {
      info: {},
      isLoading: true,
      infoItems: []
    };
  },
  
  created() {
    this.getData();
  },

  methods: {
    getData() {
      axios.get(base_url + "info-box").then((response) => {
        this.info = response.data;
        this.isLoading = false;

        // Actualiza infoItems con los datos reales
        this.infoItems = [
          { title: 'Clientes', value: this.info.total_customer, icon: 'person' },
          { title: 'Proveedores', value: this.info.total_vendor, icon: 'local_shipping' },
          { title: 'Productos', value: this.info.total_product, icon: 'inventory' },
          { title: 'Facturas', value: this.info.total_invoice, icon: 'receipt_long' },
          { title: 'Existencia Total', value: this.info.total_quantity, icon: 'layers' },
          { title: 'Existencia Vendida', value: this.info.total_sold_quantity, icon: 'shopping_cart' },
          { title: 'Existencia Actual', value: this.info.total_current_quantity, icon: 'bar_chart' },
          { title: 'Importe Vendido', value: `$ ${this.info.total_sold_amount}`, icon: 'attach_money' },
          { title: 'Importe Pagado', value: `$ ${this.info.total_paid_amount}`, icon: 'payment' },
          { title: 'Beneficio Bruto', value: `$ ${this.info.total_gross_profit}`, icon: 'stacked_bar_chart' },
          { title: 'Beneficio Neto', value: `$ ${this.info.total_net_profit}`, icon: 'monetization_on' },
        ];
      });
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 1100px;
  margin: auto;
  padding: 3rem;
  background-color: #f4f4f4;
}

.loading-message {
  text-align: center;
  font-size: 1.5rem;
  color: #2e7d32;
}

.row {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-left: 3px
}

.info-card {
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  display: flex;
  align-items: center;
  transition: transform 0.3s ease;
}

.info-card:hover {
  transform: translateY(-5px);
}

.info-icon {
  background-color: #2e7d32;
  border-radius: 50%;
  padding: 1rem;
  color: #ffffff;
  font-size: 2rem;
  margin-right: 1rem;
}

.info-content {
  flex-grow: 1;
}

.info-title {
  font-size: 2rem;
  color: #4caf50;
  font-weight: 600;
}

.info-value {
  font-size: 2.2rem;
  color: #212121;
  font-weight: bold;
}
</style>
