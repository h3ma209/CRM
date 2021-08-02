<template>

    <div>
        <template>
            <div>
                <a class="btn btn-light  mb-2" href="/customers">BACK</a>
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
                                <button class="btn btn-danger" @click="print(receipt)">Print</button>
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
                            <div class="modal-body" v-if="receipt">
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
                            <div class="modal-body" v-if="receipt && receipt.customer">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for=""><strong>Name:</strong></label>
                                            <span>{{ receipt.customer.name }}</span>
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
                                        <tbody id='tbody' v-if="receipt.details">
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

                receipt: {},
            }

        },
        methods: {
            getTotal() {
                this.total = this.receipt.details.reduce((acc, item) => acc + (item.price * item.quantity), 0)
            },
            print(receipt) {
                let css = ''
                let content = ''
                let tbody = ''
                let total = 0

                receipt.details.forEach(detail => {
                    total += Number(detail.quantity) * Number(detail.price)

                    tbody += `
                        <tr>
                            <td>${detail.name}</td>
                            <td>${detail.quantity}</td>
                            <td>${detail.price}</td>
                            <td>${detail.note || ''}</td>
                        </tr>
                    `
                })

                content += `
                    <div class="head">
                        <div>
                            <b>Customer: </b>
                            <span>${receipt.customer.name}</span>
                        </div>
                        <div>
                            <b>Date: </b>
                            <span>${receipt.date}</span>
                        </div>
                        <div>
                            <b>Invoice No. </b>
                            <span>${receipt.invoice_id}</span>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tbody}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>${total}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                `

                css += `
                    .table{width:100%;margin-bottom:1rem;color:#212529}.table td,.table th{padding:.75rem;vertical-align:top;border-top:1px solid #dee2e6}.table thead th{vertical-align:bottom;border-bottom:2px solid #dee2e6}.table tbody+tbody{border-top:2px solid #dee2e6}.table-sm td,.table-sm th{padding:.3rem}.table-bordered{border:1px solid #dee2e6}.table-bordered td,.table-bordered th{border:1px solid #dee2e6}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}.table-borderless tbody+tbody,.table-borderless td,.table-borderless th,.table-borderless thead th{border:0}.table-striped tbody tr:nth-of-type(odd){background-color:rgba(0,0,0,.05)}.table-hover tbody tr:hover{color:#212529;background-color:rgba(0,0,0,.075)}.table-primary,.table-primary>td,.table-primary>th{background-color:#b8daff}.table-primary tbody+tbody,.table-primary td,.table-primary th,.table-primary thead th{border-color:#7abaff}.table-hover .table-primary:hover{background-color:#9fcdff}.table-hover .table-primary:hover>td,.table-hover .table-primary:hover>th{background-color:#9fcdff}.table-secondary,.table-secondary>td,.table-secondary>th{background-color:#d6d8db}.table-secondary tbody+tbody,.table-secondary td,.table-secondary th,.table-secondary thead th{border-color:#b3b7bb}.table-hover .table-secondary:hover{background-color:#c8cbcf}.table-hover .table-secondary:hover>td,.table-hover .table-secondary:hover>th{background-color:#c8cbcf}.table-success,.table-success>td,.table-success>th{background-color:#c3e6cb}.table-success tbody+tbody,.table-success td,.table-success th,.table-success thead th{border-color:#8fd19e}.table-hover .table-success:hover{background-color:#b1dfbb}.table-hover .table-success:hover>td,.table-hover .table-success:hover>th{background-color:#b1dfbb}.table-info,.table-info>td,.table-info>th{background-color:#bee5eb}.table-info tbody+tbody,.table-info td,.table-info th,.table-info thead th{border-color:#86cfda}.table-hover .table-info:hover{background-color:#abdde5}.table-hover .table-info:hover>td,.table-hover .table-info:hover>th{background-color:#abdde5}.table-warning,.table-warning>td,.table-warning>th{background-color:#ffeeba}.table-warning tbody+tbody,.table-warning td,.table-warning th,.table-warning thead th{border-color:#ffdf7e}.table-hover .table-warning:hover{background-color:#ffe8a1}.table-hover .table-warning:hover>td,.table-hover .table-warning:hover>th{background-color:#ffe8a1}.table-danger,.table-danger>td,.table-danger>th{background-color:#f5c6cb}.table-danger tbody+tbody,.table-danger td,.table-danger th,.table-danger thead th{border-color:#ed969e}.table-hover .table-danger:hover{background-color:#f1b0b7}.table-hover .table-danger:hover>td,.table-hover .table-danger:hover>th{background-color:#f1b0b7}.table-light,.table-light>td,.table-light>th{background-color:#fdfdfe}.table-light tbody+tbody,.table-light td,.table-light th,.table-light thead th{border-color:#fbfcfc}.table-hover .table-light:hover{background-color:#ececf6}.table-hover .table-light:hover>td,.table-hover .table-light:hover>th{background-color:#ececf6}.table-dark,.table-dark>td,.table-dark>th{background-color:#c6c8ca}.table-dark tbody+tbody,.table-dark td,.table-dark th,.table-dark thead th{border-color:#95999c}.table-hover .table-dark:hover{background-color:#b9bbbe}.table-hover .table-dark:hover>td,.table-hover .table-dark:hover>th{background-color:#b9bbbe}.table-active,.table-active>td,.table-active>th{background-color:rgba(0,0,0,.075)}.table-hover .table-active:hover{background-color:rgba(0,0,0,.075)}.table-hover .table-active:hover>td,.table-hover .table-active:hover>th{background-color:rgba(0,0,0,.075)}.table .thead-dark th{color:#fff;background-color:#343a40;border-color:#454d55}.table .thead-light th{color:#495057;background-color:#e9ecef;border-color:#dee2e6}.table-dark{color:#fff;background-color:#343a40}.table-dark td,.table-dark th,.table-dark thead th{border-color:#454d55}.table-dark.table-bordered{border:0}.table-dark.table-striped tbody tr:nth-of-type(odd){background-color:rgba(255,255,255,.05)}.table-dark.table-hover tbody tr:hover{color:#fff;background-color:rgba(255,255,255,.075)}@media (max-width:575.98px){.table-responsive-sm{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-sm>.table-bordered{border:0}}@media (max-width:767.98px){.table-responsive-md{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-md>.table-bordered{border:0}}@media (max-width:991.98px){.table-responsive-lg{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-lg>.table-bordered{border:0}}@media (max-width:1199.98px){.table-responsive-xl{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-xl>.table-bordered{border:0}}.table-responsive{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive>.table-bordered{border:0}.table-responsive{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive>.table-bordered{border:0}


                    body {
                        padding: 1rem;
                        border: 10px double;
                        margin: 0;
                    }
                    .head {
                        display: flex;
                        justify-content: space-between;
                        border-bottom: 2px solid;
                        padding: 1rem;
                    }
                `
                
                this._print(content, css)
            },
            show(id) {
                axios.get("/api/receipt/" + id).then(resp => {
                    this.receipt = resp.data
                })
                this.is_edit = false
                $("#printModal").modal()
            },
            edit(id) {
                this.is_edit = true

                axios.get("/api/receipt/" + id).then(resp => {
                    this.receipt.customer_id = resp.data[0].customer_id
                    this.receipt.date = resp.data[0].date
                    this.receipt.details = resp.data[0].details
                    this.receipt.id = id
                    this.actions.edit = true;
                    this.actions.create = false;

                })
                $('#receiptModal').modal('show');
            },

            edited() {
                axios.put("/api/receipt/" + this.receipt.id, this.receipt)
            },

            getReceipts() {
                axios.get(`/api/customer/${this.$route.params.id}/receipts`).then(r => this.receipts = r.data);
            },
            getCustomers() {
                axios.get('/api/customer-list').then(r => this.customers = r.data)
            },
            addRow() {
                this.receipt.details.push({
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
                        'quantity': 0,
                        'note': ''
                    }]
                }
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

                this.receipt.customer_id = this.$route.params.id
            },
            getReceiptNum() {
                axios.get("/api/receipt/new-invoice-no").then(r => this.last_receipt_num = r.data)
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
    }
</script>