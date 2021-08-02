<template>

    <div class="showReceipt">
        
        <div class="row customer-details">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Customer Name</label>
                    <span class="form-control">
                        {{receipt.id}}
                    </span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Date</label>
                    <span type="date" class="form-control">{{receipt.date}}</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Receipt Num:</label>
                    <span type="number" class="form-control">{{last_receipt_num}}</span>
                </div>
            </div>
            
        </div>
        <div class="col-sm-3" style="display:grid; place-items:center">
                <button class="btn btn-primary" @click="openPrinter()">Print</button>
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
</template>

<script>
export default {
    props: [
        'heads',
        'bodys'
    ],
    data() {
        return {
            last_receipt_num: 1,
            total: 0,
            receipt: {
                id: '1',
                customer_id: '1',

                date: moment(new Date).format('YYYY-MM-DD'),
                details: [{
                    'name': 'ball',
                    'price': '10',
                    'quantity': 1,
                    'note': 'this is a note'
                }]
            }
        }
    },
    setup() {
        name: 'Show'
    },

    methods: {
        openPrinter() {
            let w = window.open("", "Test", "width=600,height=600,scrollbars=1,resizable=1")
            w.document.write('<html> <head></head> <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" > <body>')
            w.document.write('<table border="1" cell-border="1">')
            w.document.write(document.querySelector('.showReceipt .customer-details').innerHTML)
            w.document.write(document.querySelector('.showReceipt thead').innerHTML)
            w.document.write(document.querySelector('.showReceipt tbody').innerHTML)
            w.document.write(document.querySelector('.showReceipt tfoot').innerHTML)
            w.document.write('</table>')
            
            w.document.write('</body> </html>')
            w.document.close()

            setTimeout(function () {

                w.focus()
                w.print()
            }, 100)
            w.onafterprint = function () {
                w.close()
            }
        }
    }
}
</script>

<style scoped>
</style>