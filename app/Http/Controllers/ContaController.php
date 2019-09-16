<?php


namespace App\Http\Controllers;

use Faker\Provider\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Conta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;


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
            "mensagem" => "",
            "data"   => []
        ];

        try {

            $userid = $request->user_id;
            $nomeConta = $request->nomeConta;
            $codigoBarras = $request->codigoBarras;
            $dataVencimento = $request->dataVencimento;
            $valorConta = $request->valorConta;
            $dataVencimento = Carbon::createFromFormat("Y-m-d", substr($dataVencimento, 0, 10));
            $imagem = $request->imagem;

            Storage::put('params.txt', $imagem);

            $conta = new Conta();
            $conta->user_id = $userid;
            $conta->nome_conta = $nomeConta;
            $conta->vencimento = $dataVencimento;
            $conta->codigo_barras = $codigoBarras;
            $conta->valor_conta = $valorConta;

            $conta->save();

            if ($imagem !== '') {

                $base64_str = substr($imagem, strpos($imagem, ',')+1);

                //decode base64 string
                $image = base64_decode($base64_str);

                $imageName = $conta->id.'.png';

                $conta->endereco_foto = $imageName;
                $conta->save();

                Storage::disk('local')->put($imageName, $image);

                // $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                $path = $imageName;
                $client = new Client(['base_uri' => 'http://localhost:5000']);

                $options = [];

                $response = $client->get('detect/'.$path, $options);

            }


            $resultado["status"] = true;
            $resultado["data"] = $conta;
            $resultado["mensagem"] = "Salvo com sucesso";

        } catch (\Exception $e) {
            $resultado["mensagem"] = $e->getMessage();
            $resultado["status"] = false;
        }


        return response()->json($resultado);
    }

    public function ping() {
        return "gui te amo";
    }

}
