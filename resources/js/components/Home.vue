<script setup>
import { useSampleStore } from "@/stores/Sample";
import { storeToRefs } from "pinia";

// access the `store` variable anywhere in the component ✨
const store = useSampleStore();
const { sampleString, count } = storeToRefs(store);
const { increment } = store;
</script>

<template>
    <div @click="increment">{{ sampleString }}</div>
    <div>{{ count }}</div>
</template>
