<template>
    <h1>{{ title }}</h1>
</template>

<script>
import { ref } from "vue";

export default {
    name: "App",
    setup() {
        const title = ref("Title Composition API");

        return {
            title,
        };
    },
};
</script>

<style scoped></style>
