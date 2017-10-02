<?php
/**
 * Created by PhpStorm.
 * User: jgpadua
 * Date: 02/10/2017
 * Time: 10:46
 */

require_once ('Loteria.php');

class LotoFacil extends Loteria
{
    public function __construct()
    {
        $this->dat_sorteio = date("d/m/Y H:i:s");
        $this->numeros = [];
        $this->vlr_minimo = 1;
        $this->vlr_maximo = 25;
        $this->qtd_numeros = 15;
    }
}