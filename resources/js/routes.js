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
        component: require('./components/Customer/Index').default
    },
    {
        path: '/',
        component: require('./components/Customer/Index').default
    },
    {
        path: '/customer/:id/contracts',
        component: require('./components/Customer/Contracts').default
    },
    {
        path: '/customer/:id/receipts',
        component: require('./components/Customer/Receipts').default
    },
    
    {
        path: '/reports',
        component: require('./components/Reports/Index').default
    },
]