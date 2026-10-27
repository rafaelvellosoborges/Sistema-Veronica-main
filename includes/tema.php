<?php

/**
 * Lê config_loja e imprime as 4 variáveis CSS de cor do tema ativo, dentro
 * de um <style>. Os temas claro/escuro têm paleta fixa (ignoram as cores
 * salvas no banco) — só o personalizado usa as 4 cores escolhidas pelo
 * lojista. Cores derivadas (bordas, hover, etc) ficam em assets/css/loja.css,
 * calculadas via color-mix() a partir dessas 4 — nunca aqui.
 */
function imprimirVariaveisTema(PDO $pdo): void
{
    $config = $pdo->query('SELECT tema, cor_primaria, cor_secundaria, cor_fundo, cor_texto FROM config_loja WHERE id_config = 1')->fetch();
    $tema = $config['tema'] ?? 'claro';

    $paletasFixas = [
        // Paleta da vitrine: fundo azul-quase-preto e roxo elétrico, inspirada
        // na referência visual escolhida para a loja.
        'claro' => [
            'primaria' => '#8B7CFF',
            'secundaria' => '#6256D8',
            'fundo' => '#070B14',
            'texto' => '#F7F8FF',
        ],
        'escuro' => [
            'primaria' => '#9A8CFF',
            'secundaria' => '#6D5DF5',
            'fundo' => '#070B14',
            'texto' => '#F7F8FF',
        ],
    ];

    if ($tema === 'personalizado') {
        $cores = [
            'primaria' => $config['cor_primaria'],
            'secundaria' => $config['cor_secundaria'],
            'fundo' => $config['cor_fundo'],
            'texto' => $config['cor_texto'],
        ];
    } else {
        $cores = $paletasFixas[$tema] ?? $paletasFixas['claro'];
    }
    ?>
    <style>
        :root {
            --cor-primaria: <?= htmlspecialchars($cores['primaria']) ?>;
            --cor-secundaria: <?= htmlspecialchars($cores['secundaria']) ?>;
            --cor-fundo: <?= htmlspecialchars($cores['fundo']) ?>;
            --cor-texto: <?= htmlspecialchars($cores['texto']) ?>;
        }
    </style>
    <?php
}
