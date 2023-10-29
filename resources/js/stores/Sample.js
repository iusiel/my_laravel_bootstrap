import { defineStore } from "pinia";
import { ref } from "vue";

export const useSampleStore = defineStore("Sample", () => {
    const count = ref(0);
    const sampleString = ref("Hello World");

    function increment() {
        count.value++;
    }

    return { count, sampleString, increment };
});
