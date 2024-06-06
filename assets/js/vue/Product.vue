<script>
import { ref, onMounted, reactive } from 'vue'
import axios from "axios";
import { toast } from 'vue3-toastify';

export default {
    setup() {
        const paginator = ref({});
        const currentProductDelete = ref (null);
        const currentProductEdit = ref (null);
        const newProduct = reactive({
            name: null,
            description: null,
            price: null
        })

        onMounted(() => {
            getProducts();
        })

        function getProducts(page = 1) {
            axios.get("/api/product/listproducts/" + page).then(response => {
                paginator.value = response.data;
            }).catch(function (error) {
                toast.error(error.message);
            });
        }

        function deleteProduct() {
            axios.delete("/api/product/delete/" + currentProductDelete.value).then(response => {
                $('#deleteModal').modal('hide');
                if (!response.data.error) {
                    toast.success('Produit supprimé.');
                    paginator.value.results = paginator.value.results.filter(p => p.id !== currentProductDelete.value);
                } else {
                    // toast.error(response.data.message);
                }
            }).catch(function (error) {
                $('#deleteModal').modal('hide');
                toast.error(error.message);
            });
        }

        function updateProduct(product) {
            axios.put("/api/product/update", {
                id: product.id,
                name: product.name,
                description: product.description,
                price: product.price
            }).then(response => {
                if (!response.data.error) {
                    toast.success('Produit modifié.');
                    getProducts(paginator.value.currentPage);
                    currentProductEdit.value = null;
                } else {
                    toast.error(response.data.message);
                }
            }).catch(function (error) {
                toast.error(error.message);
            });
        }

        function addProduct() {
            axios.post("/api/product/add", {
                name: newProduct.name,
                description: newProduct.description,
                price: newProduct.price
            }).then(response => {
                $('#addModal').modal('hide');
                if (!response.data.error) {
                    toast.success('Produit ajouté.');
                    getProducts(paginator.value.currentPage);
                    newProduct.value = {
                        name: null,
                        description: null,
                        price: null
                    };
                } else {
                    toast.error(response.data.message);
                }
            }).catch(function (error) {
                $('#addModal').modal('hide');
                toast.error(error.message);
            });
        }

        return {
            paginator,
            currentProductDelete,
            currentProductEdit,
            newProduct,
            getProducts,
            deleteProduct,
            updateProduct,
            addProduct
        }
    }
}
</script>

<template>
    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3 text-center">
                <h1>Liste des produits</h1>
                <p class="text-secondary">(Données réelles)</p>
            </div>
            <div class="d-flex flex-row-reverse mb-2">
                <div class="btn-group">
                    <button class="btn btn-info" title="Ajouter un produit" data-toggle="modal" data-target="#addModal"><i class="bi bi-plus-circle"></i>&nbsp; Ajouter</button>
                </div>
            </div>
            <table class="table">
                <thead>
                <tr class="text-center">
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in paginator.results">
                    <td class="align-middle col-4">
                        <span v-if="currentProductEdit === product.id">
                            <input type="text" class="form-control" v-model="product.name">
                        </span>
                        <span v-else>
                            {{ product.name }}
                        </span>
                    </td>
                    <td class="align-middle col-6">
                        <span v-if="currentProductEdit === product.id">
                            <input type="text" class="form-control" v-model="product.description">
                        </span>
                        <span v-else>
                            {{ product.description }}
                        </span>
                    </td>
                    <td class="text-center align-middle col-1">
                        <span v-if="currentProductEdit === product.id">
                            <input type="number" class="form-control" v-model="product.price">
                        </span>
                        <span v-else>
                            {{ product.price }} €
                        </span>
                    </td>
                    <td class="text-center align-middle col-1">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-info" title="Editer le produit" @click="this.currentProductEdit = product.id">
                                <i v-if="currentProductEdit === product.id" class="bi bi-save" @click="updateProduct(product)"></i>
                                <i v-else class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Supprimer le produit" data-toggle="modal" data-target="#deleteModal" @click="this.currentProductDelete = product.id"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <nav class="row justify-content-md-center" v-if="paginator.toPaginate">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: paginator.currentPage === 1  }">
                        <button class="page-link" @click="getProducts(paginator.previousPage)" tabindex="-1">Previous</button>
                    </li>

                    <li v-for="i in paginator.lastPage" class="page-item" :class="{ active: paginator.currentPage === i }">
                        <span v-if="paginator.currentPage === i" class="page-link">
                            {{ i }}
                            <span class="sr-only">(current)</span>
                        </span>
                        <button v-else class="page-link" @click="getProducts(i)">{{ i }}</button>
                    </li>

                    <li class="page-item" :class="{ disabled: paginator.currentPage === paginator.lastPage  }">
                        <button class="page-link" @click="getProducts(paginator.nextPage)">Next</button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce produit ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" @click="deleteProduct">Valider</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajout d'un produit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" class="form-control" id="name" v-model="newProduct.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" v-model="newProduct.description">
                        </div>
                        <div class="form-group">
                            <label for="price">Prix</label>
                            <input type="number" class="form-control" id="price" v-model="newProduct.price">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" @click="addProduct">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</template>
