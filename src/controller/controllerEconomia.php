<?php

require_once __DIR__ . '/../service/awesomeApi/servicoEconomia.php';

use src\service\awesomeApi\ServicoEconomia;

/**
 * Classe responsável por realizar o processamento dos dados referente a conversão dos valores.
 */
class ControllerEconomia {

    public function __construct() {
        $this->processaDados();
    }

    /**
     * Processa os dados vindos da interface e retorna uma resposta ao front-end, seja ela de sucesso ou erro.
     */
    public function processaDados() {
        try {
            $aDado = $this->getDadosInterface();
            $this->validaDadosInterface($aDado);
            $sValorConvertido = $this->realizaConversaoMoeda($aDado);
            $sValorConvertidoFormatado  = $this->formataValor($sValorConvertido); 
    
            header('Content-Type: application/json');
            echo json_encode(['valorConvertido' => $_POST['paraMoeda2'].': '.$sValorConvertidoFormatado]);
        } catch (Exception $e) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Realiza a conversão da moeda.
     * 
     * @return string $aDado Valor convertido para a moeda desejada.
     */
    public function realizaConversaoMoeda($aDado) {
        $aDadoComunicacao = $this->getServicoEconomia()->consultarCotacao($aDado['deMoeda1'], $aDado['paraMoeda2']);

        return $this->calculaConversao($aDado['valor'], $aDadoComunicacao['bid']);
    }

    /**
     * Retorna o valor passado como parâmetro com "máscara".
     * 
     * @return string $aDado Valor formatado.
     */
    protected function formataValor($sValor) {
        return number_format($sValor, 2, ',', '.'); 
    }

    /**
     * Realiza o cálculo de conversão.
     * 
     * @param string $sValor Valor a ser convertido.
     * @param string $sTaxaCambio Taxa de cambio/cotação utilizada para realizar a conversão.
     * @return string $aDado Valor convertido.
     */
    protected function calculaConversao($sValor, $sTaxaCambio) {
        // Remove o separador de milhar e substitui a vírgula decimal por ponto
        $iValor = str_replace(['.', ','], ['', '.'], $sValor);

        return bcmul($sTaxaCambio, $iValor, 4);
    }
    
    /**
     * Retorna os dados que o usuário informou na interface.
     * 
     * @return array $aDado Dados vindos da interface.
     */
    protected function getDadosInterface() {
        $aDado = [];
        if (isset($_POST['deMoeda1'], $_POST['paraMoeda2'], $_POST['valor'])) {

            $aDado['deMoeda1']   = $_POST['deMoeda1'];
            $aDado['paraMoeda2'] = $_POST['paraMoeda2'];
            $aDado['valor']      = $_POST['valor'];
        }
        return $aDado;
    }

    /**
     * Aplica as validações sobre os dados vindos da interface apresentada ao usuário.
     * 
     * @param array $aDado Dados vindos da interface.
     */
    protected function validaDadosInterface($aDado) {
        $this->validaExisteDuasMoedasSelecionadas($aDado);
        $this->validaIsMoedasIguais($aDado);
        $this->validaDadosMoeda($aDado);
        $this->validaDadosValor($aDado);
    }

    /**
     * Verifica se as moedas recebidas estão entre as fornecidas pela api.
     * 
     * @param array $aDado Dados vindos da interface.
     */
    private function validaDadosMoeda($aDado) {
        $moedasValidas = $this->getServicoEconomia()->consultarMoedas();

        $moedasSelecionadas = [$aDado['deMoeda1'], $aDado['paraMoeda2']];
        $moedasInvalidas = array_filter($moedasSelecionadas, function($moeda) use ($moedasValidas) {
            return !array_key_exists($moeda, $moedasValidas);
        });

        if (!empty($moedasInvalidas)) {
            throw new Exception("Uma ou mais moedas selecionadas são inválidas.");
        }
        
    }

    /**
     * Verifica se existem duas moedas selecionadas.
     * 
     * @param array $aDado Dados vindos da interface.
     */
    private function validaExisteDuasMoedasSelecionadas($aDado) {
        if (empty($aDado['deMoeda1']) || empty($aDado['paraMoeda2'])) {
            throw new Exception("É necessário selecionar duas moedas para realizar a conversão!");
        }
    }

    /**
     * Verifica se as moedas selecionadas para conversão não são as mesmas.
     * 
     * @param array $aDado Dados vindos da interface.
     */
    private function validaIsMoedasIguais($aDado) {
        // Validar se as moedas são diferentes. 
        if ($aDado['deMoeda1'] === $aDado['paraMoeda2']) {
            throw new Exception("A moeda de origem não pode ser a mesma que a moeda de destino.");
        }
    }

    /**
     * Realiza as validações sobre o campo de valor.
     * 
     * @param array $aDado Dados vindos da interface.
     */
    private function validaDadosValor($aDado) {
        $valor = trim($aDado['valor']);
        
        // Converte o valor para um número float
        $valorFloat = floatval(str_replace(',', '.', str_replace('.', '', $valor))); 
    
        if ($valorFloat <= 0) {
            throw new Exception("O valor deve ser um número maior que zero.");
        }
    }

    /**
     * Retorna uma instancia da classe de ServicoEconomia
     * 
     * @return ServicoEconomia Retorna a classe.
     */
    private function getServicoEconomia() {
        return new ServicoEconomia();
    }
}

// Instancia o controlador
new ControllerEconomia();