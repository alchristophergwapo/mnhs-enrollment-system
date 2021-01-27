import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

//Components for routes
import DashboardPage from "./components/DashboardPage.vue";
import AllStudents from "./components/AllStudents.vue";
import AllTeachers from "./components/AllTeachers.vue";
import AllSections from "./components/AllSections.vue";
import Enrollment from "./components/Enrollment.vue";
import SignIn from "./components/SignIn.vue";

const routes = [
  {
    path: '/admin',
    component: DashboardPage
  },


  {
    path: '/admin/enrollment',
    name: 'Enrollment',
    component: Enrollment,
    meta: {
      title: 'Login'
    }
  },
  {
    path: '/admin/all_students', 
    component: AllStudents
  },
  {
    path: '/admin/all_teachers', 
    component: AllTeachers
  },
  {
    path: '/admin/all_sections',
    component: AllSections
  },
  {
    path: '/admin/sign-up',
    component: SignIn
  }
  

]

const router = new VueRouter({
  mode: 'history',
  routes: routes,
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  next();
})

export default router;