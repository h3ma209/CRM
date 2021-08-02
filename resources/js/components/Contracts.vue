<template>
    <div>
        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#contractModal">
            Add Contract +
        </button>

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
                    <td>{{ contract.prepayment }}</td>
                    <td>{{ contract.monthly_payment }}</td>
                    <td>{{ contract.user_quantity }}</td>
                    <td>{{ contract.note }}</td>
                    <td>{{ $root.formattedDate(contract.created_at) }}</td>
                    <td>
                        <button class="btn btn-primary" @click="edit(contract.id)">Edit</button>
                        <a class="btn btn-success" :href="'/customer/'+contract.customer_id+'/receipts'">Receipts</a>
                        <a class="btn btn-danger" :href="'/customer/'+contract.customer_id+'/contracts'">Contracts</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="contractModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
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
                                        <div class="col-sm-6">
                                            <label for="">Monthly Payment</label>
                                            <input type="number" class="form-control" v-model="contract.monthly_payment">
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
                                    <label for="">Note</label>
                                    <textarea class="form-control" v-model="contract.note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" v-if="!is_edit" @click="create">Create</button>
                        <button type="button" class="btn btn-primary" v-if="is_edit" @click="edited">Edit</button>
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
            is_edit: false,
            contract: {
                id: '',
                customer_id: '',
                start_date: moment(new Date).format("YYYY-MM-DD"),
                expire_date: '',
                prepayment: '',
                monthly_payment: '',
                user_quantity: 1,
                note: '',
            },
            customers: [],
        };
    },
    methods: {

        edit(id) {
            this.is_edit = true

            axios.get("/api/contract/" + id).then(resp => {
                this.contract = resp.data
            })
            $('#contractModal').modal()

        },

        edited() {
            let _this = this
            axios.put("/api/contract/" + this.contract.id, this.contract);
            $('#contractModal').modal("hide")
            _this.is_edit = false
            let index = _this.contracts.data.findIndex(item => item.id === _this.contract.id)
            _this.contracts.data[index].id = _this.contract.id
            _this.contracts.data[index].start_date = _this.contract.start_date
            _this.contracts.data[index].expire_date = _this.contract.expire_date
            _this.contracts.data[index].prepayment = _this.contract.prepayment
            _this.contracts.data[index].monthly_payment = _this.contract.monthly_payment
            _this.contracts.data[index].user_quantity = _this.contract.user_quantity
            _this.contracts.data[index].note = _this.contract.note
        },

        reset() {

            this.contract = {
                customer_id: '',
                start_date: '',
                expire_date: '',
                prepayment: '',
                monthly_payment: '',
                user_quantity: 1,
                note: '',
            }
        },
        getContracts() {
            axios.get("/api/contract").then(r => this.contracts = r.data)
        },
        create() {
            axios.post('/api/contract', this.contract).then(r => {
                this.getContracts()
                $('#contractModal').modal('hide')
            })
        },

        // Customers
        getCustomers() {
            axios.get('/api/customer-list').then(r => this.customers = r.data)
        }
    },
    mounted() {
        this.getContracts()
        this.getCustomers()
    },
};
</script>