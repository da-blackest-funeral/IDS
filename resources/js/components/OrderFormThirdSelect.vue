<template>
    <!--    todo задать id и name селектам (API)-->
    <label for="draftName">{{ label }}</label>
    <select
        id="draftName"
        v-model="store.profileId"
        name="draftName"
        class="form-control"
    >
        <option selected disabled value="0">Выберите</option>
        <option v-for="item in profiles" :key="item" :value="item.id">
            {{ item.name }}
        </option>
    </select>
</template>

<script>
import { orderFormStore } from "../stores/store";
import { computed } from "vue";

export default {
    name: "OrderFormThirdSelect",
    setup() {
        const store = orderFormStore();
        const profiles = computed(() => {
            return store.profiles;
        });
        const { label } = profiles;

        return {
            store,
            profiles,
            label,
        };
    },
};
</script>

<style scoped></style>
