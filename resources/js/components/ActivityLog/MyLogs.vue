<template>
    <div class="card card-custom shadow-sm">
        <div class="card-header min-h-50px">
            <div class="card-title">
                <div class="card-label">Activity Log</div>
            </div>
            <div class="card-toolbar" style="gap: 1rem;">
                <button class="btn btn-light-primary" @click="initCard"><i class="fad fa-up-to-line"></i> Collapse All</button>
                <button class="btn btn-light-primary" @click="expandAll"><i class="fad fa-down-to-line"></i> Expand All</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <pagination :data="list" @pagination-change-page="get" show-disabled size="large" align="right" :limit="5">
                        <span slot="prev-nav"><i class="fad fa-arrow-left font-size-base"></i></span>
                        <span slot="next-nav"><i class="fad fa-arrow-right font-size-base"></i></span>
                    </pagination>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-vertical-center">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">User</th>
                                <th style="width: 7%;">Type</th>
                                <th style="width: 9%;">Section</th>
                                <th style="width: 9%;">Subject ID</th>
                                <th style="width: 32%;">Data Change</th>
                                <th style="width: 14%;">Batch UUID</th>
                                <th style="width: 7%;">Date</th>
                                <th style="width: 7%;">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in listData" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.causer.name }}</td>
                                <td>
                                    <span class="label label-rounded font-weight-bolder label-lg label-inline" :class="[item.description == 'created' ? 'label-light-success' : (item.description == 
                                'updated' ? 'label-light-primary' : 'label-light-danger')]">{{ item.description }}</span>
                                </td>
                                <td>{{ item.log_name }}</td>
                                <td>{{ item.subject_id }}</td>

                                <td>
                                    <div class="card card-custom card-collapsed card-activity-log" data-card="true" :id="'card-activity-log-'+item.id">
                                        <div class="card-header min-h-30px border-0 p-0">
                                            <div class="card-toolbar">
                                                <a href="#" class="btn btn-sm btn-light-primary" data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title="Expand/Collapse">
                                                    Show <i class="far fa-angle-down"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table mb-0 table-primary" v-if="item.event === 'updated' && item.properties.attributes">
                                                <thead>
                                                    <tr>
                                                        <th>From</th>
                                                        <th>To</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item2, index2) in Object.keys(item.properties.attributes)" :key="index2">
                                                        <td v-if="item.properties.old"><strong>{{ item2 }}:</strong> {{ item.properties.old[item2] }}</td>
                                                        <td><strong>{{ item2 }}:</strong> {{ item.properties.attributes[item2] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table class="table mb-0 table-success" v-if="item.event === 'created' && item.properties.attributes">
                                                <tbody>
                                                    <tr v-for="(item2, index2) in Object.keys(item.properties.attributes)" :key="index2">
                                                        <td><strong>{{ item2 }}:</strong> {{ item.properties.attributes[item2] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table class="table mb-0 table-danger" v-if="item.event === 'deleted' && item.properties.old">
                                                <tbody>
                                                    <tr v-for="(item2, index2) in Object.keys(item.properties.old)" :key="index2">
                                                        <td><strong>{{ item2 }}:</strong> {{ item.properties.old[item2] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ item.batch_uuid }}</td>
                                <td>{{ formatDate(item.created_at) }}</td>
                                <td>{{ formatTimes(item.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                list: {},
                listData: [],
            }
        },
        methods: {
            get(page = 1) {
                axios.get('/api/activity-log/my-log?page=' + page).then(response => {
                    this.list = response.data
                    this.listData = response.data.data.map(item => {
                        item.properties = this.freezeReactivity(item.properties)

                        return item
                    })

                    setTimeout(() => this.initCard(), 1000)
                })
            },
            expandAll() {
                document.querySelectorAll('.card-activity-log').forEach(el => new KTCard(el.getAttribute('id')).expand())
            },
            initCard() {
                document.querySelectorAll('.card-activity-log').forEach(el => new KTCard(el.getAttribute('id')).collapse())
            },
        },
        mounted() {
            this.get()
        }
    }
</script>