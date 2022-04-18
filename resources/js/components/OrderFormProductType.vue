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
            v-for="category in store.superCategories"
            :key="category"
            :label="category.name"
        >
            <template v-for="item in store.categories" :key="item">
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
import {onMounted} from "vue";
import {orderFormStore} from "../stores/store";

export default {
    name: "OrderFormProductType",
    setup() {
        const store = orderFormStore();

        onMounted(() => {
            store.fetchProductTypes();
        });

        return {
            store,
        };
    },
};
</script>

<style scoped></style>
