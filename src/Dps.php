<?php

namespace Hadder\NfseNacional;

/**
 * Class for RPS construction and validation of data
 *
 */

use DOMException;
use DOMNode;
use NFePHP\Common\DOMImproved as Dom;
use stdClass;


class Dps implements DpsInterface
{
    /**
     * @var stdClass
     */
    public $std;
    /**
     * @var DOMNode
     */
    protected $dps;
    /**
     * @var DOMNode
     */
    protected $evento;
    /**
     * @var string
     */
    protected $jsonschema;
    /**
     * @var Dom
     */
    protected $dom;
    private string $dpsId;
    private string $preId;

    /**
     * Constructor
     * @param stdClass|null $std
     * @throws DOMException
     */
    public function __construct(stdClass $std = null)
    {
        $this->init($std);
        $this->dom = new Dom('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;
    }

    /**
     *
     * @param stdClass|null $dps
     */
    private function init(stdClass $dps = null)
    {
        if (!empty($dps)) {
            $this->std = $this->propertiesToLower($dps);
            if (empty($this->std->version)) {
                $this->std->version = '1.00';
            }
            //$ver = str_replace('.', '_', $this->std->version);
            //$this->jsonschema = realpath("../storage/jsonSchemes/v$ver/rps.schema");
            //$this->validInputData();
        }
    }

    public function render(stdClass $std = null)
    {
        if ($this->dom->hasChildNodes()) {
            $this->dom = new Dom('1.0', 'UTF-8');
            $this->dom->preserveWhiteSpace = false;
            $this->dom->formatOutput = false;
        }

        $this->init($std);
        $this->dps = $this->dom->createElement('DPS');
        $this->dps->setAttribute('versao', '1.00');
        $this->dps->setAttribute('xmlns', 'http://www.sped.fazenda.gov.br/nfse');

        $infdps_inner = $this->dom->createElement('infDPS');
        $infdps_inner->setAttribute('Id', $this->generateId());

        $this->dom->addChild(
            $infdps_inner,
            'tpAmb',
            $this->std->infdps->tpamb,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'dhEmi',
            $this->std->infdps->dhemi,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'verAplic',
            $this->std->infdps->veraplic,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'serie',
            $this->std->infdps->serie,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'nDPS',
            $this->std->infdps->ndps,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'dCompet',
            $this->std->infdps->dcompet,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'tpEmit',
            $this->std->infdps->tpemit,
            true
        );
        $this->dom->addChild(
            $infdps_inner,
            'cLocEmi',
            $this->std->infdps->clocemi,
            true
        );

        if (isset($this->std->infdps->subst)) {
            $subst_inner = $this->dom->createElement('subst');
            $infdps_inner->appendChild($subst_inner);
            $this->dom->addChild(
                $subst_inner,
                'chSubstda',
                $this->std->infdps->subst->chsubstda,
                true
            );
            $this->dom->addChild(
                $subst_inner,
                'cMotivo',
                $this->std->infdps->subst->cmotivo,
                true
            );
            $this->dom->addChild(
                $subst_inner,
                'xMotivo',
                $this->std->infdps->subst->xmotivo,
                true
            );
        }

        if (isset($this->std->infdps->prest)) {
            $prest_inner = $this->dom->createElement('prest');
            $infdps_inner->appendChild($prest_inner);
            if (isset($this->std->infdps->prest->cnpj)) {
                $this->dom->addChild(
                    $prest_inner,
                    'CNPJ',
                    $this->std->infdps->prest->cnpj,
                    true
                );
            }
            if (isset($this->std->infdps->prest->cpf)) {
                $this->dom->addChild(
                    $prest_inner,
                    'CPF',
                    $this->std->infdps->prest->cpf,
                    true
                );
            }
            if (isset($this->std->infdps->prest->nif)) {
                $this->dom->addChild(
                    $prest_inner,
                    'NIF',
                    $this->std->infdps->prest->nif,
                    true
                );
            }
            if (isset($this->std->infdps->prest->cnaonif)) {
                $this->dom->addChild(
                    $prest_inner,
                    'cNaoNIF',
                    $this->std->infdps->prest->cnaonif,
                    true
                );
            }
            if (isset($this->std->infdps->prest->caepf)) {
                $this->dom->addChild(
                    $prest_inner,
                    'CAEPF',
                    $this->std->infdps->prest->caepf,
                    true
                );
            }
            if (isset($this->std->infdps->prest->im)) {
                $this->dom->addChild(
                    $prest_inner,
                    'IM',
                    $this->std->infdps->prest->im,
                    true
                );
            }
            if (isset($this->std->infdps->prest->xnome)) {
                $this->dom->addChild(
                    $prest_inner,
                    'xNome',
                    $this->std->infdps->prest->xnome,
                    true
                );
            }
            if (isset($this->std->infdps->prest->end)) {
                $end_inner = $this->dom->createElement('end');
                $prest_inner->appendChild($end_inner);
                if (isset($this->std->infdps->prest->end->endnac)) {
                    $endnac_inner = $this->dom->createElement('endNac');
                    $end_inner->appendChild($endnac_inner);
                    $this->dom->addChild(
                        $endnac_inner,
                        'cMun',
                        $this->std->infdps->prest->end->endnac->cmun,
                        true
                    );
                    $this->dom->addChild(
                        $endnac_inner,
                        'CEP',
                        $this->std->infdps->prest->end->endnac->cep,
                        true
                    );
                } elseif (isset($this->std->infdps->prest->end->endext)) {
                    $endext_inner = $this->dom->createElement('endExt');
                    $end_inner->appendChild($endext_inner);
                    $this->dom->addChild(
                        $endext_inner,
                        'cPais',
                        $this->std->infdps->prest->end->endext->cpais,
                        true
                    );
                    $this->dom->addChild(
                        $endext_inner,
                        'cEndPost',
                        $this->std->infdps->prest->end->endext->cendpost,
                        true
                    );
                    $this->dom->addChild(
                        $endext_inner,
                        'xCidade',
                        $this->std->infdps->prest->end->endext->xcidade,
                        true
                    );
                    $this->dom->addChild(
                        $endext_inner,
                        'xEstProvReg',
                        $this->std->infdps->prest->end->endext->xestprovreg,
                        true
                    );
                }

                //                dd($this->std->infdps->prest->end);
                $this->dom->addChild(
                    $end_inner,
                    'xLgr',
                    $this->std->infdps->prest->end->xlgr,
                    true
                );
                $this->dom->addChild(
                    $end_inner,
                    'nro',
                    $this->std->infdps->prest->end->nro,
                    true
                );
                if (isset($this->std->infdps->prest->end->xcpl)) {
                    $this->dom->addChild(
                        $end_inner,
                        'xCpl',
                        $this->std->infdps->prest->end->xcpl,
                        false
                    );
                }
                $this->dom->addChild(
                    $end_inner,
                    'xBairro',
                    $this->std->infdps->prest->end->xbairro,
                    true
                );
            }
            $this->dom->addChild(
                $prest_inner,
                'fone',
                $this->std->infdps->prest->fone,
                false
            );
            $this->dom->addChild(
                $prest_inner,
                'email',
                $this->std->infdps->prest->email,
                false
            );

            $regtrib_inner = $this->dom->createElement('regTrib');
            $prest_inner->appendChild($regtrib_inner);
            $this->dom->addChild(
                $regtrib_inner,
                'opSimpNac',
                $this->std->infdps->prest->regtrib->opsimpnac,
                true
            );
            $this->dom->addChild(
                $regtrib_inner,
                'regEspTrib',
                $this->std->infdps->prest->regtrib->regesptrib,
                true
            );
            if (isset($this->std->infdps->prest->regtrib->regaptribsn)) {
                $this->dom->addChild(
                    $regtrib_inner,
                    'regApTribSN',
                    $this->std->infdps->prest->regtrib->regaptribsn,
                    false
                );
            }

        }
        if (isset($this->std->infdps->toma)) {
            $toma_inner = $this->dom->createElement('toma');
            $infdps_inner->appendChild($toma_inner);
            if (isset($this->std->infdps->toma->cnpj)) {
                $this->dom->addChild(
                    $toma_inner,
                    'CNPJ',
                    $this->std->infdps->toma->cnpj,
                    true
                );
            }
            if (isset($this->std->infdps->toma->cpf)) {
                $this->dom->addChild(
                    $toma_inner,
                    'CPF',
                    $this->std->infdps->toma->cpf,
                    true
                );
            }
            if (isset($this->std->infdps->toma->nif)) {
                $this->dom->addChild(
                    $toma_inner,
                    'NIF',
                    $this->std->infdps->toma->nif,
                    true
                );
            }
            if (isset($this->std->infdps->toma->cnaonif)) {
                $this->dom->addChild(
                    $toma_inner,
                    'cNaoNIF',
                    $this->std->infdps->toma->cnaonif,
                    true
                );
            }
            if (isset($this->std->infdps->toma->caepf)) {
                $this->dom->addChild(
                    $toma_inner,
                    'CAEPF',
                    $this->std->infdps->toma->caepf,
                    true
                );
            }
            if (isset($this->std->infdps->toma->im)) {
                $this->dom->addChild(
                    $toma_inner,
                    'IM',
                    $this->std->infdps->toma->im,
                    true
                );
            }
            $this->dom->addChild(
                $toma_inner,
                'xNome',
                $this->std->infdps->toma->xnome,
                true
            );
            if (isset($this->std->infdps->toma->end)) {
                $end_inner = $this->dom->createElement('end');
                $toma_inner->appendChild($end_inner);
                if (isset($this->std->infdps->toma->end->endnac)) {
                    $endnac_inner = $this->dom->createElement('endNac');
                    $end_inner->appendChild($endnac_inner);
                    $this->dom->addChild(
                        $endnac_inner,
                        'cMun',
                        $this->std->infdps->toma->end->endnac->cmun,
                        true
                    );
                    $this->dom->addChild(
                        $endnac_inner,
                        'CEP',
                        $this->std->infdps->toma->end->endnac->cep,
                        true
                    );
                } elseif (isset($this->std->infdps->toma->end->endext)) {
                    $endext_inner = $this->dom->createElement('endExt');
                    $end_inner->appendChild($endext_inner);
                    $this->dom->addChild(
                        $endext_inner,
                        'cPais',
                        $this->std->infdps->toma->end->endext->cpais,
                        true
                    );
                    $this->dom->addChild(
                        $endext_inner,
                        'cEndPost',
                        $this->std->infdps->toma->end->endext->cendpost,
                        true
                    );
                    $this->dom->addChild(
                        $endext_inner,
                        'xCidade',
                        $this->std->infdps->toma->end->endext->xcidade,
                        true
                    );
                    $this->dom->addChild(
                        $endext_inner,
                        'xEstProvReg',
                        $this->std->infdps->toma->end->endext->xestprovreg,
                        true
                    );
                }

                //                dd($this->std->infdps->toma->end);
                $this->dom->addChild(
                    $end_inner,
                    'xLgr',
                    $this->std->infdps->toma->end->xlgr,
                    true
                );
                $this->dom->addChild(
                    $end_inner,
                    'nro',
                    $this->std->infdps->toma->end->nro,
                    true
                );
                if (isset($this->std->infdps->toma->end->xcpl)) {
                    $this->dom->addChild(
                        $end_inner,
                        'xCpl',
                        $this->std->infdps->toma->end->xcpl,
                        false
                    );
                }
                $this->dom->addChild(
                    $end_inner,
                    'xBairro',
                    $this->std->infdps->toma->end->xbairro,
                    true
                );
            }
            $this->dom->addChild(
                $toma_inner,
                'fone',
                $this->std->infdps->toma->fone,
                false
            );
            $this->dom->addChild(
                $toma_inner,
                'email',
                $this->std->infdps->toma->email,
                false
            );
        }

        //TODO Fazer grupo interm
        //if (isset($this->std->interm)) {
        //    $interm_inner = $this->dom->createElement('interm');
        //    $infdps_inner->appendChild($interm_inner);
        //}

        $serv_inner = $this->dom->createElement('serv');
        $infdps_inner->appendChild($serv_inner);

        $locprest_inner = $this->dom->createElement('locPrest');
        $serv_inner->appendChild($locprest_inner);
        $this->dom->addChild(
            $locprest_inner,
            'cLocPrestacao',
            $this->std->infdps->serv->locprest->clocprestacao,
            true
        );
        if (isset($this->std->infdps->serv->locprest->cpaisprestacao)) {
            $this->dom->addChild(
                $locprest_inner,
                'cPaisPrestacao',
                $this->std->infdps->serv->locprest->cpaisprestacao,
                true
            );
        }
        if (isset($this->std->infdps->serv->locprest->cpaisconsum)) {
            $this->dom->addChild(
                $locprest_inner,
                'cPaisConsum',
                $this->std->infdps->serv->locprest->cpaisconsum,
                true
            );
        }

        $cserv_inner = $this->dom->createElement('cServ');
        $serv_inner->appendChild($cserv_inner);

        $this->dom->addChild(
            $cserv_inner,
            'cTribNac',
            $this->std->infdps->serv->cserv->ctribnac,
            true
        );
        if (isset($this->std->infdps->serv->cserv->ctribmun)) {
            $this->dom->addChild(
                $cserv_inner,
                'cTribMun',
                $this->std->infdps->serv->cserv->ctribmun,
                true
            );
        }
        if (isset($this->std->infdps->serv->cserv->cpaisprestacao)) {
            $this->dom->addChild(
                $cserv_inner,
                'cPaisPrestacao',
                $this->std->infdps->serv->cserv->cpaisprestacao,
                true
            );
        }
        $this->dom->addChild(
            $cserv_inner,
            'xDescServ',
            $this->std->infdps->serv->cserv->xdescserv,
            true
        );
        if (isset($this->std->infdps->serv->cserv->cnbs)) {
            $this->dom->addChild(
                $cserv_inner,
                'cNBS',
                $this->std->infdps->serv->cserv->cnbs,
                true
            );
        }
        if (isset($this->std->infdps->serv->cserv->cintcontrib)) {
            $this->dom->addChild(
                $cserv_inner,
                'cIntContrib',
                $this->std->infdps->serv->cserv->cintcontrib,
                true
            );
        }

        //TODO Fazer grupo comExt
        //TODO Fazer grupo lsadppu
        //TODO Fazer grupo obra
        //TODO Fazer grupo atvEvento
        //TODO Fazer grupo explRod
        //TODO Fazer grupo infoCompl


        if (isset($this->std->infdps->serv->infocompl->xinfcomp)) {
            $infocompl_inner = $this->dom->createElement('infoCompl');
            $serv_inner->appendChild($infocompl_inner);

            $this->dom->addChild(
                $cserv_inner,
                'cTribNac',
                $this->std->infdps->serv->infocompl->xinfcomp,
                true
            );
        }

        $valores_inner = $this->dom->createElement('valores');
        $infdps_inner->appendChild($valores_inner);
        $vservprest_inner = $this->dom->createElement('vServPrest');
        $valores_inner->appendChild($vservprest_inner);

        $this->dom->addChild(
            $vservprest_inner,
            'vServ',
            $this->std->infdps->valores->vservprest->vserv,
            true
        );

        //TODO Fazer grupo vDescCondIncond
        //TODO Fazer grupo vDedRed

        $trib_inner = $this->dom->createElement('trib');
        $valores_inner->appendChild($trib_inner);

        $tribmun_inner = $this->dom->createElement('tribMun');
        $trib_inner->appendChild($tribmun_inner);

        $this->dom->addChild(
            $tribmun_inner,
            'tribISSQN',
            $this->std->infdps->valores->trib->tribmun->tribissqn,
            true
        );
        if (isset($this->std->infdps->valores->trib->tribmun->tpretissqn)) {
            $this->dom->addChild(
                $tribmun_inner,
                'tpRetISSQN',
                $this->std->infdps->valores->trib->tribmun->tpretissqn,
                true
            );
        }

        //TODO Fazer grupo tribFed


        $tottrib_inner = $this->dom->createElement('totTrib');
        $trib_inner->appendChild($tottrib_inner);

        $this->dom->addChild(
            $tottrib_inner,
            'indTotTrib',
            $this->std->infdps->valores->trib->tottrib->indtottrib,
            true
        );


        $dps = $this->dom->createElement('DPS');
        $dps->setAttribute('versao', '1.00');
        $dps->setAttribute('xmlns', 'http://www.sped.fazenda.gov.br/nfse');
        $this->dps->appendChild($infdps_inner);
        $this->dom->appendChild($this->dps);
        /*        return str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $this->dom->saveXML());*/
        return $this->dom->saveXML();
    }

    public function renderEvento(stdClass $std = null)
    {
        if ($this->dom->hasChildNodes()) {
            $this->dom = new Dom('1.0', 'UTF-8');
            $this->dom->preserveWhiteSpace = false;
            $this->dom->formatOutput = false;
        }

        $this->init($std);
        $this->evento = $this->dom->createElement('pedRegEvento');
        $this->evento->setAttribute('versao', '1.00');
        $this->evento->setAttribute('xmlns', 'http://www.sped.fazenda.gov.br/nfse');

        $infpedreg_inner = $this->dom->createElement('infPedReg');
        $infpedreg_inner->setAttribute('Id', $this->generatePre());

        $this->dom->addChild(
            $infpedreg_inner,
            'tpAmb',
            $this->std->infpedreg->tpamb,
            true
        );
        $this->dom->addChild(
            $infpedreg_inner,
            'verAplic',
            $this->std->infpedreg->veraplic,
            true
        );
        $this->dom->addChild(
            $infpedreg_inner,
            'dhEvento',
            $this->std->infpedreg->dhevento,
            true
        );
        if (isset($this->std->infpedreg->cnpjautor)) {
            $this->dom->addChild(
                $infpedreg_inner,
                'CNPJAutor',
                $this->std->infpedreg->cnpjautor,
                true
            );
        }
        if (isset($this->std->infpedreg->cpfautor)) {
            $this->dom->addChild(
                $infpedreg_inner,
                'CPFAutor',
                $this->std->infpedreg->cpfautor,
                true
            );
        }
        $this->dom->addChild(
            $infpedreg_inner,
            'chNFSe',
            $this->std->infpedreg->chnfse,
            true
        );
        $this->dom->addChild(
            $infpedreg_inner,
            'nPedRegEvento',
            $this->std->npedregevento,
            true
        );


        if (isset($this->std->infpedreg->e101101)) {
            $e101101_inner = $this->dom->createElement('e101101');
            $infpedreg_inner->appendChild($e101101_inner);
            $this->dom->addChild(
                $e101101_inner,
                'xDesc',
                $this->std->infpedreg->e101101->xdesc,
                true
            );
            $this->dom->addChild(
                $e101101_inner,
                'cMotivo',
                $this->std->infpedreg->e101101->cmotivo,
                true
            );
            $this->dom->addChild(
                $e101101_inner,
                'xMotivo',
                $this->std->infpedreg->e101101->xmotivo,
                true
            );
        }

        $dps = $this->dom->createElement('DPS');
        $dps->setAttribute('versao', '1.00');
        $dps->setAttribute('xmlns', 'http://www.sped.fazenda.gov.br/nfse');
        $this->evento->appendChild($infpedreg_inner);
        $this->dom->appendChild($this->evento);
        /*        return str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $this->dom->saveXML());*/
        return $this->dom->saveXML();
    }

    public function setFormatOutput(bool $formatOutput)
    {
        $this->dom->formatOutput = $formatOutput;
    }

    public function setStd(stdClass $std)
    {
        $this->init($std);
    }

    /**
     * Mudar todas proprioedades da stdClass para minúsculas
     * @param stdClass $data
     * @return stdClass
     */
    public static function propertiesToLower(stdClass $data)
    {
        $properties = get_object_vars($data);
        $clone = new stdClass();
        foreach ($properties as $key => $value) {
            if ($value instanceof stdClass) {
                $value = self::propertiesToLower($value);
            }
            $newkey = strtolower($key);
            $clone->{$newkey} = $value;
        }
        return $clone;
    }

    //    /**
    //     * Validation json data from json Schema
    //     * @param stdClass $data
    //     * @return boolean
    //     * @throws \RuntimeException
    //     */
    //    protected function validInputData()
    //    {
    //        if (!is_file($this->jsonschema)) {
    //            return true;
    //        }
    //        $validator = new JsonValid();
    //        $validator->check($this->std, (object)['$ref' => 'file://' . $this->jsonschema]);
    //        if (!$validator->isValid()) {
    //            $msg = "";
    //            foreach ($validator->getErrors() as $error) {
    //                $msg .= sprintf("[%s] %s\n", $error['property'], $error['message']);
    //            }
    //            throw new InvalidArgumentException($msg);
    //        }
    //        return true;
    //    }

    public function getDpsId()
    {
        return $this->dpsId;
    }

    public function getEventoId()
    {
        return $this->preId;
    }

    private function generateId()
    {
        $string = 'DPS';
        $string .= substr($this->std->infdps->clocemi, 0, 7); //Cód.Mun. (7) +
        $string .= isset($this->std->infdps->prest->cnpj) ? 2 : 1; //Tipo de Inscrição Federal (1) +
        if (isset($this->std->infdps->prest->cnpj)) {
            $inscricao = $this->std->infdps->prest->cnpj;
        } else {
            $inscricao = $this->std->infdps->prest->cpf;
        }
        $string .= str_pad($inscricao, 14, 0, STR_PAD_LEFT); //Inscrição Federal (14 - CPF completar com 000 à esquerda) +
        $string .= str_pad($this->std->infdps->serie, 5, 0, STR_PAD_LEFT); //Série DPS (5) +
        $string .= str_pad($this->std->infdps->ndps, 15, 0, STR_PAD_LEFT); //Série DPS (5) +
        $this->dpsId = $string;
        return $string;
    }

    private function generatePre()
    {
        $string = 'PRE';
        $string .= $this->std->infpedreg->chnfse; //Chave de acesso da NFS-e (50) +
        $string .= $this->codigoEvento(); //Código do evento (6)
        $string .= str_pad($this->std->npedregevento, 3, 0, STR_PAD_LEFT); //Número do Pedido de Registro do Evento (nPedRegEvento) (3)
        $this->preId = $string;
        return $string;
    }

    private function codigoEvento()
    {
        $codigo = '000000';
        switch (true) {
            case isset($this->std->infpedreg->e101101):
                $codigo = '101101';
                break;
            case isset($this->std->infpedreg->e105102):
                $codigo = '105102';
                break;
        }

        return $codigo;
    }

}
