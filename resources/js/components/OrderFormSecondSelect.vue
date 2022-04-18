<template>
    <div :id="name" class="mt-0 mb-3">
        <label :for="name">{{ label }} </label>
        <select
            :id="name"
            v-model="store.additional"
            :name="name"
            class="form-control"
            @change="store.fetchProfiles"
        >
            <option disabled selected value="0">Выберите</option>
            <option v-for="item in productOptions" :key="item" :value="item.id">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
import { orderFormStore } from "../stores/store";
import { computed } from "vue";

export default {
    name: "OrderFormSecondSelect",
    setup() {
        const store = orderFormStore();
        const productOptions = computed(() => {
            return store.productOptions;
        });
        const name = computed(() => {
            return store.productName;
        });
        const label = computed(() => {
            return store.productLabel;
        });

        return {
            store,
            productOptions,
            name,
            label,
        };
    },
};
</script>

<style scoped></style>
