<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { HttpStatus } from 'http-status-ts';
import { reactive, ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    yandexMapSetting: {
        required: false,
        type: Object,
    }
})
const setting = reactive({ ...props.yandexMapSetting });
const errorValidation = ref({});
const isSuccessSaved = ref(false);
const isSyncProcess = ref(false);

const updateSetting = () => {
    isSuccessSaved.value = false;
    isSyncProcess.value = false;
    errorValidation.value = {};
    document.body.style.cursor = 'progress';
    axios.patch(route('admin.yandex-maps.setting.update'), setting)
        .then(() => {
            isSuccessSaved.value = true;
            router.post(route('admin.yandex-maps.reviews.sync'), {}, {
                onStart: () => {
                    isSyncProcess.value = true;
                },
                onFinish: () => {
                    document.body.style.cursor = 'default';
                },
                onError: () => {

                }
            })
        })
        .catch((error) => {
            console.log(error.response);
            if (error.response.status === HttpStatus.UNPROCESSABLE_ENTITY) {
                errorValidation.value = error.response.data;
            }
            document.body.style.cursor = 'default';
        })
}

defineOptions({
    layout: AdminLayout
})
</script>

<template>
    <div class="setting">
        <h2 class="setting__title">Подключить яндекс</h2>
        <label for="org-reviews-url" class="setting__org-reviews-url-label">
            Укажите ссылку на Яндекс, пример
            <a
                class="setting__link"
                href="https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/"
                target="_blank"
            >
                https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/
            </a>
        </label>
        <input id="org-reviews-url" class="setting__input-text" type="text" v-model="setting.org_reviews_url">
        <div v-if="errorValidation?.errors" class="setting__validation-error">
            {{ errorValidation?.errors?.org_reviews_url?.[0] }}
        </div>
        <div v-else-if="isSuccessSaved" class="setting__success-message">
            {{ 'Сохранено. ' }} {{ isSyncProcess ? 'Начался процесс синхронизации отзывов.' : '' }}
        </div>
        <div v-else class="setting__validation-error">
            {{ '&nbsp;' }}
        </div>
        <button type="button" class="setting__save-button" @click="updateSetting">Сохранить</button>
    </div>
</template>

<style scoped>

</style>
