<template>
    <div>
        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#contractModal">Add Contract +</button>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Start Date</th>
                    <th>Expire Date</th>
                    <th>Prepayment</th>
                    <th>Monthly Payment</th>
                    <th>User Quantity</th>
                    <th>Note</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="(contract, i) in contracts.data" :key="i">
                    <th>{{ contracts.data.length-i }}</th>
                    <td>{{ contract.customer.name }}</td>
                    <td>{{ contract.start_date }}</td>
                    <td>{{ contract.expire_date }}</td>
                    <td>
                        {{ contract.prepayment }}
                        <span v-if="contract.prepayment_currency == 'IQD'" class="badge badge-success">{{ contract.prepayment_currency  }}</span>
                        <span v-if="contract.prepayment_currency == 'USD'" class="badge badge-primary">{{ contract.prepayment_currency  }}</span>
                    </td>
                    <td>
                        {{ contract.monthly_payment}}
                        <span v-if="contract.monthly_payment_currency == 'IQD'" class="badge badge-success">{{ contract.monthly_payment_currency  }}</span>
                        <span v-if="contract.monthly_payment_currency == 'USD'" class="badge badge-primary">{{ contract.monthly_payment_currency  }}</span>
                    </td>
                    <td>{{ contract.user_quantity }}</td>
                    <td>{{ contract.note }}</td>
                    <td>{{ contract.free_trial_durtaion }}</td>
                    <td>{{ $root.formattedDate(contract.created_at) }}</td>
                    <td>
                        <button class="btn btn-primary" @click="edit(contract.id)">Edit</button>
                        <a class="btn btn-success" :href="'/customer/'+contract.customer_id+'/receipts'">Receipts</a>
                        <a class="btn btn-warning" :href="'/customer/'+contract.customer_id+'/contracts'">Contracts</a>
                        <button class="btn btn-danger" @click="deleteContract(contract.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="contractModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Contract</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Customer Name</label>
                                    <select v-model="contract.customer_id" class="form-control">
                                        <option value="" disabled>Select customer</option>
                                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Start Date</label>
                                            <input type="date" class="form-control" v-model="contract.start_date">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">Expire Date</label>
                                            <input type="date" class="form-control" v-model="contract.expire_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Prepayment</label>
                                            <input type="number" class="form-control" v-model="contract.prepayment">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Currency</label>
                                            <select v-model="contract.prepayment_currency" class="form-control">
                                                <option value="" disabled>Select Currency</option>
                                                <option v-for="currency,i in currencies" :key="i" :value="currency">
                                                    <span v-if="currency == 'IQD'" class="badge badge-success">{{ currency  }}</span>
                                                    <span v-if="currency == 'USD'" class="badge badge-primary">{{ currency  }}</span>
                                                </option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-6">
                                            <label for="">Monthly Payment</label>
                                            <input type="number" class="form-control" v-model="contract.monthly_payment">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Currency</label>
                                            <select v-model="contract.monthly_payment_currency" class="form-control">
                                                <option value="" disabled>Select Currency</option>
                                                <option v-for="currency,i in currencies" :key="i" :value="currency">{{ currency }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">User Quantity</label>
                                    <input type="number" class="form-control" v-model="contract.user_quantity" min='1'>
                                </div>
                                <div class="form-group">
                                    <label for="">Free Trial Durtation</label>
                                    <input type="text" class="form-control" v-model="contract.free_trial_duration">
                                </div>
                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea class="form-control" v-model="contract.note"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col mt-2">
                                <table class="table table-bordered table-stripped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Note</th>

                                        </tr>
                                    </thead>
                                    <tbody id='tbody'>
                                        <tr v-for="(user,i) in credentials" :key="i">
                                            <td>
                                                {{i+1}}
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" v-model="user.username" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" v-model="user.password" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" v-model="user.note" required>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger" @click="deleteCred(i)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" v-if="!is_edit" @click="create">Create</button>
                        <button type="button" class="btn btn-primary" v-if="is_edit" @click="update">Edit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="reset">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    components: {},
    data() {
        return {
            contracts: [],
            currencies: ["IQD", "USD"],
            is_edit: false,
            credentials: [{
                id: '',
                username: '',
                password: '',
                note: '',
            }],
            contract: {
                id: '',
                customer_id: '',
                start_date: moment(new Date).format("YYYY-MM-DD"),
                expire_date: '',
                prepayment: '',
                monthly_payment: '',
                user_quantity: 1,
                free_trial_duration: '',
                prepayment_currency: '',
                monthly_payment_currency: '',
                note: '',
            },
            customers: [],
        };
    },
    methods: {

        // Contract Related
        deleteContract(id) {
            axios.delete(`/api/contract/${id}`).then(() => {
                toastr.success("Contract Successfully Deleted")
                this.getContracts()
            }).catch(e => toastr.error(e.message))
        },

        getContracts() {
            axios.get("/api/contract").then(r => this.contracts = r.data)
        },

        deleteCred(index) {
            if(this.credentials[index].id !== ''){
                axios.delete('/api/contract/credential/'+this.credentials[index].id).then(toastr.success('Successfully deleted'+this.credentials[index].username))
            }
            this.credentials.splice(index, 1)
            this.contract.user_quantity = this.credentials.length

        },


        create() {
            this.contract.credentials = this.credentials
            axios.post('/api/contract', this.contract).then(r => {
                this.getContracts()
                $('#contractModal').modal('hide')
            }).catch(e => {
                toastr.error(e.message)
            })
        },
        edit(id) {
            this.is_edit = true

            axios.get("/api/contract/" + id).then(resp => {
                this.contract = resp.data
                this.credentials = this.contract.credentials
            }).catch(e => {
                toastr.error(e.message)
            })

            $('#contractModal').modal()
        },
        update() {
            axios.put("/api/contract/" + this.contract.id, this.contract).then(resp => {
                this.contracts = resp.data
            }).catch(e => {
                toastr.error(e.message)
            })
            $('#contractModal').modal("hide")
            this.is_edit = false
            this.getContracts()


        },
        reset() {
            this.contract = {
                id: '',
                customer_id: '',
                start_date: moment(new Date).format("YYYY-MM-DD"),
                expire_date: '',
                prepayment: '',
                monthly_payment: '',
                user_quantity: 1,

                free_trial_duration: '',
                prepayment_currency: '',
                monthly_payment_currency: '',
                note: '',
            }
            this.credentials = []

        },

        // Customers
        getCustomers() {
            axios.get('/api/customer-list').then(r => this.customers = r.data).catch(e => {
                toastr.error(e.message)
            })
        },


    },
    mounted() {
        this.getContracts()
        this.getCustomers()
    },
    watch: {
        contract: {
            handler(val) {
                if (val.credentials) {
                    this.credentials = val.credentials
                    for(var i=0; i< val.user_quantity; i++){
                        let cred = this.credentials[i]
                        if (cred===undefined){
                            this.credentials.push({})
                        }
                    }
                }
                else {
                    let l = []
                    for (var i = 0; i < val.user_quantity; i++) {
                        l.push({})
                    }
                    this.credentials = l
                }
            },
            deep: true,
        }
    }
};
</script>