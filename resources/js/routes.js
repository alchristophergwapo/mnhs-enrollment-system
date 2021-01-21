import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

//Components for routes
import DashboardPage from "./components/DashboardPage.vue";
import AllStudents from "./components/AllStudents.vue";
import AllTeachers from "./components/AllTeachers.vue";
import AllSections from "./components/AllSections.vue";
import Enrollment from "./components/Enrollment.vue";

const routes = [
  { path: '/dashboard', component: DashboardPage },
  { path: '/enrollment', component: Enrollment },
  { path: '/all_students', component: AllStudents },
  { path: '/all_teachers', component: AllTeachers },
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