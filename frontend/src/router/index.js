import { createRouter, createWebHistory } from 'vue-router'
import Cookies from 'universal-cookie'
import { useUserStore } from '../stores/user'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),


  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
      meta: {
        middleware: "guest",
        class: "no-header red-bg"
      }
    },
    {
      path:'/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: {
        middleware: "guest",
        class: "red-bg"
      }
    },
    {
      path: '/logout',
      name: 'logout',
      component: {
        beforeRouteEnter(to, from, next) {
          
          const cookies = new Cookies()
          const userStore = useUserStore();


          fetch(import.meta.env.VITE_AUTH_URL+'logout', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                // Add the CSRF token in the request headers
                'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
            },
          })
          .then(response => {
            // Remove the CSRF token cookie
            cookies.remove('XSRF-TOKEN')

            // Remove the user from user store
            userStore.logout()

            // Redirect to login
            next({ name: 'login' })
          }
          )
          .catch(error => console.log(error))
        }
      },
      meta: {
        middleware: "auth",
      },
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: {
        middleware: "guest",
        class: "red-bg"
      }
    },
    {
      path: '/my-list',
      name: 'my-list',
      component: () => import('../views/ListView.vue'),
      meta: {
        middleware: "auth"
      }
    }
  ]
})

// Handle auth for each route
router.beforeEach((to, from, next) => { 

  const userStore = useUserStore();

  // Check if the route has a middleware auth and if the user is logged in
  if (to.meta.middleware === 'auth' && !userStore.isLoggedIn) {
    next({ name: 'login' })
    return
  }
  // Check if the route has a middleware guest and if the user is logged in
  if (to.meta.middleware === 'guest' && userStore.isLoggedIn) {
    next({ name: 'my-list' })
    return
  }

  next()
  return

})

export default router
