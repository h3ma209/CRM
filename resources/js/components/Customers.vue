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
                        <button class="btn btn-block mt-2 btn-danger" @click="openMonthlyReceiptModal(customer)"><i class="fa fa-plus"></i> Monthly Receipt</button>
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
        <div class="modal" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">

                            <span>Create Monthly Receipt</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Customer Name</label>
                                    <select v-model="receipt.customer_id" class="form-control">
                                        <option value="" disabled>Select customer</option>
                                        <option v-for="customer in customers.data" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Date</label>
                                    <input type="date" class="form-control" v-model="receipt.date">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Receipt Num:</label>
                                    <input type="number" class="form-control" v-model="last_receipt_num" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary m-3" @click="addRow">
                                Add +
                            </button>
                            <table class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Currency</th>
                                        <th>Note</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id='tbody'>
                                    <tr v-for="detail, i in receipt.details" :key="i">
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" type="text" v-model="detail.name">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" type="number" v-model.number="detail.quantity">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" type="number" v-model.number="detail.price">
                                            </div>
                                        </td>
                                        <td>{{ detail.quantity * detail.price }}</td>
                                        <td>
                                            <select v-model="detail.currency" class="form-control">
                                                <option value="" disabled>Select Currency</option>
                                                <option v-for="currency,i in currencies" :key="i" :value="currency">{{ currency }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" rows="1" v-model="detail.note"></textarea>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" @click="deleteRow(i)">Delete</button>
                                        </td>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total: {{Number(total).toLocaleString()}}</td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" v-if="!is_edit" @click="createMonthlyReceipt(customer_contract.customer_id)">Create</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- print modal -->
        <div class="modal" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span>Receipt</span>

                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for=""><strong>Name:</strong></label>
                                    <span>{{getCustomerById}}</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for=""><strong>Date:</strong></label>
                                    <span>
                                        {{receipt.date}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for=""><strong>No:</strong></label>
                                    <span>{{last_receipt_num}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <table class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody id='tbody'>
                                    <tr v-for="detail, i in receipt.details" :key="i">
                                        <td>
                                            <div class="form-group">
                                                <span type="text">{{detail.name}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <span type="number">{{detail.quantity}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <span type="number">{{detail.price}}</span>
                                            </div>
                                        </td>
                                        <td>{{ Number(detail.quantity * detail.price).toLocaleString() }}</td>
                                        <td>
                                            <div class="form-group">
                                                <p>{{detail.note}}</p>
                                            </div>
                                        </td>

                                    </tr>
                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total: {{Number(total).toLocaleString()}}</td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">

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

            total: 0,
            last_receipt_num: 0,
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
            receipt: {
                id: '',
                customer_id: '',

                date: moment(new Date).format('YYYY-MM-DD'),
                contract_id: '',
                details: [{
                    'name': 'Monthly Payment',
                    'price': 0,
                    'quantity': 1,
                    'currency': "IQD",
                    'note': '',
                }]
            },
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
        getTotal() {
            this.total = this.receipt.details.reduce((acc, item) => acc + (item.price * item.quantity), 0)
        },
        resetReceipt() {
            this.receipt = {
                customer_id: '',
                date: moment(new Date).format('YYYY-MM-DD'),
                details: [{
                    'name': '',
                    'price': '',
                    'quantity': 1,
                    'currency': "IQD",
                    'note': ''
                }]
            }
            this.getReceiptNum();
        },
        addRow() {
            console.log('adding')
            this.receipt.details.push({
                "id": '',
                "receipt_id": this.last_receipt_num,
                'name': 'Monthly Payment',
                'price': this.customer_contract.monthly_payment,
                'quantity': 1,
                "currency": this.customer_contract.monthly_payment_currency,
                'note': ''
            });
        },
        deleteRow(id) {
            let dt = this.receipt.details[id]
            this.receipt.details.splice(id, 1);
            console.log(dt)
            if (dt.id != '') {
                axios.delete('/api/receipt/detail/' + dt.id).then(r => console.log(r.data)).catch(e => {
                    toastr.error(e.response.data.message)
                })
            }
        },


        openMonthlyReceiptModal(customer) {
            if (confirm('are you sure?')) {
                this.getCustomerContracts(customer)
                $("#receiptModal").modal()
            }
        },
        createMonthlyReceipt(id) {
            axios.post(`/api/customer/${id}/new-monthly-receipt`, this.receipt).then(response => {
                
                this.customer_monthly_receipt = response.data
                toastr.success('successfully created')
                this.getReceiptNum()
                this.printLayout(document.querySelector('#printModal .modal-body').innerHTML, `.row{font-size:14px;margin:10px;} .table{font-size:14px;}`)
            }).catch(e => {
                console.log(e)
                toastr.error(e.message)
            })
        },
        printLayout(content, css) {
            const w = 768
            const h = 600
            const y = window.top.outerHeight / 2 + window.top.screenY - (h / 2)
            const x = window.top.outerWidth / 2 + window.top.screenX - (w / 2)
            const newWindow = window.open('', '', `top=${y},left=${x},width=${w},height=${h},toolbar=0,titlebar=0,scrollbars=0,status=0`)

            const style = `<style>
            
                
            </style>`

            newWindow.document.open()
            newWindow.document.write(`<html>`);
            newWindow.document.write(`<head><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></head>`);
            newWindow.document.write(`<body>`);
            newWindow.document.write(`<style>
            *, *::before, *::after { box-sizing: border-box; }

                body {
                    margin: 0;
                    padding: 0;

                    -webkit-transform: rotate(-90deg) scale(.68,.68); 
                    -moz-transform:rotate(-90deg) scale(.58,.58); 
                    font-family: sans-serif;
                    
                }
                @media print {

                    @page {size: landscape;}
                    ${css}
                }`)
            newWindow.document.write(`${css}`)
            newWindow.document.write('</style>')


            newWindow.document.write(`<div id="content">${content}</div>`);

            newWindow.document.write(`</body>`);
            newWindow.document.write(`</html>`);

            newWindow.document.close()
            setTimeout(function () {
                newWindow.focus()
                newWindow.print()
            }, 1000);

            newWindow.onafterprint = function () {
                newWindow.close()
            }
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
        getCustomerContracts(customer) {
            axios.get(`/api/customer/${customer.id}/contracts`).then(resp => {
                let r = resp.data.data[0]
                // r.number_of_months = Number(1)
                // r.receipt_note = "payment of"
                console.log(r)
                this.customer_contract = r
                this.receipt.customer_id = this.customer_contract.customer_id
                this.receipt.details[0].price = this.customer_contract.monthly_payment
                this.receipt.details[0].currency = this.customer_contract.monthly_payment_currency


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
        // updateReceiptNote() {
        //     // let u_n = this.customer_contract.user_quantity
        //     // let m_p = this.customer_contract.monthly_payment
        //     let n_m = this.customer_contract.number_of_months
        //     this.customer_contract.receipt_note = `Payment for ${u_n} users of ${n_m} months`
        //     console.log(this.customer_contract.receipt_note)
        // },
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
        },
        getReceiptNum() {
            axios.get("/api/receipt/new-invoice-no").then(r => this.last_receipt_num = r.data)
        }
    },
    created() {
        this.getCustomers();
        this.getReceiptNum();
    },
    computed: {
        getCustomerById() {
            if (this.receipt.customer_id != '') {
                return this.customers.data.find(item => item.id === this.receipt.customer_id).name

            }
        },
    },
    watch: {
        customer_contract: {
            handler(val) {
                // this.updateReceiptNote()
            },
            deep: true
        },
        receipt: {
            handler(val) {
                this.getTotal();
                console.log(val)
            },
            deep: true,
        }
    }
};
</script>