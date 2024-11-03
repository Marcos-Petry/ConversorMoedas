<?php

namespace src\service\awesomeApi;

require_once __DIR__ . '/../../config/awesomeApi/enumEndPoints.php';

use Exception,
    src\config\awesomeApi\EnumEndPoints;
/**
 * Classe responsável por realizar os serviços de comunicação com a awesomeapi.
 */
class ServicoEconomia {

    public function __construct() {}

    /**
     * Busca o valor de cotação das moedas
     * 
     * @param string $sMoeda1 Código da moeda de origem.
     * @param string $sMoeda2 Código da moeda de destino.
     * @return array Retorna os dados de resposta da requisição.
     */
    public function consultarCotacao($sMoeda1, $sMoeda2) {
        $aResponse = $this->efetuaComunicacao('json/last/'.$sMoeda1.'-'.$sMoeda2);

        if (isset($aResponse[$sMoeda1.$sMoeda2])) {
            return $aResponse[$sMoeda1.$sMoeda2];
        } else {
            throw new Exception('Não é possível fazer a conversão das moedas de '.$sMoeda1.' para '.$sMoeda2.' por causa de uma limitação da api, para verificar as possíveis conversões acesse: https://economia.awesomeapi.com.br/xml/available');
        }
    }

    /**
     * Retorna um array com todas as moedas fornecidas pela api.
     * 
     * @return array Retorna um array associativo com as moedas disponibilizadas pela api.
     */
    public function consultarMoedas() {
        // busca o conteúdo 
        $xmlContent = file_get_contents($this->montaUrlApi('/xml/available/uniq'));

        if ($xmlContent === false) {
            throw new Exception("Erro ao buscar o XML.");
        }

        // Carrega o XML
        $xml = simplexml_load_string($xmlContent, "SimpleXMLElement", LIBXML_NOCDATA);

        if ($xml === false) {
            throw new Exception("Erro ao processar o XML.");
        }

        $json = json_encode($xml);
        $array = json_decode($json, true);

        return $array;
    }

    /**
     * Efetua a comunicação com a api.
     * 
     * @param string $sRecurso Valor a ser concatenado a url base da api, refere-se ao serviço a ser requisitado.
     * @return array Retorna os dados de resposta da requisição.
     */
    private function efetuaComunicacao($sRecurso) {
        $sEndPoint = $this->montaUrlApi($sRecurso);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $sEndPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);

        $oResponse = curl_exec($curl);
    
        curl_close($curl);

        return json_decode($oResponse, true);
    }

    /**
     * Monta a url a ser requisitada para a api.
     * 
     * @param string $sRecurso Valor a ser concatenado a url base da api.
     * @return string Retorna a url a ser requisitado a api.
     */
    private function montaUrlApi($sRecurso) {
        return EnumEndPoints::URL_BASE .$sRecurso;
    }

}