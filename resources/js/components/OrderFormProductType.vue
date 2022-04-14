<template>
    <label class="mb-1 mt-2 mt-md-0" for="categories">Тип изделия</label>
    <select
        id="categories"
        v-model="selected"
        name="categories"
        class="form-control"
    >
        <option>Тип изделия</option>
        <optgroup
            v-for="category in groupCategories"
            :key="category"
            :label="category.name"
        >
            <template v-for="item in categoriesItems" :key="item">
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
import { onMounted, ref } from "vue";
import axios from "axios";

export default {
    name: "OrderFormProductType",
    setup() {
        const selected = ref("Тип изделия");
        const categoriesItems = ref(null);
        const groupCategories = ref(null);
        onMounted(async () => {
            const {
                data: {
                    data: { ...categories },
                    superCategories: { ...superCategories },
                },
            } = await axios.get("/api/categories");
            categoriesItems.value = categories;
            groupCategories.value = superCategories;
        });

        return {
            categoriesItems,
            groupCategories,
            selected,
        };
    },
};
</script>

<style scoped></style>
