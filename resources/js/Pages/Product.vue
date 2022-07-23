<script setup>
import { Head } from "@inertiajs/inertia-vue3";

const props = defineProps(["products"]);
</script>
<template>
    <Head title="Product List" />
    <div class="d-flex flex-wrap mt-5 justify-content-center">
        <div
            class="card m-3"
            style="width: 18rem"
            v-for="product in products"
            :key="product.id"
        >
            <div class="position-relative">
                <img
                    class="card-img-top"
                    :src="product.image"
                    alt="Product Image"
                />
                <span
                    class="position-absolute badge rounded-pill bg-success"
                    style="right: 10px; bottom: 10px; font-size: 0.8rem"
                    >$ {{ product.price }}</span
                >
            </div>
            <div class="card-body">
                <h4 class="card-title text-center">{{ product.name }}</h4>
            </div>
        </div>
    </div>
    <button
        class="btn btn-primary rounded-circle position-fixed fs-4 shadow"
        style="right: 3rem; bottom: 3rem; width: 3rem; height: 3rem;"
    >
        <i class="bi bi-plus"></i>
    </button>
</template>
