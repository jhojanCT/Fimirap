<template>
    <div>
        <h3>Lista de productos filtrados</h3>
        <ul>
            <li v-for="product in products" :key="product.id">
                {{ product.name }} - {{ product.quantity }}
            </li>
        </ul>
        <p>Proveedores: {{ vendors }}</p>
        <p>Categorías: {{ categorys }}</p>
    </div>
</template>

<script>
export default {
    props: {
        vendors: Array,
        categorys: Array,
        products: Array,
    },
};
</script>
