<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\ProductActiveEnum;
use App\Models\Product;
use Carbon\Carbon;

final class UpdateJob
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $data = $args['input'];
        $jobId = $args['id'];
        $userId = auth()->id();
        $product = Product::job()->where('user_id', $userId)->find($jobId);
        if (!$product) {
            throw new \Exception('الوظيفة رقم ' . $jobId . ' غير موجود');
        }
        $product->update([
            'name' => \Str::words($data['info'], 10),
            'info' => $data['info'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'tags' => $data['tags'] ?? null,
            'active' =>auth()->user()->is_default_active?$product->active:ProductActiveEnum::PENDING->value,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'start_date' => isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
            'end_date' => isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
            'code' => $data['code'] ?? null,
            'url' => $data['url'] ?? null,
            'expert' => \Str::words($data['info'], 10),
            'category_id' => $data['category_id'] ?? null,
            'sub1_id' => $data['sub1_id'] ?? null,
            'sub2_id' => $data['sub2_id'] ?? null,
            'sub3_id' => $data['sub3_id'] ?? null,
            'sub4_id' => $data['sub4_id'] ?? null,
        ]);

        if (isset($data['attach'])) {
            if (is_array($data['attach'])) {
                foreach ($data['attach'] as $doc) {
                    $product->addMedia($doc)->toMediaCollection('docs');
                }
            } else {
                $product->addMedia($data['attach'])->toMediaCollection('docs');
            }
        }

        return $product;
    }
}
