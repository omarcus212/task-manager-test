/**
 * useApi - HTTP Functions for API
 * Usage: const { get, post, patch, delete: del } = useApi()
 */

import axios from 'axios';
import { ref } from 'vue';

const api = axios.create({
    baseURL: import.meta.env.API_URL || 'http://localhost:8000/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    timeout: 20000,
});

export function useApi() {

    const loading = ref(false);
    const error = ref(null);
    const success = ref(null);

    // Função genérica para requisições do tipo GET
    const get = async (endpoint, params = {}) => {
        loading.value = true;
        error.value = null;
        try {

            const response = await api.get(endpoint, { params });
            success.value = response.data.message;

            return response.data;

        } catch (err) {

            error.value = err.response?.data?.message || err.message;
            throw err;

        } finally {

            loading.value = false;

        }
    };

    // Função genérica para requisições do tipo POST
    const post = async (endpoint, payload) => {

        loading.value = true;
        error.value = null;

        try {

            const response = await api.post(endpoint, payload);
            success.value = response.data.message;

            return response.data;

        } catch (err) {

            error.value = err.response?.data?.message || err.message;
            throw err;

        } finally {

            loading.value = false;

        }
    };

    // Função genérica para requisições do tipo PATCH (atualização parcial)
    const patch = async (endpoint, payload) => {

        loading.value = true;
        error.value = null;

        try {

            const response = await api.patch(endpoint, payload);
            success.value = response.data.message;

            return response.data;

        } catch (err) {

            error.value = err.response?.data?.message || err.message;
            throw err;

        } finally {

            loading.value = false;

        }
    };

    // Função genérica para requisições do tipo DELETE (exclusão)
    const deleteRequest = async (endpoint) => {

        loading.value = true;
        error.value = null;

        try {

            const response = await api.delete(endpoint);
            success.value = response.data.message;

            return response.data;

        } catch (err) {

            error.value = err.response?.data?.message || err.message;
            throw err;

        } finally {

            loading.value = false;

        }
    };

    // Função utilitária para limpar as mensagens de erro e sucesso da tela
    const clearFeedback = () => {
        error.value = null;
        success.value = null;
    };

    return {
        loading,
        error,
        success,
        get,
        post,
        patch,
        delete: deleteRequest,
        clearFeedback,
    };
}