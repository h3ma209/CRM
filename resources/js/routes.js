export default [
    {
        path: '/receipts',
        component: require('./components/Receipts').default
    },
    {
        path: '/contracts',
        component: require('./components/Contracts').default
    },
    {
        path: '/customers',
        component: require('./components/Customers').default
    },
    {
        path: '/customers',
        component: require('./components/Customers').default
    },
    {
        path: '/customer/:id/contracts',
        component: require('./components/CustomerContracts').default
    },
    {
        path: '/customer/:id/receipts',
        component: require('./components/CustomerReceipts').default
    },
    {
        path: '/test',
        component: require('./components/test').default
    },
]