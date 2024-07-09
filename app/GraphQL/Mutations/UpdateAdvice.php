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
            'name' => $data['name'] ?? null,
            'url' => $data['url'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'sub1_id' => $data['sub1_id'] ?? null,

        ]);

        if (isset($data['image'])) {
            $advice->clearMediaCollection('image');
            $advice->addMedia($data['image'])->toMediaCollection('image');
        }
        return $advice;
    }
}
