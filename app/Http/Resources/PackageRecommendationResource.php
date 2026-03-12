<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\CurrencyService;

class PackageRecommendationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currencyService = app(CurrencyService::class);
        $imageUrl = $this->primary_image_url;

        $cheapestSchedule = $this->tripSchedules->first();
        $price = $cheapestSchedule ? $cheapestSchedule->price : $this->base_price;

        $avgRating = $this->reviews->avg('rating');
        $reviewCount = $this->reviews->count();

        // Badge logic
        $badge = null;
        if ($this->is_trending) {
            $badge = 'HOT';
        } elseif ($cheapestSchedule && $cheapestSchedule->available_seats <= 5) {
            $badge = 'LIMITED';
        } elseif ($reviewCount > 0 && $avgRating >= 4.5) {
            $badge = 'TOP RATED';
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $imageUrl,
            'price' => $price,
            'price_formatted' => $currencyService->format($price),
            'location' => $this->location_text,
            'duration' => $this->duration_days ? $this->duration_days . 'D' : null,
            'rating' => $avgRating ? round($avgRating, 1) : null,
            'review_count' => $reviewCount,
            'badge' => $badge,
            'url' => route('tour.show', $this->slug),
        ];
    }
}
