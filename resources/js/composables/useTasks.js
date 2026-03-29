import { ref, unref } from 'vue';
import { useApi } from './useApi';


export function useTasks(projectId = null) {

    const { get, post, patch, delete: deleteRequest, loading, error, success } = useApi();

    const tasks = ref([]);
    const meta = ref(null);

    // Função interna para determinar o endpoint. Se houver um projectId, usa a rota do projeto.
    const getBaseEndpoint = () => {
        const id = unref(projectId);
        return id ? `/projects/${id}/tasks` : '/tasks';
    };

    // Busca tarefas da API aplicando filtros (status, prioridade, etc)
    const fetchTasks = async (filters = {}) => {
        // Remove chaves que possuem valores nulos ou indefinidos do objeto de filtros
        const cleanFilters = Object.fromEntries(
            Object.entries(filters).filter(([_, v]) => v != null)
        );

        const response = await get(getBaseEndpoint(), cleanFilters);

        tasks.value = response.data || [];
        meta.value = response.meta || null;
    };

    // Função para criar uma nova tarefa
    const createTask = async (payload) => {

        const response = await post(getBaseEndpoint(), payload);
        const task = response.data;
        tasks.value.unshift(task);

        return task;
    };

    // Função padrão para atualizar uma tarefa via API
    const updateTask = async (taskId, payload) => {

        const response = await patch(`${getBaseEndpoint()}/${taskId}`, payload);
        const updated = response.data;

        // Atualiza o objeto correspondente dentro da lista reativa local
        const index = tasks.value.findIndex(t => t.id === taskId);
        if (index !== -1) {
            tasks.value[index] = updated;
        }

        return updated;
    };

    // Função para atualização "otimista": atualiza a UI antes de receber a resposta do servidor
    const updateTaskOptimistic = async (task, updates) => {
        const originalTask = { ...task }; // Guarda uma cópia do estado original caso a API falhe

        // Encontra a tarefa na lista e aplica as atualizações imediatamente
        const index = tasks.value.findIndex(t => t.id === task.id);
        if (index !== -1) {
            tasks.value[index] = { ...task, ...updates };
        }

        try {
            // Tenta realizar a atualização real na API
            const response = await patch(`${getBaseEndpoint()}/${task.id}`, updates);
            if (index !== -1) {
                // Sobrescreve com os dados oficiais vindos do servidor (ex: campos calculados)
                tasks.value[index] = response.data;
            }

            return response.data;

        } catch (err) {

            if (index !== -1) {
                tasks.value[index] = originalTask;
            }

            throw err;
        }
    };

    // Função para deletar uma tarefa
    const deleteTask = async (task) => {
        await deleteRequest(`${getBaseEndpoint()}/${task.id}`);
        tasks.value = tasks.value.filter(t => t.id !== task.id);
    };

    return {
        tasks,
        meta,
        loading,
        error,
        success,
        fetchTasks,
        createTask,
        updateTask,
        updateTaskOptimistic,
        deleteTask,
    };
}