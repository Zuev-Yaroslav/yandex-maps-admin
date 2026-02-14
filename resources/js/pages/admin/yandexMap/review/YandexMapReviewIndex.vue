<script setup lang="ts">
import { ref } from 'vue';
import Vue3StarRatings from 'vue3-star-ratings';
import RatingStarIcon from '@/components/icons/RatingStarIcon.vue';
import YandexMapIcon from '@/components/icons/YandexMapIcon.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    reviews: {
        required: false,
        type: Object,
    },
    yandexMapSetting: {
        required: false,
        type: Object,
    },
})
const localReviews = ref({ ...props.reviews });
defineOptions({
    layout: AdminLayout,
});
</script>

<template>
    <div v-if="localReviews && yandexMapSetting" class="reviews">
        <a aria-label="Ссылка на организацию" :href="yandexMapSetting.org_reviews_url" target="_blank"  class="reviews__yandex-map-link">
            <YandexMapIcon
                class="reviews__yandex-map-icon"
            />
            <span>Яндекс Карты</span>
        </a>
        <div class="reviews__wrapper">
            <ul class="reviews-list">
                <li :key="review.reviewId" v-for="review in localReviews.data" class="reviews-list__item">
                    <div class="review-card">
                        <div class="review-card__inner">
                            <div class="review-card__header">
                                <div class="review-card__header-item">
                                    <span class="review-card__date">{{ review.updatedTime }}</span>
                                    <span class="review-card__organization-name">{{ localReviews.meta.organizationName }}</span>
                                    <YandexMapIcon
                                        class="preview-card__yandex-map-icon"
                                    />
                                </div>
                                <div class="review-card__header-item">
                                    <vue3-star-ratings
                                        v-model="review.rating"
                                        :custom-svg="RatingStarIcon"
                                        inactiveColor="#FFFFFF"
                                        :star-size="14"
                                        star-color="#FBBC04"
                                        :disable-click="true"
                                    />
                                </div>
                            </div>
                            <div class="review-card__author">
                                <span class="review-card__author-text">{{ review.author?.name ?? 'Анонимный отзыв' }}</span>
                                <span class="review-card__author-text">{{ review.author?.professionLevel }}</span>
                            </div>
                            <p class="review-card__text">
                               {{ review.text }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="reviews-stats-card">
                <div class="reviews-stats-card__rating-block">
                    <span class="reviews-stats-card__rating-value">{{ localReviews.meta.ratingData.ratingValue }}</span>
                    <vue3-star-ratings
                        v-model="localReviews.meta.ratingData.ratingValue"
                        :star-size="24"
                        :custom-svg="RatingStarIcon"
                        inactiveColor="#FFFFFF"
                        star-color="#FBBC04"
                        :disable-click="true"
                    />
                </div>
                <p class="reviews-stats-card__count">Всего отзывов: {{ localReviews.meta.ratingData.reviewCount }}</p>
            </div>
        </div>
        <p v-if="localReviews.meta.params.count > localReviews.meta.params.limit" class="font-semibold mt-5">Максимум отзывов на этой странице {{ reviews.meta.params.limit }}</p>
    </div>
    <p v-else class="font-semibold">Укажите ссылку в Настройке</p>
</template>

<style scoped>

</style>
