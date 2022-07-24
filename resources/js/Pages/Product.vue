<script setup lang="ts">
import { Head } from "@inertiajs/inertia-vue3";
import ProductForm from "@interface/Forms/ProductForm";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, onMounted } from "vue";
import { Modal } from "bootstrap";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps(["products"]);

const form = useForm<ProductForm>({
    _method: null,
    name: null,
    price: null,
    image: null,
});

const modalTitle = ref<string>(null);
const oldImage = ref<File>(null);
const saveModal = ref(null);
let productId = null;
let saveModalObj = null;

onMounted(() => {
    saveModalObj = new Modal(saveModal.value);
});

const createProduct = () => {
    clearForm();
    modalTitle.value = "Create Product";
};

const editProduct = (id) => {
    clearForm();
    modalTitle.value = "Edit Product";
    productId = id;
    let selectedProduct = props.products.find(
        (product) => product.id === productId
    );
    form.name = selectedProduct.name;
    form.price = selectedProduct.price;
    oldImage.value = selectedProduct.image;
};

const onImageUpload = (event) => {
    form.image = event.target.files[0];
};

const saveProduct = (event) => {
    if (productId != null) {
        form._method = "put";
        form.post("/products/" + productId, {
            preserveScroll: true,
            onSuccess: (response: any) => {
                saveModalObj.hide();
            },
            onError: (error: any) => {
                console.log(error);
            },
        });
    } else {
        form.post("/products", {
            preserveScroll: true,
            onSuccess: (response: any) => {
                saveModalObj.hide();
            },
            onError: (error: any) => {
                console.log(error);
            },
        });
    }
};

const deleteProduct = (id) => {
    if (confirm("Are you sure you want to delete this Product?")) {
        Inertia.delete("/products/" + id, {
            preserveScroll: true,
            onError: (error: any) => {
                console.log(error);
            },
        });
    }
};

const clearForm = () => {
    form._method = null;
    form.name = null;
    form.price = null;
    form.image = null;
    form.clearErrors();
    productId = null;
    oldImage.value = null;
    (<HTMLInputElement>document.getElementById("image")).value = null;
};
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
                <div class="row">
                    <div class="col-6 px-2">
                        <button
                            class="btn btn-primary rounded-pill w-100"
                            @click="editProduct(product.id)"
                            data-bs-toggle="modal"
                            data-bs-target="#saveModal"
                        >
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </div>
                    <div class="col-6 px-2">
                        <button class="btn btn-danger rounded-pill w-100" @click="deleteProduct(product.id)">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating Add Button -->
    <button
        class="btn btn-success rounded-circle position-fixed fs-4 shadow"
        style="right: 3rem; bottom: 3rem; width: 3rem; height: 3rem"
        @click="createProduct"
        data-bs-toggle="modal"
        data-bs-target="#saveModal"
    >
        <i class="bi bi-plus"></i>
    </button>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="saveModal" tabindex="-1" ref="saveModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-text="modalTitle"></h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <form @submit.prevent="saveProduct($event)" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="[form.errors.name ? 'is-invalid' : '']"
                                id="name"
                                v-model="form.name"
                            />
                            <div
                                v-if="form.errors.name"
                                class="invalid-feedback"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input
                                type="number"
                                min="0"
                                class="form-control"
                                :class="[form.errors.price ? 'is-invalid' : '']"
                                id="price"
                                v-model="form.price"
                            />
                            <div
                                v-if="form.errors.price"
                                class="invalid-feedback"
                            >
                                {{ form.errors.price }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input
                                type="file"
                                class="form-control"
                                :class="[form.errors.image ? 'is-invalid' : '']"
                                id="image"
                                @input="onImageUpload"
                            />
                            <div
                                v-if="form.errors.image"
                                class="invalid-feedback"
                            >
                                {{ form.errors.image }}
                            </div>
                        </div>
                        <template v-if="oldImage">
                            <label class="form-label">Old Image</label>
                            <img
                                class="w-100"
                                :src="oldImage"
                                alt="Product Image"
                            />
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
