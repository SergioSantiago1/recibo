<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

// Função para converter valor por extenso
function valorPorExtenso($valor = 0, $maiusculas = false) {
    $valor = number_format($valor, 2, '.', '');
    list($reais, $centavos) = explode('.', $valor);

    setlocale(LC_ALL, 'pt_BR.UTF-8');
    $fmt = new \NumberFormatter('pt_BR', \NumberFormatter::SPELLOUT);

    $reaisTexto = $fmt->format($reais);
    $centavosTexto = $fmt->format($centavos);

    $extenso = $reaisTexto . ' ' . ($reais == 1 ? 'real' : 'reais');

    if ($centavos > 0) {
        $extenso .= ' e ' . $centavosTexto . ' ' . ($centavos == 1 ? 'centavo' : 'centavos');
    }

    return $maiusculas ? mb_strtoupper($extenso, 'UTF-8') : $extenso;
}

function limparValorMonetario($valor) {
    $valor = str_replace(['R$', ' '], '', $valor);
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);
    return floatval($valor);
}

function mesPorExtenso($data) {
    $meses = [
        '01' => 'janeiro',
        '02' => 'fevereiro',
        '03' => 'março',
        '04' => 'abril',
        '05' => 'maio',
        '06' => 'junho',
        '07' => 'julho',
        '08' => 'agosto',
        '09' => 'setembro',
        '10' => 'outubro',
        '11' => 'novembro',
        '12' => 'dezembro'
    ];

    $partes = explode('/', $data); // formato: dd/mm/yyyy
    $dia = $partes[0];
    $mes = $meses[$partes[1]];
    $ano = $partes[2];

    return "{$dia} de {$mes} de {$ano}";
}

// Recebe os dados do formulário
$nomeDono = 'Braz Mendes Ferreira 12029265845';
$cnpjDono = '37.156.851/0001-38';
$nome = $_POST['nome'];
$servico = $_POST['servico'];
$cpfRg = $_POST['CPF/RG'];
$valor = $_POST['valor'];
$data = $_POST['data'];

// Converte valor para número
$valorLimpo = limparValorMonetario($valor);

// Formata a data para o padrão brasileiro
$dataFormatada = date('d/m/Y', strtotime($data));

// Conteúdo HTML do recibo
$html = "
    <h2><center>BRAZ MENDES FERREIRA 12029265845</center></h2>
    <h2><center>RECIBO DE PAGAMENTO</center></h2>
    <br><br>
    <p>
        Eu, <strong>{$nome}</strong>, recebi de Braz Mendes Ferreira 12029265845 
        a importância de <strong>{$valor}</strong>
        <strong>(" . valorPorExtenso($valorLimpo, true) . ")</strong>
        pelos serviços de: <strong>{$servico}</strong>, prestados por acordo de freelancer no dia 
        {$dataFormatada}.
    </p>
    <br><br>
    <p>SUMARÉ (SP), " . mesPorExtenso($dataFormatada) ."</p>
    <br><br>
    <p>_____________________________________<br>{$nome}<br>CPF/RG: {$cpfRg}</p>
    <br><br>
    <p>_____________________________________<br>{$nomeDono}<br>CNPJ: {$cnpjDono}</p>
";

// Gera o PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("recibo_{$nome}.pdf", ["Attachment" => false]);
?>