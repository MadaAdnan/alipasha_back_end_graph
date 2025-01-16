<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Models\Advice;

final class UpdateAdvice
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $userId = auth()->id();
        $adviceId = $args['id'];
        $advice = Advice::where('user_id', $userId)->find($adviceId);
        if (!$advice) {
            throw new \Exception('الإعلان غير موجود');
        }
        $advice->update([
            'name' => $data['name'] ?? $advice->name,
            'url' => $data['url'] ?? $advice->url,
            'city_id' => $data['city_id'] ?? $advice->city_id,
            'category_id' => $data['category_id'] ?? $advice->category_id,
            'sub1_id' => $data['sub1_id'] ?? $advice->sub1_id,
            'status' =>isset($data['image']) && $data['image']!=null? ProductActiveEnum::PENDING->value:$advice->status,

        ]);

        if (isset($data['image'])) {
            $advice->clearMediaCollection('image');
            $advice->addMedia($data['image'])->toMediaCollection('image');
        }
        return $advice;
    }
}
