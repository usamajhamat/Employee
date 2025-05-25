import AuthLayout from '@/layouts/AuthLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import Login from '../../pages/auth/Login.vue'

export default [
    {
        path: '',
        component: AuthLayout,
        children: [
            {
                path: '',
                name: 'Login',
                component: Login,
                meta: { requiresAuth: false },
            },
        ]
    }
]
