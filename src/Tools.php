<?php

namespace Hadder\NfseNacional;

use DOMDocument;
use NFePHP\Common\Certificate;

class Tools extends RestCurl
{
    public function __construct(string $config, Certificate $cert)
    {
        parent::__construct($config, $cert);
    }

    public function consultarNfseChave($chave)
    {
        $operacao = 'nfse/' . $chave;
        $retorno = $this->getData($operacao);

        if (isset($retorno['erro'])) {
            throw new \Exception($retorno['erro']);
        }
        if ($retorno) {
            $base_decode = base64_decode($retorno['nfseXmlGZipB64']);
            $gz_decode = gzdecode($base_decode);
            return mb_convert_encoding($gz_decode, 'ISO-8859-1', 'UTF-8');
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    public function consultarDpsChave($chave)
    {
        $operacao = 'dps/' . $chave;
        $retorno = $this->getData($operacao);

        if (isset($retorno['erro'])) {
            throw new \Exception($retorno['erro']);
        }
        if ($retorno) {
            return $retorno;
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    public function consultarNfseEventos($chave, $tipoEvento = null, $nSequencial = null)
    {
        $operacao = 'nfse/' . $chave . '/eventos';
        if ($tipoEvento) {
            $operacao .= '/' . $tipoEvento;
        }
        if ($nSequencial) {
            $operacao .= '/' . $nSequencial;
        }
        $retorno = $this->getData($operacao);
        if (isset($retorno['erro'])) {
            throw new \Exception($retorno['erro']);
        }
        if ($retorno) {
            return $retorno;
        }

        return null;
    }

    public function consultarDanfse($chave)
    {
        $operacao = 'danfse/' . $chave;
        return $this->getData(true, $operacao);
    }

    public function enviaDps($content)
    {
        //$content = $this->canonize($content);
        $content = $this->sign($content, 'infDPS', '', 'DPS');
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . $content;

        $gz = gzencode($content);
        $data = base64_encode($gz);
        $dados = [
            'dpsXmlGZipB64' => $data
        ];
        return $this->postData('nfse', json_encode($dados));
    }

    /**
     * @throws \DOMException
     */
    public function cancelaNfse($std)
    {
        $dps = new \Hadder\NfseNacional\Dps($std);
        $content = $dps->renderEvento($std);
        //$content = $this->canonize($content);
        $content = $this->sign($content, 'infPedReg', '', 'pedRegEvento');
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . $content;
        $gz = gzencode($content);
        $data = base64_encode($gz);
        $dados = [
            'pedidoRegistroEventoXmlGZipB64' => $data
        ];
        $operacao = 'nfse/' . $std->infPedReg->chNFSe . '/eventos';
        $retorno = $this->postData($operacao, json_encode($dados));
        if (isset($retorno['erro'])) {
            throw new \Exception($retorno['erro']);
        }
        if ($retorno) {
            return $retorno;
        }
        return null;
    }

    protected function canonize($content)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($content);
        dump($dom->saveXML());
        return $dom->C14N(false, false, null, null);
    }
}
