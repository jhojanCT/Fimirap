<template>
    <div class="update-filter-modal">
      <div class="modal fade" id="update-filter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Actualizar Producto</h5>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updateProduct">
                <div class="form-group">
                  <label for="product-quantity">Cantidad Actual</label>
                  <input type="number" id="product-quantity" v-model="product.current_qty" class="form-control" required />
                </div>
                <div class="form-group">
                  <label for="product-waste">Desperdicio</label>
                  <input type="number" id="product-waste" v-model="product.waste_qty" class="form-control" required />
                </div>
                <div class="form-group">
                  <label for="product-name">Nombre del Producto</label>
                  <input type="text" id="product-name" v-model="product.product_name" class="form-control" disabled />
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Cerrar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import EventBus from "@/utils/EventBus";
  
  export default {
    data() {
      return {
        product: {
          id: null,
          product_name: "",
          current_qty: 0,
          waste_qty: 0
        }
      };
    },
    methods: {
      openModal(productId) {
        this.fetchProduct(productId);
        $("#update-filter-modal").modal("show");
      },
      closeModal() {
        this.product = {
          id: null,
          product_name: "",
          current_qty: 0,
          waste_qty: 0
        };
        $("#update-filter-modal").modal("hide");
      },
      fetchProduct(id) {
        axios.get(`/api/products/${id}`).then((response) => {
          this.product = response.data;
        });
      },
      updateProduct() {
        axios
          .put(`/api/products/${this.product.id}`, this.product)
          .then((response) => {
            EventBus.$emit("filter-updated", response.data);
            this.closeModal();
          })
          .catch((error) => {
            console.error("Error al actualizar el producto:", error);
          });
      }
    }
  };
  </script>
  
  <style scoped>
  .update-filter-modal {
    margin-top: 20px;
  }
  </style>
  