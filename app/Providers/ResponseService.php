<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseService extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Resposta de Sucesso
         * Uso: Response::success($data, $message, $code)
         */
        Response::macro('success', function ($data = [], $message = 'Operação realizada com sucesso', $code = 200) {

            // Converte para resposta temporária para extrair os dados
            $tempResponse = $data->toResponse(request());
            $originalContent = json_decode($tempResponse->getContent(), true);

            // ✅ Extrai data, meta e links para o nível correto
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $originalContent['data'] ?? [],
                'meta' => $originalContent['meta'] ?? null,
                'links' => $originalContent['links'] ?? null,
            ], $code);


        });

        /**
         * Resposta de Erro
         * Uso: Response::error($message, $code)
         */
        Response::macro('error', function ($message = 'Operation error', $code = 400) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], $code);
        });

    }
}
