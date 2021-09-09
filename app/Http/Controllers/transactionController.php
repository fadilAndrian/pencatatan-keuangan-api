<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class transactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Transaction::orderBy('tanggal', 'DESC')->get();
        $response = [
            'message' => 'Data seluruh transaksi',
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaksi' => ['required'],
            'jumlah' => ['required', 'numeric'],
            'tipe' => ['required', 'in:Pengeluaran, Pemasukan']
        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = Transaction::create($request->all());
            $response = [
                'message' => 'Transaksi berhasil disimpan',
                'data' => $data
            ];

            return response()->json($response, Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                'message' => "Gagal".$e->errorInfo
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'transaksi' => ['required'],
            'jumlah' => ['required', 'numeric'],
            'tipe' => ['required', 'in:Pengeluaran, Pemasukan']
        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data->update($request->all());
            $response = [
                'message' => 'Transaksi berhasil diperbarui',
                'data' => $data
            ];

            return response()->json($response, Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'message' => "Gagal".$e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Transaction::findOrFail($id);

        try {
            $data->delete();
            $response = [
                $message = 'Data berhasil dihapus'
            ];

            return Response()->json($response, Response::HTTP_OK);
            
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Gagal".$e->errorInfo
            ]);
        }
    }
}
