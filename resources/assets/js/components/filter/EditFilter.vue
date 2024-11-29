<template>
    <div class="wrap">
      <div class="modal fade" id="edit-stock" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="defaultModalLabel">Actualizar inventario</h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-danger" v-if="errors">
                <ul>
                  <li v-for="error in errors">{{ error[0] }}</li>
                </ul>
              </div>
              <form>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">palette</i>
                      </span>
                      <div class="form-line">
                        <select class="form-control select2" v-model="stock.category" @change="findProduct">
                          <option value="">Selecciona una categoría</option>
                          <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">shopping_bag</i>
                      </span>
                      <div class="form-line">
                        <select class="form-control select2" v-model="stock.product">
                          <option value="">Selecciona un producto</option>
                          <option v-for="(product, index) in products" :key="index" :value="product.id">{{ product.product_name }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">supervisor_account</i>
                      </span>
                      <div class="form-line">
                        <select class="form-control select2" v-model="stock.vendor">
                          <option value="">Selecciona un proveedor</option>
                          <option v-for="(vendor, index) in vendors" :key="index" :value="vendor.id">{{ vendor.name }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">attach_money</i>
                      </span>
                      <div class="form-line">
                        <input type="text" class="form-control" v-model="stock.buying_price" placeholder="Precio de compra" />
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">attach_money</i>
                      </span>
                      <div class="form-line">
                        <input type="text" class="form-control" v-model="stock.selling_price" placeholder="Precio de venta" />
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">add</i>
                      </span>
                      <div class="form-line">
                        <input type="text" class="form-control" v-model="stock.current_quantity" disabled placeholder="Cantidad actual" />
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="material-icons">assignment</i>
                      </span>
                      <div class="form-line">
                        <input type="text" class="form-control" v-model="stock.note" placeholder="Nota" />
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button @click="updateStock(stock.id)" type="button" class="btn btn-success waves-effect">Actualizar</button>
              <button @click="resetForm" type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
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
    props: ["vendors", "categories"],
    mixins: [mixin],
  
    data() {
      return {
        stock: {
          id: "",
          product: "",
          category: "",
          vendor: "",
          current_quantity: "",
          buying_price: "",
          selling_price: "",
          note: "",
        },
        products: [],
        errors: null,
      };
    },
  
    created() {
      EventBus.$on("edit-stock", (id) => {
        this.stock.id = id;
        this.editStock(id);
        $("#edit-stock").modal("show");
      });
  
      $("#edit-stock").on("hidden.bs.modal", () => {
        this.resetForm();
      });
    },
  
    methods: {
      editStock(id) {
        axios.get(base_url + "stock/" + id + "/edit")
          .then((response) => {
            this.stock = response.data;
          })
          .catch((err) => {
            console.error(err);
          });
      },
  
      findProduct() {
        if (this.stock.category) {
          axios.get(base_url + "category/products/" + this.stock.category)
            .then((response) => {
              this.products = response.data;
            })
            .catch((err) => {
              console.error(err);
            });
        } else {
          this.products = [];
        }
      },
  
      updateStock(id) {
        axios.put(base_url + "stock/" + id, this.stock)
          .then((response) => {
            $("#edit-stock").modal("hide");
            this.resetForm();
            EventBus.$emit("stock-updated", response.data);
          })
          .catch((err) => {
            if (err.response) {
              this.errors = err.response.data.errors;
            }
          });
      },
  
      resetForm() {
        this.stock = {
          product: "",
          vendor: "",
          category: "",
          buying_price: "",
          selling_price: "",
          note: "",
          current_quantity: "",
        };
      },
    },
  };
  </script>
  