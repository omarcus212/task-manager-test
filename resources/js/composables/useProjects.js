import { ref } from 'vue';
import { useApi } from './useApi';


export function useProjects() {
    const { get, post, patch, loading, error, success } = useApi();

    const projects = ref([]);
    const currentProject = ref(null);
    const meta = ref(null);

    // Função para buscar a lista de projetos, aceita filtros (nome, status, etc)
    const fetchProjects = async (filters = {}) => {

        const response = await get('/projects', filters);
        // Atualiza a lista de projetos com os dados recebidos (ou vazio caso não venha nada)
        projects.value = response.data || [];
        meta.value = response.meta || null;

    };

    // Função para buscar os detalhes de um projeto específico pelo seu ID
    const fetchProject = async (projectId) => {
        // Faz a requisição GET para a rota detalhada do projeto
        const response = await get(`/projects/${projectId}`);
        currentProject.value = response.data || null;
        return response.data;
    };

    // Função para criar um novo projeto
    const createProject = async (payload) => {

        const response = await post('/projects', payload);
        const project = response.data;
        projects.value.unshift(project);

        return project;
    };

    // Função para atualizar um projeto existente
    const updateProject = async (projectId, payload) => {

        const response = await patch(`/projects/${projectId}`, payload);
        const updated = response.data;

        // Localiza o projeto na lista local pelo ID e o substitui pelos dados atualizados
        const index = projects.value.findIndex(p => p.id === projectId);
        if (index !== -1) {
            projects.value[index] = updated;
        }

        // Se o projeto atualizado for o que está sendo visualizado em detalhes, atualiza-o também
        if (currentProject.value && currentProject.value.id === projectId) {
            currentProject.value = updated;
        }

        return updated;
    };

    return {
        projects,
        currentProject,
        meta,
        loading,
        error,
        success,
        fetchProjects,
        fetchProject,
        createProject,
        updateProject,
    };
}