import { createRouter, createWebHistory } from 'vue-router';
import ProjectDetail from '../pages/ProjectDetail.vue';
import ProjectsList from '../pages/ProjectsListTasks.vue';

const routes = [
    {
        path: '/',
        name: 'projects.index',
        component: ProjectsList,
        meta: { title: 'Projects' }
    },
    {
        path: '/projects/:id',
        name: 'projects.show',
        component: ProjectDetail,
        meta: { title: 'Projects Tasks' },
        props: true                   // Passa params como props para o componente
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Muda o título da página ao navegar
router.afterEach((to) => {
    document.title = `${to.meta.title || 'Task Manager'} - Task Manager`;
});

export default router;