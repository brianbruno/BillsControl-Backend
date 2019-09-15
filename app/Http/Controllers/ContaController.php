<?php


namespace App\Http\Controllers;


use App\Conta;
use Illuminate\Http\Request;

class ContaController {

    public function contas(Request $request) {

        $resultado = [
            "status" => false,
            "data"   => []
        ];

        try {

            $userid = $request->user_id;

            $contas = Conta::where('user_id', $userid)->get();
            $resultado["data"] = $contas;

            $resultado["status"] = true;

        } catch (\Exception $e) {
            $resultado["status"] = false;
        }


        return response()->json($resultado);
    }

    public function criarConta(Request $request) {
        $resultado = [
            "status" => false,
            "data"   => []
        ];

        try {

            $userid = $request->user_id;
            $nomeConta = $request->nomeConta;
            $codigoBarras = $request->codigoBarras;
            $dataVencimento = $request->dataVencimento;
            $imagem = $request->imagem;

            $conta = new Conta();
            $conta->user_id = $userid;
            $conta->nome_conta = $nomeConta;
            $conta->vencimento = $dataVencimento;
            $conta->codigo_barras = $codigoBarras;

            $conta->save();

            $resultado["status"] = true;
            $resultado["data"] = $conta;

        } catch (\Exception $e) {
            $resultado["status"] = false;
        }


        return response()->json($resultado);
    }

    public function ping() {
        return "gui te amo";
    }

}
