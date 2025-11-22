<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Repositories\PropertyImageRepository\PropertyImageRepositoryInterface;
use Cloudinary\Api\Upload\UploadApi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PropertyImageService
{
    protected $propertyImageRepository;

    public function __construct(PropertyImageRepositoryInterface $propertyImageRepository)
    {
        $this->propertyImageRepository = $propertyImageRepository;
    }

    public function getAllImages($propertyId)
    {
        try {
            $images = $this->propertyImageRepository->allImages($propertyId);
            return $images;
        } catch (Exception $e) {
            return null;
        }
    }

    public function show($propertyId, $imageId)
    {
        try {
            $image = $this->propertyImageRepository->detailPropertyImage($propertyId, $imageId);
            return $image;
        } catch (Exception $e) {
            return null;
        }
    }

    public function create($request, $propertyId)
    {
        try {
            // Upload ảnh lên Cloudinary
            $uploadedFile = Cloudinary::upload(
                $request->file('image_path')->getRealPath(),
                ['folder' => 'real_estate_goline']
            );

            // Lấy URL ảnh từ mảng trả về
            $uploadedFileUrl = $uploadedFile->getSecurePath();
            $data = [
                'property_id' => $propertyId,
                'image_path'  => $uploadedFileUrl,
                'image_name'  => $request->input('image_name'),
                'is_primary'  => $request->input('is_primary', false),
                'sort_order'  => $request->input('sort_order', 0),
            ];

            return $this->propertyImageRepository->create($data);
        } catch (Exception $e) {
            return null;
        }
    }

    public function update(Request $request, $propertyId, $imageId)
    {
        try {
            // Lấy ảnh cũ từ DB
            $oldImage = $this->propertyImageRepository->detailPropertyImage($propertyId, $imageId);

            if ($request->hasFile('image_path')) {
                // Nếu có ảnh cũ thì thử xóa trên Cloudinary
                if ($oldImage && !empty($oldImage->image_path)) {
                    $publicId = $this->getPublicIdFromUrl($oldImage->image_path);

                    if ($publicId) {
                        try {
                            Cloudinary::destroy($publicId);
                        } catch (\Exception $ex) {
                            // Nếu ảnh không tồn tại trên Cloudinary thì bỏ qua, không throw lỗi
                            Log::warning("Cloudinary delete failed: " . $ex->getMessage());
                        }
                    } else {
                        Log::info("Public ID rỗng, bỏ qua xóa Cloudinary.");
                    }
                }

                // Upload ảnh mới lên Cloudinary
                $uploadedFile = Cloudinary::upload(
                    $request->file('image_path')->getRealPath(),
                    ['folder' => 'real_estate_goline']
                );

                // Lấy URL ảnh mới
                $uploadedFileUrl = $uploadedFile->getSecurePath();

                // Merge dữ liệu để update
                $data = [
                    'property_id' => $propertyId,
                    'image_path'  => $uploadedFileUrl,
                    'image_name'  => $request->input('image_name'),
                    'is_primary'  => $request->input('is_primary', false),
                    'sort_order'  => $request->input('sort_order', 0),
                ];

                return $this->propertyImageRepository->update($imageId, $data);
            }

            return null;
        } catch (Exception $e) {
            Log::error("Update image error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Hàm tách public_id từ URL Cloudinary
     */
    private function getPublicIdFromUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $parts = explode('/', trim($path, '/'));

        // Tìm vị trí của "upload"
        $uploadIndex = array_search('upload', $parts);
        if ($uploadIndex === false) {
            return null;
        }

        // Bỏ qua "upload" và "version" (ví dụ: v1756602601)
        $publicIdParts = array_slice($parts, $uploadIndex + 2);

        // Ghép lại thành public_id
        $publicId = implode('/', $publicIdParts);

        // Bỏ extension (.jpg, .png...)
        return pathinfo($publicId, PATHINFO_DIRNAME) !== '.'
            ? pathinfo($publicId, PATHINFO_DIRNAME) . '/' . pathinfo($publicId, PATHINFO_FILENAME)
            : pathinfo($publicId, PATHINFO_FILENAME);
    }


    public function delete($propertyId, $imageId)
    {
        try {
            $oldImage = $this->propertyImageRepository->detailPropertyImage($propertyId, $imageId);
            if ($oldImage != null && !empty($oldImage->image_path)) {
                $publicId = $this->getPublicIdFromUrl($oldImage->image_path);

                if ($publicId) {
                    try {
                        Cloudinary::destroy($publicId);
                    } catch (\Exception $ex) {
                        // Nếu ảnh không tồn tại trên Cloudinary thì bỏ qua, không throw lỗi
                        Log::warning("Cloudinary delete failed: " . $ex->getMessage());
                    }
                } else {
                    Log::info("Public ID rỗng, bỏ qua xóa Cloudinary.");
                }
                $status = $this->propertyImageRepository->delete($imageId);
                return $status;
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function deleteMultiple(Request $request, $propertyId)
    {
        try {
            $imageIds = $request->input('image_ids', []);
            if (empty($imageIds) || !is_array($imageIds)) {
                return null;
            }

            $publicIds = [];
            $deletedDbIds = [];

            // Lấy public_id từ DB
            foreach ($imageIds as $imageId) {
                $oldImage = $this->propertyImageRepository->detailPropertyImage($propertyId, $imageId);
                if ($oldImage && !empty($oldImage->image_path)) {
                    $publicId = $this->getPublicIdFromUrl($oldImage->image_path);
                    if ($publicId) {
                        $publicIds[] = $publicId;
                    } else {
                        Log::info("Public ID rỗng cho image_id {$imageId}, bỏ qua.");
                    }
                    $this->propertyImageRepository->delete($imageId);
                    $deletedDbIds[] = $imageId;
                }
                // Xóa record trong DB (dù có hay không có publicId)
            }

            // Nếu có public_ids thì gọi Cloudinary API để xóa 1 lần
            if (!empty($publicIds)) {
                try {
                    (new UploadApi())->destroy($publicIds, ["invalidate" => true]);
                } catch (\Exception $ex) {
                    Log::warning("Batch delete Cloudinary thất bại: " . $ex->getMessage());
                }
            }

            return [
                'deleted_in_db' => $deletedDbIds,
                'deleted_in_cloudinary' => $publicIds
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getAllHomeAvatars()
    {
        try {
            return $this->propertyImageRepository->getAllHomeAvatars();
        } catch (Exception $e) {
            Log::error('getAllHomeAvatars error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
