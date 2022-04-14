<template>
    <label class="mb-1 mt-2 mt-md-0" for="categories">Тип изделия</label>
    <select
        id="categories"
        v-model="store.categoryId"
        name="categories"
        class="form-control"
        @change="store.fetchCategories"
    >
        <option value="0" selected disabled>Тип изделия</option>
        <optgroup
            v-for="category in superCategories"
            :key="category"
            :label="category.name"
        >
            <template v-for="item in categories" :key="item">
                <option
                    v-if="item.parent_id === category.id"
                    selected
                    :value="item.id"
                >
                    {{ item.name }}
                </option>
            </template>
        </optgroup>
    </select>
</template>

<script>
import { computed, onMounted } from "vue";
import { orderFormStore } from "../store/store";

export default {
    name: "OrderFormProductType",
    setup() {
        const store = orderFormStore();
        const categories = computed(() => {
            return store.categories;
        });
        const superCategories = computed(() => {
            return store.superCategories;
        });

        onMounted(() => {
            store.fetchProductTypes();
        });

        return {
            store,
            categories,
            superCategories,
        };
    },
};
</script>

<style scoped></style>
