<template>
    <div :id="name" class="col-10 col-md-3 mt-2 mt-md-0">
        <label :for="name">{{ label }} </label>
        <select :id="name" :name="name" class="form-control">
            <option value="0">Выберите</option>
            <option v-for="item in productOptions" :key="item" :value="item.id">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
import { orderFormStore } from "../store/store";
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
            productOptions,
            name,
            label,
        };
    },
};
</script>

<style scoped></style>
