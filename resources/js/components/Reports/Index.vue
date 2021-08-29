<template>
    <div>
        <div class="row mt-5">
            <div class="col-3 text-center">
                <Card title="New Customers" numbers="13" iconname="far fa-user" bgcolor="#dc3545"></Card>
            </div>
            <div class="col-3 text-center">
                <Card title="Monthly Contracts" numbers="23" iconname="far fa-file-alt" bgcolor="#0d6efd"></Card>
            </div>
            <div class="col-3 text-center">
                <Card title="Monthly Receipts" numbers="43" iconname="fas fa-receipt" bgcolor="#198754"></Card>
            </div>
            <div class="col-3 text-center">
                <Card title="Monthly Profit" numbers="43" iconname="fas fa-dollar-sign" bgcolor="#ffc107"></Card>
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

            profit: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [
                    {
                        label: 'Monthly Profit',
                        backgroundColor: '#52b788',
                        data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
                    },
                ]
            },
            expense: {
                labels: ['Server', 'Domain', 'Electricty'],
                datasets: [
                    {
                        
                        backgroundColor: ['#ffc107','#0d6efd','#198754'],
                        data: [40, 30, 20]
                    },

                ]
            },


        }
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
