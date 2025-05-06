<?php

namespace App\Services\Destination;

use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class DestinationImageService
{
    private const MAX_IMAGES = 5;

    /**
     * Memproses gambar untuk destinasi
     *
     * @param Destination $destination Destinasi yang akan diunggah gambarnya
     * @param array $images Array file gambar
     * @param int|null $primaryIndex Indeks gambar yang akan dijadikan utama
     * @param bool $makePrimary Apakah perlu mengatur gambar utama
     * @return void
     */
    public function processImages(
        Destination $destination,
        array $images,
        ?int $primaryIndex = 0,
        bool $makePrimary = true
    ): void {
        foreach ($images as $index => $imageFile) {
            $imageName = uniqid() . '-' . Str::random(10) . '-' . time() . '.' . $imageFile->extension();
            $path = $imageFile->storeAs('destinations', $imageName);
            $url = str_replace('', '', $path);

            $isPrimary = $makePrimary && $primaryIndex !== null && $index == $primaryIndex;

            if ($isPrimary) {
                DestinationImage::where('destination_id', $destination->id)
                    ->update(['is_primary' => false]);
            }

            DestinationImage::create([
                'destination_id' => $destination->id,
                'url' => $url,
                'is_primary' => $isPrimary,
            ]);
        }
    }

    /**
     * Menghapus semua gambar terkait destinasi
     *
     * @param Destination $destination
     * @return void
     */
    public function deleteDestinationImages(Destination $destination): void
    {
        foreach ($destination->images as $image) {
            Storage::delete($image->url);
            $image->delete();
        }
    }

    /**
     * Menghapus gambar dari destinasi
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return bool
     */
    public function deleteImage(Destination $destination, DestinationImage $image): bool
    {
        $this->validateImageOwnership($destination, $image);

        if ($this->isLastImage($destination)) {
            return false;
        }

        $this->handlePrimaryImageDeletion($destination, $image);

        Storage::delete($image->url);
        return $image->delete();
    }

    /**
     * Validasi kepemilikan gambar
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return void
     */
    private function validateImageOwnership(Destination $destination, DestinationImage $image): void
    {
        if ($image->destination_id !== $destination->id) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }

    /**
     * Cek apakah gambar adalah yang terakhir
     *
     * @param Destination $destination
     * @return bool
     */
    private function isLastImage(Destination $destination): bool
    {
        return $destination->images()->count() <= 1;
    }

    /**
     * Mengatur gambar utama baru saat gambar utama dihapus
     *
     * @param Destination $destination
     * @param DestinationImage $image
     * @return void
     */
    private function handlePrimaryImageDeletion(Destination $destination, DestinationImage $image): void
    {
        if ($image->is_primary) {
            $nextImage = DestinationImage::where('destination_id', $destination->id)
                ->where('id', '!=', $image->id)
                ->first();

            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }
    }

    /**
     * Validasi jumlah gambar
     *
     * @param Destination $destination
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function validateImageCount(Destination $destination, array $data): void
    {
        $existingImageCount = $destination->images()->count();
        $newImageCount = isset($data['image']) ? count($data['image']) : 0;

        if (($existingImageCount + $newImageCount) > self::MAX_IMAGES) {
            throw new Exception("Total gambar tidak boleh lebih dari " . self::MAX_IMAGES . ".");
        }
    }

    /**
     * Memperbarui gambar utama
     *
     * @param Destination $destination
     * @param int $primaryImageId
     * @return void
     */
    public function updatePrimaryImage(Destination $destination, int $primaryImageId): void
    {
        DestinationImage::where('destination_id', $destination->id)
            ->where('id', '!=', $primaryImageId)
            ->update(['is_primary' => false]);

        $primaryImage = $destination->images()->find($primaryImageId);
        if ($primaryImage) {
            $primaryImage->update(['is_primary' => true]);
        }
    }
}
