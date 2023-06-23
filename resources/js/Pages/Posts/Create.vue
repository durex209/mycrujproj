<template>
    <div class="row gap-20 masonry pos-r">
        <div class="peers fxw-nw jc-sb ai-c">
            <h3>{{ pageTitle }} Municipality</h3>
            <Link href="/posts">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-lg"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z" />
                <path fill-rule="evenodd"
                    d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z" />
            </svg>
            </Link>
        </div>
        <div class="col-md-8">
            <form @submit.prevent="submit()">
                <!-- 'id', 'citymunDesc', 'regDesc', 'provCode', 'citymunCode' -->
                <text-input v-model="form.citymunDesc" :error="form.errors.citymunDesc" label="City / Municipality" type="citymunDesc" isFocused="focus"/>
                <div class="fs-6 c-red-500" v-if="form.errors.citymunDesc">{{ form.errors.citymunDesc }}</div>

                
                <label for="">psgc Code</label>
                <input type="text" v-model="form.psgcCode" class="form-control" autocomplete="chrome-off">
                <div class="fs-6 c-red-500" v-if="form.errors.psgcCode">{{ form.errors.psgcCode }}</div>

                <label for="">Reg Desc</label>
                <input type="text" v-model="form.regDesc" class="form-control" autocomplete="chrome-off">
                <div class="fs-6 c-red-500" v-if="form.errors.regDesc">{{ form.errors.regDesc }}</div>
                
                <label for="">Provice Code</label>
                <input type="text" v-model="form.provCode" class="form-control" autocomplete="chrome-off">
                <div class="fs-6 c-red-500" v-if="form.errors.provCode">{{ form.errors.provCode }}</div>

                <label for="">City Mun Code</label>
                <input type="text" v-model="form.citymunCode" class="form-control" autocomplete="chrome-off">
                <div class="fs-6 c-red-500" v-if="form.errors.citymunCode">{{ form.errors.citymunCode }}</div>

                <input type="hidden" v-model="form.id" class="form-control" autocomplete="chrome-off">
                <button type="button" class="btn btn-primary mt-3" @click="submit()" :disabled="form.processing">Save
                    changes</button>
            </form>


        </div>
    </div>

  
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import TextInput from '@/Shared/TextInput'

export default {
    components: {
        TextInput,
    },
    props: {
        editData: Object,
    },
    data() {
        return {
            form: useForm({
                citymunDesc: "",
                regDesc: "",
                provCode:"",
                citymunCode:"",
                psgcCode: "",
                id: null
            }),
            municipals:[],
            barangays:[],
            puroks:[],
            testValue:"",
            pageTitle: "",
            loading:false,
        };
    },

    mounted() {

        if (this.editData !== undefined) {
            this.loading = true
            this.pageTitle = "Edit"
            this.form.citymunDesc = this.editData.citymunDesc
            this.form.psgcCode = this.editData.psgcCode
            this.form.regDesc = this.editData.regDesc
            this.form.id = this.editData.id
            this.form.citymunCode = this.editData.citymunCode
            this.form.provCode = this.editData.provCode
        } else {
            this.pageTitle = "Create"
        }

        this.loadMunicipals()
        this.loadBarangays()
    },

    methods: {
        submit() {
            if (this.editData !== undefined) {
                this.form.patch("/posts/" + this.form.id, this.form);
            } else {
                this.form.post("/posts", this.form);
            }
        },

        loadMunicipals() { 
            axios.post('/municipalities').then((response) => {
                this.municipals = response.data
                
            })
        },

        loadBarangays() {
            axios.post('/barangays', {citymunCode:this.form.citymunCode}).then((response) => {
                this.barangays = response.data
                this.puroks = []
            })
        },
    },
};
</script>