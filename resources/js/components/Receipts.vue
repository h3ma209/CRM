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
                                <button class="btn btn-warning" @click="print(receipt.id)">Print</button>
                                <button class="btn btn-danger" @click="deleteReceipt(receipt.id)">Delete</button>

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
                                            <select v-if="!receipt.guest" v-model="receipt.customer_id" class="form-control">
                                                <option value="" disabled>Select customer</option>
                                                <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                                            </select>
                                            <input v-if="receipt.guest" type="text" class="form-control" v-model="receipt.name">
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" v-model="receipt.guest">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Guest
                                                </label>
                                            </div>
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
                                            <span>{{getCustomerById.name }}</span>
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
            currencies: ["IQD", "USD"],

            receipt: {
                id: '',
                customer_id: '',
                date: moment(new Date).format('YYYY-MM-DD'),
                details: [{
                    'name': '',
                    'price': '',
                    'quantity': 1,
                    'currency': 'IQD',
                    'note': ''
                }]
            },

        }

    },
    methods: {

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

        getTotal() {
            this.total = this.receipt.details.reduce((acc, item) => acc + (item.price * item.quantity), 0)
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
        print(id) {
            axios.get("/api/receipt/" + id).then(resp => {
                this.receipt.customer_id = resp.data.customer_id
                this.receipt.date = resp.data.date
                this.receipt.details = resp.data.details
                this.last_receipt_num = resp.data.invoice_id
                $("#printModal").modal()
            }).then(() => {
                this.printLayout(document.querySelector('#printModal .modal-body').innerHTML, `.row{font-size:14px;margin:10px;} .table{font-size:14px;}`)
            })
        },
        show(id) {
            this.is_edit = false

            axios.get("/api/receipt/" + id).then(resp => {
                this.receipt.customer_id = resp.data.customer_id
                this.receipt.date = resp.data.date
                this.receipt.details = resp.data.details
                this.last_receipt_num = resp.data.invoice_id
                $("#printModal").modal()
            })

        },
        edit(id) {
            this.is_edit = true

            axios.get("/api/receipt/" + id).then(resp => {
                this.receipt.guest = Boolean(Number(resp.data.customer.guest))
                if (this.receipt.guest) {
                    this.receipt.name = resp.data.customer.name
                }
                this.receipt.customer_id = resp.data.customer_id
                this.receipt.date = resp.data.date
                this.receipt.details = resp.data.details
                this.receipt.id = id
                this.last_receipt_num = resp.data.invoice_id
                this.actions.edit = true;
                this.actions.create = false;

            })
            $('#receiptModal').modal('show');
        },

        edited() {
            axios.put("/api/receipt/" + this.receipt.id, this.receipt).then((resp) => {
                console.log(resp.data)
                $('#receiptModal').modal('hide');
                this.getReceiptNum();
                toastr.success('Successfuly Edited Receipt');
            }).catch(e => {
                toastr.error(e.response.data.message)
            })


        },


        getReceipts() {
            axios.get('/api/receipt').then(r => this.receipts = r.data).catch(e => {
                toastr.error(e.response.data.message)
            });
        },
        deleteReceipt(id) {
            if (confirm("Are you sure you want to delete this receipt ?")) {
                axios.delete('/api/receipt/' + id).then(r => {
                    toastr.success(`Receipt ${id} successfull deleted`)
                    this.getReceipts()

                }).catch(e => {
                    toastr.error(e.message)
                })
            }
        },
        getCustomers() {
            axios.get('/api/customer-list').then(r => this.customers = r.data).catch(e => {
                toastr.error(e.response.data.message)
            })
        },
        addRow() {
            this.receipt.details.push({
                "id": '',
                "receipt_id": this.last_receipt_num,
                'name': '',
                'price': '',
                'quantity': 1,
                "currency": "IQD",
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
                    'currency': "IQD",
                    'note': ''
                }]
            }
            this.getReceiptNum();
        },
        create() {
            axios.post('/api/receipt', this.receipt).then(r => {
                this.getReceipts()
                $('#receiptModal').modal('hide')
                toastr.success('Successfuly Created Receipt')
            }).catch(e => {
                toastr.error(e.response.data.message)
            })
            this.getReceiptNum()
        },
        showCreateModal() {
            this.is_edit = false
            $('#receiptModal').modal()
            this.reset()
        },
        getReceiptNum() {
            axios.get("/api/receipt/new-invoice-no").then(r => this.last_receipt_num = r.data).catch(e => {
                toastr.error(e.response.data.message)
            })
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
                return this.customers.find(item => item.id === this.receipt.customer_id)

            }else{
                return {name:''}
            }

        },
    }
}
</script>