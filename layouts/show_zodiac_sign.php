<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['data_nascimento'])) {
    $data_nascimento = $_POST['data_nascimento'];

    // Converte a data para um objeto DateTime
    $data = DateTime::createFromFormat('Y-m-d', $data_nascimento);

    if ($data) {
        $dia = $data->format('d');
        $mes = $data->format('m');

        // Carrega o arquivo XML
        $xml = simplexml_load_file('signos.xml');

        // Inicializa a variável do signo encontrado
        $signo_encontrado = null;

        // Percorre os signos no XML
        foreach ($xml->signo as $signo) {
            // Converte as datas do XML
            [$diaInicio, $mesInicio] = explode('/', (string) $signo->dataInicio);
            [$diaFim, $mesFim] = explode('/', (string) $signo->dataFim);

            // Cria objetos DateTime para comparação
            $dataInicio = DateTime::createFromFormat('m-d', "$mesInicio-$diaInicio");
            $dataFim = DateTime::createFromFormat('m-d', "$mesFim-$diaFim");
            $dataAtual = DateTime::createFromFormat('m-d', "$mes-$dia");

            // Verifica se a data está no intervalo do signo
            if ($dataAtual >= $dataInicio && $dataAtual <= $dataFim) {
                $signo_encontrado = (object) [
                    'signoNome' => (string) $signo->signoNome,
                    'descricao' => (string) $signo->descricao
                ];
                break;
            }
        }
    }
} else {
    $signo_encontrado = null;
}

?>

<?php include('header.php'); ?>
<body>
    <div class="container-fluid main-container mt-4 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="content-wrapper text-center p-5" style="background-color: white; border-radius: 15px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); max-width: 500px;">
            <?php if ($signo_encontrado): ?>
                <h1 class="text-primary mb-4">Seu signo é: <?= $signo_encontrado->signoNome ?></h1>
                <p class="text-muted mb-5"><?= $signo_encontrado->descricao ?></p>
            <?php else: ?>
                <p class="text-danger mb-5">Data inválida! Não foi possível encontrar um signo correspondente.</p>
            <?php endif; ?>
            <a href='../index.php' class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</body>

