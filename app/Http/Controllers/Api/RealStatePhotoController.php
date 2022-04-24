<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private $realStatePhoto;
    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId)
    {
        try {
            $photo = $this->realStatePhoto->where('real_state_id', $realStateId)->where('is_thumb');
            if ($photo->count()) $photo->first()->update(['is_thumb' => false]);


            $photo = $this->realStatePhoto->findOrFail($photoId);
            $photo->update(['is_thumb' => true]);

            return Response()->json([
                'message' => 'Thumb atualizada com sucesso!'
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function remove($photoId)
    {
        try {
            $photo = $this->realStatePhoto->findOrFail($photoId);

            if($photo->is_thumb){
                $message = new ApiMessages('Não é possivel remover foto de thumb, selelecione outra thamb e remova a imagem desejada');
                return response()->json($message->getMessage(), 401);
            }


            if ($photo) {
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return Response()->json([
                'messagem' => 'Thumb removida com sucesso!'
            ]);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
