<script setup lang="ts">
import { reactive, ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps({
    yandexMapSetting: {
        required: false,
        type: Object,
    }
})
const setting = reactive({ ...props.yandexMapSetting });
const errorValidation = ref({});
const isSuccessSaved = ref(false);

const updateSetting = () => {
    isSuccessSaved.value = false;
    errorValidation.value = {};
    axios.patch(route('admin.yandex-maps.setting.update'), setting)
        .then(() => {
            isSuccessSaved.value = true;
        })
        .catch((error) => {
            errorValidation.value = error.response.data;
        })
}

defineOptions({
    layout: AdminLayout
})
</script>

<template>
    <div class="setting">
        <h2 class="setting__title">Подключичить яндекс</h2>
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
        <div v-if="!isSuccessSaved" class="setting__validation-error">
            {{ errorValidation?.errors?.org_reviews_url?.[0] || '&nbsp;' }}
        </div>
        <div v-else class="setting__success-message">
            Сохранено
        </div>
        <button type="button" class="setting__save-button" @click="updateSetting">Сохранить</button>
    </div>
</template>

<style scoped>

</style>
