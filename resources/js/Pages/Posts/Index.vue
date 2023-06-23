<template>

    <Head>
        <title>Municipal List</title>
    </Head>

    <div class="row gap-10 masonry pos-r">
        <div class="peers fxw-nw jc-sb ai-c">
            <h3>Municipal List</h3>
            <div class="peers">
                <div class="peer mR-10">
                    <input v-model="search" type="text" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="peer" >
                    <Link class="btn btn-primary btn-sm" href="/posts/create">Add Municipal</Link>
                    <button class="btn btn-primary btn-sm mL-2 text-white" @click="showFilter()">Filter</button>
                </div>
            </div>
        </div>

        <filtering v-if="filter" @closeFilter="filter=false">
            <label>Sample Inputs</label>
            <input type="text" class="form-control">
            <button class="btn btn-sm btn-primary mT-5 text-white" @click="">Filter</button>
        </filtering>

        <div class="col-12">
            <div class="bgc-white p-20 bd">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Municipal</th>
                            <th scope="col" style="width: 30%">Province Code</th>
                            <th scope="col" style="text-align: right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="municipal in municipalities.data" :key="municipal.id">
                            <td>
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-lg-auto text-center text-lg-start">
                                        {{  municipal.id }}
                                    </div>

                                </div>
                            </td>
                            <td>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip sample">{{ municipal.citymunDesc }}</span>
                            </td>
                            <td>
                                <div class="badge bg-info me-1" >
                                    {{ municipal.provCode }}
                                </div>
                            </td>
                            <td style="text-align: right">
                                <!-- v-if="user.can.edit" -->
                                <div class="dropdown dropstart">
                                    <button class="btn btn-secondary btn-sm action-btn" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                            <path
                                                d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu action-dropdown" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <Link class="dropdown-item" :href="`/posts/${municipal.id}/edit`">Edit</Link>
                                        </li>
                                        <!--
                                        <li><a class="dropdown-item" href="#"
                                                @click="editPermissions(municipal.id)">Permissions</a></li>
                                        <li>
                                            <hr class="dropdown-divider action-divider">
                                        </li>
                                        <li v-if="can.canDeleteUser">
                                            <Link class="text-danger dropdown-item" @click="deleteUser(municipal.id)">Delete
                                            </Link>
                                        </li>
                                        -->
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <!-- read the explanation in the Paginate.vue component -->
                        <!-- <pagination :links="users.links" /> -->
                        <!-- <pagination :next="users.next_page_url" :prev="users.prev_page_url" /> -->
                        <pagination :next="municipalities.next_page_url" :prev="municipalities.prev_page_url" /> 
                        pagination here
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import Pagination from "@/Shared/Pagination";
import { Inertia } from "@inertiajs/inertia";
import { ref, watch } from "vue";

defineProps ({
    municipalities : Object,

});

let search = ref('');

watch (search, value =>{
    Inertia.get('/posts', {search:value},{
        preserveState : true,
    });

});

function deleteUser(id) {
            let text = "WARNING!\nAre you sure you want to delete the record?";
              if (confirm(text) == true) {
                this.$inertia.delete("/users/" + id);
              }
        }

function showFilter() {
    this.filter = !this.filter
}

function editPermissions(userId) {
            var vm = this
            var user = _.find(this.users.data, { id: userId })
            this.showModal = true
            this.selectedUser = userId
            this.selectedPermissions = []

            _.forEach(user.permissions, function(e) {
                vm.selectedPermissions.push(e.id)
            })
            this.getAllPermissions() 
}
        
function updatePermissions() {

     axios.post('update-user-permissions', {
             'user_id' : this.selectedUser,
              'permissions' : this.selectedPermissions
        }).then(response => {
                    this.$inertia.reload({ only: ['users'] })
    })              
}

function closeModal() {
    this.showModal = false
}

async function getAllPermissions() {
    await axios.post('get-all-permissions').then( response => {
        this.permissions = _.groupBy(response.data, 'permission_group');
     })
}

</script>

