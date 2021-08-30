<template>
    <div>
        <div class="row mt-5">
            <div class="col-3 text-center">
                <Card title="Customers" :numbers="customers_num" iconname="far fa-user" bgcolor="#dc3545"></Card>
            </div>
            <div class="col-3 text-center">
                <Card title="Contracts" :numbers="contracts_num" iconname="far fa-file-alt" bgcolor="#0d6efd"></Card>
            </div>
            <div class="col-3 text-center">
                <Card title="Receipts" :numbers="receipts_num" iconname="fas fa-receipt" bgcolor="#ffc107"></Card>
            </div>
            <div class="col-3 text-center">
                <Card title="Monthly Profit" :numbers="monthly_profit" iconname="fas fa-dollar-sign" bgcolor="#198754"></Card>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-3 chart text-center ">
                <h2 class="mb-3 mt-3">Monthly Income</h2>
                <monthly-income :data="profit"></monthly-income>
            </div>
            <div class="col-3 chart text-center ">
                <h2 class="mb-3 mt-3">Monthly Expenses</h2>
                <monthly-expenses :data="expense"></monthly-expenses>
            </div>
        </div>

    </div>
</template>
<script>
import MonthlyExpenses from './charts/MonthlyExpenses.vue'
import MonthlyIncome from './charts/MonthlyIncome.vue'
import { mapActions } from 'vuex'
import Card from "./ReportCard"
export default {
    components: {
        Card,
        MonthlyIncome,
        MonthlyExpenses
    },
    setup() {

    },
    data() {
        return {
            customers_num: 0,
            contracts_num: 0,
            receipts_num: 0,
            monthly_profit:0,

            profit: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                        label: 'Yearly Profit',
                        backgroundColor: '#52b788',
                        data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
                    },
                ]
            },
            expense: {
                labels: ['Server', 'Domain', 'Electricty'],
                datasets: [
                    {

                        backgroundColor: ['#ffc107', '#0d6efd', '#198754'],
                        data: [40000, 30000, 25000]
                    },

                ]
            },


        }
    },
    methods: {
        ...mapActions(['getCustomers', 'getContracts', 'getReceipts']),
        async getProfit(){
            try{
                let resp = await axios.get('/api/reports/monthly-profit')
                this.monthly_profit =  resp.data
            }
            catch(e){
                toaster.error(e)
            }
        }
    },
    mounted() {
        this.getCustomers().then(r => this.customers_num = r.data.length)
        this.getReceipts().then(r => this.receipts_num = r.data.data.length)
        this.getContracts().then(r => this.contracts_num = r.data.data.length)
        this.getProfit()
        // this.getContracts().then(r => console.log(r.data))
    },
    computed: {

    }
}


</script>

<style scoped>
.chart {
    margin: 15px;
    background: rgb(245, 245, 245);
    padding: 10px 20px 10px 20px;
    border-radius: 5px;
}
</style>
