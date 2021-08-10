<template>
    <div>
        <pagination :data="customers" @pagination-change-page="getCustomers" :limit="5" :show-disabled="true"></pagination>

        <div class="row">
            <div class="col-auto">
                <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#customerModal">Add Customer +</button>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-icon btn-primary" @click="getCustomers"><i class="fa fa-search"></i></button>
                    </div>
                    <input type="date" class="form-control" v-model="filter_start_date">
                    <input type="date" class="form-control" v-model="filter_end_date">
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contanct 1</th>
                    <th>Contanct 2</th>
                    <th>Email</th>
                    <th>Note</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(customer, i) in customers.data" :key="i">
                    <th>{{  i+1 }}</th>
                    <td>{{ customer.name }}</td>
                    <td>{{ customer.address }}</td>
                    <td>{{ customer.contact_1 }}</td>
                    <td>{{ customer.contact_2 }}</td>
                    <td>{{ customer.email }}</td>
                    <td>{{ customer.note }}</td>
                    <td>{{ $root.formattedDate(customer.created_at) }}</td>
                    <td>
                        <button class="btn btn-primary" @click="edit(customer.id)">Edit</button>
                        <a class="btn btn-success" :href="'/customer/'+customer.id+'/receipts'">Receipts</a>
                        <a class="btn btn-secondary" :href="'/customer/'+customer.id+'/contracts'">Contracts</a>
                        <button class="btn btn-block mt-2 btn-danger" @click="openMonthlyReceiptModal(customer.id)"><i class="fa fa-plus"></i> Monthly Receipt</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagination :data="customers" @pagination-change-page="getCustomers" :limit="5" :show-disabled="true"></pagination>

        <div class="modal" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Monthly Receipt</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" v-model="customer.name">
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <input type="text" class="form-control" v-model="customer.address">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Contact 1</label>
                                            <input type="text" class="form-control" v-model="customer.contact_1">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">Contact 2</label>
                                            <input type="text" class="form-control" v-model="customer.contact_2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" v-model="customer.email">
                                </div>
                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea class="form-control" v-model="customer.note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="!is_edit" type="button" class="btn btn-primary" @click="create">Create</button>
                        <button v-if="is_edit" type="button" class="btn btn-primary" @click="edited">Edit</button>

                        <button v-if="!is_edit" type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>

                        <button v-if="is_edit" type="button" class="btn btn-secondary" data-dismiss="modal" @click="reset">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="monthlyModal" tabindex="-1" role="dialog" aria-labelledby="monthlyModal" aria-hidden="true">
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
                                    <input type="text" v-model="customer_contract.customer.name" disabled>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Start Date</label>
                                            <input type="date" class="form-control" v-model="customer_contract.start_date" disabled>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="">Expire Date</label>
                                            <input type="date" class="form-control" v-model="customer_contract.expire_date" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="">Prepayment</label>
                                            <input type="number" class="form-control" v-model="customer_contract.prepayment" disabled>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Currency</label>
                                            <select v-model="customer_contract.prepayment_currency" class="form-control" disabled>
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
                                            <input type="number" class="form-control" v-model="customer_contract.monthly_payment" disabled>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Currency</label>
                                            <select v-model="customer_contract.monthly_payment_currency" class="form-control" disabled>
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
                                    <input type="number" class="form-control" v-model="customer_contract.user_quantity" min='1' disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">Free Trial Durtation</label>
                                    <input type="text" class="form-control" v-model="customer_contract.free_trial_duration" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="">No Months:</label>
                                    <input type="number" class="form-control" v-model="customer_contract.number_of_months" min='1'>
                                </div>
                                <div class="form-group">
                                    <label for="">Receipt Note</label>
                                    <textarea class="form-control" v-model="customer_contract.receipt_note"></textarea>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Total</label>
                                    <span type="text" class="form-control">{{(customer_contract.user_quantity * customer_contract.monthly_payment) * customer_contract.number_of_months }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="createMonthlyReceipt(customer_contract.id)">Create</button>

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
import moment from 'moment'
export default {
    components: {},
    data() {
        return {
            currencies: ['IQD', "USD"],
            customer_contract: {
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
                total: 0,
                number_of_months: 1,
                receipt_note: "Payment of ",
                note: '',
                customer: {
                    id: '',
                    name: '',
                    address: '',
                    contact_1: '',
                    contact_2: '',
                    email: '',
                    note: ''
                },

            },
            customer_monthly_receipt: [],
            is_edit: false,
            customers: {},
            customer: {
                id: '',
                name: '',
                address: '',
                contact_1: '',
                contact_2: '',
                email: '',
                note: ''
            },

            filter_start_date: moment(new Date).format('YYYY-MM-DD'),
            filter_end_date: moment(new Date).format('YYYY-MM-DD'),
        };
    },
    methods: {

        openMonthlyReceiptModal(id) {


            if (confirm('are you sure?')) {
                this.getCustomerContracts(id)
                $("#monthlyModal").modal()
            }
        },
        createMonthlyReceipt(id) {
            axios.get(`/api/customer/${id}/new-monthly-receipt`).then(response => {
                this.customer_monthly_receipt = response.data
                toastr.success('successfully created')

                let w = window.open("", "Test", "width=600,height=600,scrollbars=1,resizable=1")
                w.document.write(`
                        <html>
                            <head></head>
                            <body>
                                test
                                <table border="1" cell-border="1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                </table>
                            </body>
                        </html>
                    `)
                w.document.close()

                setTimeout(function () {

                    w.focus()
                    w.print()
                }, 100)
                w.onafterprint = function () {
                    w.close()
                }
            }).catch(e => {
                toastr.error(e.response.data.message)
            })
        },

        reset() {
            this.customer = {
                name: '',
                address: '',
                contact_1: '',
                contact_2: '',
                email: '',
                note: '',
                id: ''
            }
            this.is_edit = false
        },
        edit(id) {
            this.is_edit = true

            axios.get("/api/customer/" + id).then(resp => {
                this.customer.name = resp.data.name
                this.customer.address = resp.data.address
                this.customer.contact_1 = resp.data.contact_1
                this.customer.contact_2 = resp.data.contact_2
                this.customer.email = resp.data.email
                this.customer.note = resp.data.note
                this.customer.id = resp.data.id
            })
            $('#customerModal').modal()
        },
        getCustomerContracts(id) {
            axios.get(`/api/customer/${id}/contracts`).then(resp => {
                let r = resp.data.data[0]
                r.number_of_months = Number(1)
                r.receipt_note = "payment of"
                console.log(r)
                this.customer_contract = r
            });

        },
        edited() {
            let _this = this
            axios.put("/api/customer/" + this.customer.id, this.customer);
            $('#customerModal').modal("hide")
            this.is_edit = false
            let index = this.customers.data.findIndex(item => item.id === _this.customer.id)
            _this.customers.data[index] = _this.customer
            this.reset()
        },
        getCustomers(page = 1) {
            axios.get("/api/customer?page=" + page, {
                params: {
                    start_date: this.filter_start_date,
                    end_date: this.filter_end_date,
                }
            }).then(r => this.customers = r.data)
        },
        updateReceiptNote() {
            let u_n = this.customer_contract.user_quantity
            let m_p = this.customer_contract.monthly_payment
            let n_m = this.customer_contract.number_of_months
            this.customer_contract.receipt_note = `Payment for ${u_n} users of ${n_m} months`
            console.log(this.customer_contract.receipt_note)
        },
        create() {
            axios.post('/api/customer', this.customer).then(r => {
                this.getCustomers()
                $('#customerModal').modal('hide')
            }).catch(e => {
                let errors = ''
                Object.keys(e.response.data.errors).forEach(el => {
                    errors += (`<li>${e.response.data.errors[el]}</li>`)
                })
                toastr.error(`<ul>${errors}</ul>`)
            })
        }
    },
    mounted() {
        this.getCustomers();
    },
    watch: {
        customer_contract: {
            handler(val) {
                this.updateReceiptNote()
            },
            deep: true
        }
    }
};
</script>