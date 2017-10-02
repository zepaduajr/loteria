<?php
/**
 * Created by PhpStorm.
 * User: jgpadua
 * Date: 02/10/2017
 * Time: 14:34
 */

class Loteria
{
    public $dat_sorteio;
    public $numeros;
    public $vlr_minimo;
    public $vlr_maximo;
    public $qtd_numeros;

    public function getNumeros(){
        return $this->numeros;
    }

    public function getDatSorteio(){
        return $this->dat_sorteio;
    }

    public function setNumeros($j){
        $this->numeros = $j;
    }

    public function gerarNumerosAleatorios($qtd_jogos = 1)
    {
        $arrayNumeros = [];
        for ($x = 0; $x < $qtd_jogos; $x++){
            for ($i=0; $i < $this->qtd_numeros; $i++){
                $numero = $this->gerarNumeroAleatorio();
                $arrayNumeros[$i] = $numero;
            }
            $arrayNumeros['dat_sorteio'] = $this->dat_sorteio;
            array_push($this->numeros, $arrayNumeros);
        }

        return $this->numeros;
    }

    public function gerarNumeroAleatorio()
    {
        $numero = rand($this->vlr_minimo,$this->vlr_maximo);
        while (!$this->validaNumeroUnico($numero)){
            $numero = rand($this->vlr_minimo,$this->vlr_maximo);
        }

        return $numero;
    }

    protected function validaNumeroUnico($numero)
    {
        if(array_search($numero, $this->numeros))
        {
            return false;
        }

        return true;
    }
}