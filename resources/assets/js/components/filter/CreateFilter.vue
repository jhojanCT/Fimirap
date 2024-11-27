<template>
    <div class="create-filter-modal">
      <div class="modal fade" id="create-filter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
            </div>
            <div class="modal-body">
              <form @submit.prevent="createProduct">
                <div class="form-group">
                  <label for="new-product-name">Nombre del Producto</label>
                  <input type="text" id="new-product-name" v-model="newProduct.product_name" class="form-control" required />
                </div>
                <div class="form-group">
                  <label for="new-product-quantity">Cantidad</label>
                  <input type="number" id="new-product-quantity" v-model="newProduct.current_qty" class="form-control" required />
                </div>
                <div class="form-group">
                  <label for="new-product-waste">Desperdicio</label>
                  <input type="number" id="new-product-waste" v-model="newProduct.waste_qty" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary">Agregar</button>
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
        newProduct: {
          product_name: "",
          current_qty: 0,
          waste_qty: 0
        }
      };
    },
    methods: {
      openModal() {
        $("#create-filter-modal").modal("show");
      },
      closeModal() {
        this.newProduct = {
          product_name: "",
          current_qty: 0,
          waste_qty: 0
        };
        $("#create-filter-modal").modal("hide");
      },
      createProduct() {
        axios
          .post("/api/products", this.newProduct)
          .then((response) => {
            EventBus.$emit("filter-updated", response.data);
            this.closeModal();
          })
          .catch((error) => {
            console.error("Error al agregar el producto:", error);
          });
      }
    }
  };
  </script>
  
  <style scoped>
  .create-filter-modal {
    margin-top: 20px;
  }
  </style>
  