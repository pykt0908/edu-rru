import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, _from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else if (to.hash) {
      return { el: to.hash, behavior: 'smooth' }
    } else {
      return { top: 0, left: 0 }
    }
  },
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      redirect: '/about/history',
    },
    {
      path: '/about/history',
      name: 'faculty-history',
      component: () => import('../views/FacultyHistoryView.vue'),
    },
    {
      path: '/about/management',
      name: 'faculty-management',
      component: () => import('../views/FacultyManagementView.vue'),
    },
    {
      path: '/about/committee',
      name: 'faculty-committee',
      component: () => import('../views/FacultyCommitteeView.vue'),
    },
    {
      path: '/about/ita',
      name: 'faculty-ita',
      component: () => import('../views/FacultyItaView.vue'),
    },
    {
      path: '/about/philosophy',
      name: 'faculty-philosophy',
      component: () => import('../views/FacultyPhilosophyView.vue'),
    },
    {
      path: '/about/regulations',
      name: 'faculty-regulations',
      component: () => import('../views/FacultyRegulationsView.vue'),
    },
    {
      path: '/about/personnel',
      name: 'faculty-personnel',
      component: () => import('../views/FacultyPersonnelView.vue'),
    },
    {
      path: '/about/personnel/:id',
      name: 'faculty-personnel-detail',
      component: () => import('../views/FacultyPersonnelDetailView.vue'),
    },
    {
      path: '/personnel',
      redirect: '/about/personnel',
    },
    {
      path: '/personnel/:id',
      redirect: (to) => `/about/personnel/${to.params.id}`,
    },
    {
      path: '/curriculum',
      redirect: '/curriculum/bachelor',
    },
    {
      path: '/curriculum/bachelor',
      name: 'curriculum-bachelor',
      component: () => import('../views/CurriculumListView.vue'),
      props: { defaultDegree: 'bachelor' },
    },
    {
      path: '/curriculum/grad-diploma',
      name: 'curriculum-grad-diploma',
      component: () => import('../views/CurriculumListView.vue'),
      props: { defaultDegree: 'grad-diploma' },
    },
    {
      path: '/curriculum/master',
      name: 'curriculum-master',
      component: () => import('../views/CurriculumListView.vue'),
      props: { defaultDegree: 'master' },
    },
    {
      path: '/curriculum/detail',
      name: 'curriculum-detail',
      component: () => import('../views/CurriculumDetailView.vue'),
    },
    {
      path: '/sdgs',
      name: 'sdgs',
      component: () => import('../views/SdgsView.vue'),
    },
    {
      path: '/sdgs/post/:id',
      name: 'sdg-post-detail',
      component: () => import('../views/SdgPostDetailView.vue'),
    },
    {
      path: '/sdgs/activity/:id',
      redirect: (to) => `/sdgs/post/${to.params.id}`,
    },
    {
      path: '/posts/:id',
      name: 'post-detail',
      component: () => import('../views/PostDetailView.vue'),
    },
    {
      path: '/news/:id',
      redirect: (to) => `/posts/${to.params.id}`,
    },
    {
      path: '/student-services',
      name: 'student-services',
      component: () => import('../views/StudentServicesView.vue'),
    },
    {
      path: '/planning',
      name: 'planning',
      component: () => import('../views/PlanningView.vue'),
    },
    {
      path: '/student-activities',
      name: 'student-activities',
      component: () => import('../views/StudentActivitiesView.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('../views/ContactView.vue'),
    },
    // Backoffice / Admin Panel Routes
    {
      path: '/admin',
      component: () => import('../layouts/AdminLayout.vue'),
      children: [
        {
          path: '',
          name: 'admin-dashboard',
          component: () => import('../views/admin/AdminDashboardView.vue'),
        },
        {
          path: 'posts',
          name: 'admin-posts',
          component: () => import('../views/admin/AdminPostsView.vue'),
        },
        {
          path: 'posts/create',
          name: 'admin-post-create',
          component: () => import('../views/admin/AdminPostCreateView.vue'),
        },
        {
          path: 'posts/:id/edit',
          name: 'admin-post-edit',
          component: () => import('../views/admin/AdminPostEditView.vue'),
        },
        {
          path: 'personnel',
          name: 'admin-personnel',
          component: () => import('../views/admin/AdminPersonnelView.vue'),
        },
        {
          path: 'content',
          name: 'admin-content',
          component: () => import('../views/admin/AdminContentView.vue'),
        },
      ],
    },
  ],
})

router.afterEach((to, from) => {
  if (to.path !== from.path || !to.hash) {
    if (typeof window !== 'undefined') {
      window.scrollTo({ top: 0, left: 0, behavior: 'instant' })
      document.documentElement.scrollTop = 0
      document.body.scrollTop = 0
    }
  }
})

export default router
