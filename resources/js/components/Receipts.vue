<template>

    <div>
        <template>
            <div>
                <button class="btn btn-primary mb-2" @click="showCreateModal">
                    Add receipt +
                </button>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice No.</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Paid at</th>
                            <th>Created at</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(receipt, i) in receipts.data" :key="i">
                            <th>{{ receipts.data.length-i }}</th>
                            <td>{{ receipt.invoice_id }}</td>
                            <td>{{ receipt.customer.name }}</td>
                            <td>{{ receipt.date }}</td>
                            <td>{{ receipt.paid_at}}</td>
                            <td>{{ $root.formattedDate(receipt.created_at) }}</td>
                            <td>
                                <button class="btn btn-primary" @click="edit(receipt.id)">Edit</button>
                                <button class="btn btn-success" @click="show(receipt.id)">Show</button>
                                <button class="btn btn-danger" @click="print(receipt.id)">Print</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="modal" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <span v-show="is_edit">Edit Receipt</span>
                                    <span v-show="!is_edit">Add Receipt</span>
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
                                                <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
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
                                            <td>{{total}}</td>
                                            <td></td>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" v-if="!is_edit" @click="create">Create</button>
                                <button type="button" class="btn btn-primary" v-if="is_edit" @click="edited">Edit</button>
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
                                            <span>{{getCustomerById }}</span>
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
                                                <td>{{ detail.quantity * detail.price }}</td>
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
                                            <td>{{total}}</td>
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

    </div>

</template>
<script>
import moment from 'moment'


export default {
    data() {
        return {
            customers: [],
            total: 0,
            last_receipt_num: 0,
            is_edit: false,

            actions: {
                edit: false,
                create: true,
            },
            receipts: [],

            receipt: {
                id: '',
                customer_id: '',
                date: moment(new Date).format('YYYY-MM-DD'),
                details: [{
                    'name': '',
                    'price': '',
                    'quantity': 1,
                    'note': ''
                }]
            },

        }

    },
    methods: {

        deleteRow(id) {
            
            let dt = this.receipt.details[id]
            this.receipt.details.splice(id,1);
            
            console.log(dt)
            if (dt.id != '') {
                axios.delete('/api/receipt/detail/' + dt.id).then(r => console.log(r.data)).catch(e => {
                    toastr.error(e.response.data.message)
                })
            }
        },

        getTotal() {
            this.total = this.receipt.details.reduce((acc, item) => acc + (item.price * item.quantity), 0)
        },
        print(id) {
            axios.get("/api/receipt/" + id).then(resp => {
                this.receipt.customer_id = resp.data[0].customer_id
                this.receipt.date = resp.data[0].date
                this.receipt.details = resp.data[0].details
                this.last_receipt_num = resp.data[0].invoice_id
                $("#printModal").modal()
            }).then(function () {
                let w = window.open("", "Test", "width=600,height=600,scrollbars=1,resizable=1")
                w.document.write(document.querySelector('#printModal .modal-body').innerHTML)
                w.document.close()
                w.print()
            })
        },
        show(id) {
            this.is_edit = false

            axios.get("/api/receipt/" + id).then(resp => {
                this.receipt.customer_id = resp.data[0].customer_id
                this.receipt.date = resp.data[0].date
                this.receipt.details = resp.data[0].details
                this.last_receipt_num = resp.data[0].invoice_id
                $("#printModal").modal()
            })

        },
        edit(id) {
            this.is_edit = true

            axios.get("/api/receipt/" + id).then(resp => {
                this.receipt.customer_id = resp.data[0].customer_id
                this.receipt.date = resp.data[0].date
                this.receipt.details = resp.data[0].details
                this.receipt.id = id
                this.last_receipt_num = resp.data[0].invoice_id
                this.actions.edit = true;
                this.actions.create = false;

            })
            $('#receiptModal').modal('show');
        },

        edited() {
            axios.put("/api/receipt/" + this.receipt.id, this.receipt).then((resp) => console.log(resp.data))
        },


        getReceipts() {
            axios.get('/api/receipt').then(r => this.receipts = r.data);
        },
        getCustomers() {
            axios.get('/api/customer-list').then(r => this.customers = r.data)
        },
        addRow() {
            this.receipt.details.push({
                "id": '',
                "receipt_id": this.last_receipt_num,
                'name': '',
                'price': '',
                'quantity': 1,
                'note': ''
            });
        },
        reset() {
            this.receipt = {
                customer_id: '',
                date: moment(new Date).format('YYYY-MM-DD'),
                details: [{
                    'name': '',
                    'price': '',
                    'quantity': 1,
                    'note': ''
                }]
            }
            this.getReceiptNum();
        },
        create() {
            axios.post('/api/receipt', this.receipt).then(r => {
                this.getReceipts()
                $('#receiptModal').modal('hide')
            })
        },
        showCreateModal() {
            this.is_edit = false
            $('#receiptModal').modal()
            this.reset()
        },
        getReceiptNum() {
            axios.get("/api/last_receipt_num").then(r => this.last_receipt_num = r.data + 1)
        }

    },
    created() {
        this.getCustomers()
        this.getReceipts()

        this.getReceiptNum()
    },
    watch: {
        receipt: {
            handler(val) {
                this.getTotal();
            },
            deep: true,
        }
    },
    computed: {
        getCustomerById() {
            if (this.receipt.customer_id != '') {
                return this.customers.find(item => item.id === this.receipt.customer_id).name

            }

        },
    }
}
</script>